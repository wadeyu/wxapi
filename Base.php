<?php
/**
 * 微信接口基础类
 *
 * @author wadeyu
 * @date 2016-06-14
 * @copyright by Rainbow
 */
namespace App\Lib\Platform\Wxapi;
abstract class Base implements BaseInterface{
	protected $_appid = '';
	protected $_appsecret = '';
	protected $_apiUrl = 'https://api.weixin.qq.com/';
	protected $_oauthUrl = '';
	protected $_apiAccessToken = ''; //接口访问凭据
	public function __construct($appid,$appsecret){
		$this->_appid = $appid;
		$this->_appsecret = $appsecret;
	}

	/**
	 * 获取微信接口调用凭证
	 * 接口说明 http://mp.weixin.qq.com/wiki/11/0e4b294685f817b95cbed85ba5e82b8f.html
	 * 接口调用有限制 需要缓存accessToken减少接口调用次数,当前默认2小时(7200秒)过期
	 *
	 * @return array 正常情况返回['ret'=>["access_token"=>"ACCESS_TOKEN","expires_in"=>7200]]
	 */
	public function getApiAccessTokenFromWx(){
		$aData = [
			'grant_type' => 'client_credential',
			'appid' => $this->_appid,
			'secret' => $this->_appsecret,
		];
		$q = http_build_query($aData);
		$url = $this->_apiUrl . self::API_API_ACCESS_TOKEN;
		$url .= "?{$q}";
		return $this->_get($url);
	}

	/**
	 * 微信短链接
	 * 接口说明 http://mp.weixin.qq.com/wiki/10/165c9b15eddcfbd8699ac12b0bd89ae6.html
	 *
	 * @return 正常情况返回['ret'=>['short_url'=>'http://w.url.cn/s/xxx']]
	 */
	public function getShortUrl($longUrl){
		$longUrl = trim($longUrl);
		if(!$longUrl){
			return false;
		}
		if(!preg_match('/^https?:\/\/.*/i',$longUrl)){
			return false;
		}
		$aPost = [
			'action' => 'long2short',
			'long_url' => $longUrl,
		];
		return $this->_apiPost(self::API_COMM_SHORT_URL,$aPost);
	}

	public function isExecSuccess($errcode){
		return $errcode == self::ERRCODE_SUCCESS;
	}
	public function isInvalidAccessTokenCode($errcode){
		return $errcode == self::ERRCODE_INVALID_ACCESSTOKEN;
	}
	public function setApiAccessToken($accessToken){
		$this->_apiAccessToken = $accessToken;
	}
	public function getApiAccessToken(){
		return $this->_apiAccessToken;
	}
	/**
	 * 发起api接口请求
	 *
	 * @param $data 数组
	 *
	 * @return array
	 */
	protected function _curl($url,array $data = array(),$method='GET',array $aHeader = array()){
		$ret = false;
		$st = microtime(true);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36');
        if($method == 'POST'){
        	curl_setopt($ch,CURLOPT_POST,true);
        	if($data){
        		$aHeader[] = 'Content-Type:application/json';
        		$data = $this->_toJsonStr($data);
        		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        	}
    	}
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if($aHeader){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
        $ret = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch); 
        return ['ret'=>$ret,'errno'=>$errno,'error'=>$error];
	}
	protected function _get($url){
		$ret = $this->_curl($url);
		if($ret['ret']){
			$ret['ret'] = json_decode($ret['ret'],true);
		}
		return $ret;
	}
	protected function _post($url, array $aData = array()){
		$ret = $this->_curl($url,$aData,'POST');
		if($ret['ret']){
			$ret['ret'] = json_decode($ret['ret'],true);
		}
		return $ret;
	}
	protected function _apiPost($api, array $aData = array()){
		$accessToken = $this->getApiAccessToken();
		if(!$accessToken){
			return ['ret'=>false,'errno'=>9999,'error'=>'没有设置api接口凭证'];
		}
		$url = $this->_apiUrl . $api . "?access_token={$accessToken}";
		return $this->_post($url,$aData);
	}
	protected function _apiGet($api,$aData = array()){
		$accessToken = $this->getApiAccessToken();
		if(!$accessToken){
			return ['ret'=>false,'errno'=>9999,'error'=>'没有设置api接口凭证'];
		}
		$url = $this->_apiUrl . $api;
		$sep = (strpos($api,'?') !== false) ? '?' : '&';
		$url .= "{$sep}access_token={$accessToken}";
		if($aData){
			$tmp = http_build_query($aData);
			$url .= "&{$tmp}";
		}
		return $this->_get($url);
	}
	protected function _toJsonStr(array &$aData = []){
		$ret = '';
		$aKeys = array_keys($aData);
		$bIsAssoc = false;
		$i = 0;
		foreach($aKeys as $v){
			if($i !== $v){
				$bIsAssoc = true;
				break;
			}
			$i++;
		}
		$ret = $bIsAssoc ? '{' : '[';
		foreach($aData as $k => $v){
			$ret .= ($bIsAssoc ? "\"{$k}\":" : '');
			if(is_array($v)){
				$ret .= $this->_toJsonStr($v);
			}else{
				if(is_string($v)){
					$v = str_replace("\t",' ',$v);//制表符是JSON非法字符，反解析会失败
					$ret .= "\"{$v}\"";
				}elseif(is_int($v)){
					$ret .= $v;
				}elseif(is_bool($v)){
					$ret .= ($v ? 'true' : 'false');
				}elseif(is_null($v)){
					$ret .= 'null';
				}else{
					$ret .= '""';
				}
			}
			$ret .= ',';
		}
		$ret = rtrim($ret,',');
		$ret .= $bIsAssoc ? '}' : ']';
		return $ret;
	}
	protected function _authPost($api,array $aData = array()){}
	protected function _authGet($api,array $aData = array()){}
}
