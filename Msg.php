<?php
/**
 * 消息管理模块
 */
 namespace App\Lib\Platform\Wxapi;

 class Msg extends Base{
 	/**
 	 * 发送模板消息
 	 * 参数格式参考: https://mp.weixin.qq.com/wiki 模板消息tab
 	 *
 	 * @param string $toUser 接收者openid
 	 * @param string $templateId 微信模板编号
 	 * @param array $data 消息主题内容
 	 * @param string $url 跳转地址
 	 * @param array $miniApp 小程序跳转配置
 	 *
 	 * @return array
 	 */
 	public function sendTmplMsg($toUser,$templateId,array $data,$url = '',$miniApp = []){
 		$aPost = [
 			'touser' => $toUser,
 			'template_id' => $templateId,
 			'data' => $data,
 		];
 		if($url){
 			$aPost['url'] = $url;
 		}
 		if($miniApp){
 			$aPost['miniprogram'] = $miniApp;
 		}
 		return $this->_apiPost(self::API_MSG_TMPL_SEND,$aPost);
 	}
 }