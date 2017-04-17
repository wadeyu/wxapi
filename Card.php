<?php
/**
 * 卡券功能模块
 *
 * @author wadeyu
 * @date 2016-06-14
 * @copyright by Rainbow
 */
 namespace App\Lib\Platform\Wxapi;

 class Card extends Base{
 	/**
 	 * 激活会员卡
 	 * 参数说明见接口激活部分 http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025283&token=&lang=zh_CN
 	 *
 	 * @param $cardNo string 卡号
 	 * @param $wxCode string 微信官方code
 	 * @param $wxCardId string 微信官方卡模板id
 	 * @param $cardBackgroundPic string 卡背景图片
 	 * @param $aExtra array 接口其它数据 
 	 *
 	 * @author wadeyu
 	 * @return array
 	 */ 
 	public function activateMemberCard($cardNo,$wxCode,$wxCardId,$cardBackgroundPic,array $aExtra = array()){
 		$aPost = [
 			'membership_number'  => $cardNo,
 			'code'				 => $wxCode,
 			'card_id'			 => $wxCardId,
 		];
 		if($cardBackgroundPic){ //卡背景 可选字段
 			$aPost['background_pic_url'] = $cardBackgroundPic;
 		}
 		$aPost = array_merge($aPost,$aExtra);
 		return $this->_apiPost(self::API_CARD_MEMBER_CARD_ACTIVATE,$aPost);
 	}

 	/**
 	 * 更新会员卡信息
 	 * 参数说明见更新会员信息接口部分 http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025283&token=&lang=zh_CN
 	 *
 	 * @param $wxCode string 微信官方code
 	 * @param $wxCardId string 微信官方卡模板id
 	 * @param $aExtra array 需要更新的数据
 	 *
 	 * @author wadeyu
 	 * @return array
 	 */
 	public function updateMemberCard($wxCode,$wxCardId,array $aExtra = array()){
 		$aExtra['code'] = $wxCode;
 		$aExtra['card_id'] = $wxCardId;
 		return $this->_apiPost(self::API_CARD_MEMBER_CARD_UPDATE_USER,$aExtra);
 	}

 	/**
 	 * 批量查询卡券列表
 	 * 参数说明见批量查询卡券列表部分 http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025272&token=&lang=zh_CN
 	 *
 	 * @param $page int 页数
 	 * @param $size int 数量 目前为止不能大于50
 	 * @param $aFilter array 过滤条件 
 	 *
 	 * @author wadeyu
 	 * @return array
 	 */
 	public function getCardByBatch($page,$size = 10,array $aFilter = array()){
 		$page = intval($page);
 		$size = intval($size);
 		if($page <= 0){
 			$page = 1;
 		}
 		if($size <= 0){
 			$size = 10;
 		}
 		if($size > 50){
 			$size = 50;
 		}
 		$aPost = array_merge(['offset'=>($page - 1)*$size,'count'=>$size],$aFilter);
 		return $this->_apiPost(self::API_CARD_BATCH_GET,$aPost);
 	}

 	/**
 	 * 拉取会员信息
 	 * 参数说明见一键激活-步骤5：拉取会员信息接口 http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025283&token=&lang=zh_CN
 	 *
 	 * @param $wxCardId string 微信卡模板id
 	 * @param $wxCode string 微信领卡code
 	 *
 	 * @author wadeyu
 	 * @return array
 	 */
 	public function getMemberCardUserInfo($wxCardId,$wxCode){
 		$wxCardId = trim($wxCardId);
 		$wxCode = trim($wxCode);
 		return $this->_apiPost(self::API_CARD_MEMBER_CARD_USERINFO_GET,['card_id'=>$wxCardId,'code'=>$wxCode]);
 	}

 	/**
 	 * 跳转型一键激活会员填写临时信息
 	 *
 	 * @param $ticket
 	 *
 	 * @author wadeyu
 	 * @return array
 	 */
 	public function getMemberCardActivateTempInfo($ticket){
 		$ticket = trim($ticket);
 		return $this->_apiPost(self::API_CARD_MEMBER_CARD_ACTIVATE_TEMP_INFO,['activate_ticket'=>$ticket]);
 	}

 	/**
 	 * 设置微信支付即会员规则
 	 * 参数详见说明 http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1466494654_K9rNz&token=&lang=zh_CN 设置支付即会员部分
 	 *
 	 * @param $wxCardId string 微信卡模板id [卡券ID，仅支持非自定义code模式的card_id和预存code模式的card_id]
 	 * @param $jumpUrl string 模板消息跳转的url，可以是商户自定义的领卡网页链接
 	 * @param $aMchidList array 支持赠券规则的支付商户号列表
 	 * @param $aExtra array 其它字段 
 	 * begin_time:规则生效时间 end_time:规则结束时间 min_cost:本次规则生效支付金额下限，与分为单位 max_cost:本次规则生效支付金额上限，与分为单位
 	 * is_locked:是否允许其他appid设置本规则内已经设置过的商户号，默认为true 
 	 *
 	 * @author wadeyu
 	 * @return array 
 	 */
 	public function setPayGiftMemberCardRule($wxCardId,$jumpUrl,array $aMchidList = [],array $aExtra = []){
 		$wxCardId = trim($wxCardId);
 		$jumpUrl = trim($jumpUrl);
 		$aPost = [
 			'card_id' => $wxCardId,
 			'jump_url' => $jumpUrl, 
 			'mchid_list' => $aMchidList,
 		];
 		if($aExtra){
 			$aPost = array_merge($aPost,$aExtra);
 		}
 		return $this->_apiPost(self::API_CARD_PAYGIFTMEMBERCARD_ADD,$aPost);
 	}

 	/**
 	 * 删除微信支付即会员规则
 	 * 参数说明详见 http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1466494654_K9rNz&token=&lang=zh_CN 删除支付即会员规则接口部分
 	 *
 	 * @param $wxCardId string 微信卡模板id 
 	 * @param $aMchidList array 本次删除的支付即会员的商户号列表
 	 *
 	 * @author wadeyu
 	 * @return array 
 	 */
 	public function deletePayGiftMemberCardRule($wxCardId,array $aMchidList = array()){
 		$wxCardId = trim($wxCardId);
 		$aPost = [
 			'card_id' => $wxCardId,
 			'mchid_list' => $aMchidList,
 		];
 		return $this->_apiPost(self::API_CARD_PAYGIFTMEMBERCARD_DELETE,$aPost);
 	}

 	/**
 	 * 查询微信支付即会员规则
 	 * 参数说明详见 http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1466494654_K9rNz&token=&lang=zh_CN 查询支付即会员规则接口部分
 	 *
 	 * @param $mchid string 商户号
 	 *
 	 * @author wadeyu
 	 * @return array
 	 */
 	public function getPayGiftMemberCardRule($mchid){
 		$mchid = trim($mchid);
 		$aPost = [
 			'mchid' => $mchid,
 		];
 		return $this->_apiPost(self::API_CARD_PAYGIFTMEMBERCARD_GET,$aPost);
 	}

 	/**
 	 * 核销卡券
 	 *
 	 * @param string $code 券码
 	 * @param string $cardId 卡券id
 	 *
 	 * @return array
 	 */
 	public function consumeCode($code,$cardId){
 		$code = trim($code);
 		$cardId = trim($cardId);
 		$aPost = [
 			'code' => $code,
 			'card_id' => $cardId,
 		];
 		return $this->_apiPost(self::API_CARD_CODE_CONSUME,$aPost);
 	}

 	/**
 	 * 设置卡券失效
 	 *
 	 * @param string $code 券码
 	 * @param string $cardId 卡券id
 	 *
 	 * @return array
 	 */
 	public function disableCode($code,$cardId){
 		$code = trim($code);
 		$cardId = trim($cardId);
 		$aPost = [
 			'code' => $code,
 			'card_id' => $cardId,
 		];
 		return $this->_apiPost(self::API_CARD_CODE_UNAVAILABLE,$aPost);
 	}

 	/**
 	 * 创建卡券
 	 *
 	 * @param array $aPost 字段列表
 	 *
 	 * @return array
 	 */
 	public function createCard(array $aPost){
 		return $this->_apiPost(self::API_CARD_CREATE,$aPost);
 	}

 	/**
 	 * 导入code码
 	 *
 	 * @param string $cardId 卡券id
 	 * @param array $aCodes 券码列表
 	 *
 	 * @return array
 	 */
 	public function depositCode($cardId,$aCodes){
 		$aPost = ['card_id' => $cardId,'code'=>$aCodes];
 		return $this->_apiPost(self::API_CARD_CODE_DEPOSIT,$aPost);
 	}

 	/**
 	 * 查询导入code数量
 	 *
 	 * @param string $cardId 卡券id
 	 *
 	 * @return array
 	 */
 	public function getDepositCount($cardId){
 		$aPost = ['card_id' => $cardId];
 		return $this->_apiPost(self::API_CARD_CODE_GETDEPOSITCOUNT,$aPost);
 	}

 	/**
 	 * 核查导入的code
 	 *
 	 * @param string $cardId 卡券id
 	 *
 	 * @return array
 	 */
 	public function checkCode($cardId){
 		$aPost = ['card_id' => $cardId];
 		return $this->_apiPost(self::API_CARD_CODE_CHECK,$aPost);
 	}

 	/**
 	 * 修改库存
 	 *
 	 * @param string $cardId 卡券Id
 	 * @param int $num 库存数量 加减
 	 *
 	 * @return array
 	 */
 	public function modifyStock($cardId,$num){
 		$num = (int)$num;
 		if($num === 0){
 			return [];
 		}
 		$aPost = ['card_id' => $cardId];
 		if($num > 0){
 			$aPost['increase_stock_value'] = $num;
 		}else{
 			$aPost['reduce_stock_value'] = 0-$num;
 		}
 		return $this->_apiPost(self::API_CARD_MODIFY_STOCK,$aPost);
 	}


 }