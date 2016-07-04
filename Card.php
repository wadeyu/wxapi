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
 }