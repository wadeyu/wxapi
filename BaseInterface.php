<?php
/**
 * 微信接口定义类
 *
 * @author wadeyu
 * @date 2016-06-14
 * @copyright by Rainbow
 */
namespace App\Lib\Platform\Wxapi;
interface BaseInterface{
	//-----------------------------------------------------------------------------------------------------------------------
	//接口错误代码定义
	const ERRCODE_SUCCESS = 0; //执行成功
	const ERRCODE_INVALID_ACCESSTOKEN = 40001; //获取access_token时AppSecret错误，或者access_token无效。
	//-----------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------
	//卡券模块相关
	const API_CARD_MEMBER_CARD_ACTIVATE = 'card/membercard/activate'; //激活会员卡
	const API_CARD_MEMBER_CARD_UPDATE_USER = 'card/membercard/updateuser'; //更新会员卡信息
	const API_CARD_BATCH_GET = 'card/batchget'; //批量查询卡券列表 
	const API_CARD_MEMBER_CARD_USERINFO_GET = 'card/membercard/userinfo/get'; //拉取会员信息接口
	const API_CARD_MEMBER_CARD_ACTIVATE_TEMP_INFO = 'card/membercard/activatetempinfo/get'; //跳转型一键激活临时会员卡信息
	const API_CARD_PAYGIFTMEMBERCARD_ADD =  'card/paygiftmembercard/add'; //增加支付即会员规则接口
	const API_CARD_PAYGIFTMEMBERCARD_DELETE = 'card/paygiftmembercard/delete'; //删除支付即会员规则接口
	const API_CARD_PAYGIFTMEMBERCARD_GET = 'card/paygiftmembercard/get'; //查询商户号支付即会员规则接口
	const API_CARD_CODE_CONSUME = 'card/code/consume'; //核销优惠券
	const API_CARD_CODE_UNAVAILABLE = 'card/code/unavailable'; //设置卡券失效
	const API_CARD_CREATE = 'card/create'; //创建卡券
	const API_CARD_CODE_DEPOSIT = 'card/code/deposit'; //导入code码
	const API_CARD_CODE_GETDEPOSITCOUNT = 'card/code/getdepositcount'; //查询导入code数目接口
	const API_CARD_CODE_CHECK = 'card/code/checkcode'; //核查code
	const API_CARD_MODIFY_STOCK = 'card/modifystock'; //修改库存接口
	const API_CARD_USER_GETCARDLIST = 'card/user/getcardlist'; //获取用户已领取卡券接口
	const API_CARD_MEMBER_CARD_ACTIVATE_GETURL = 'card/membercard/activate/geturl'; //获取开卡插件参数
	//-----------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------
	//用户信息相关
	const API_USER_INFO_BATCHGET = 'cgi-bin/user/info/batchget'; //批量查询用户信息

	//-----------------------------------------------------------------------------------------------------------------------
	//接口调用凭据相关
	const API_API_ACCESS_TOKEN = 'cgi-bin/token'; //api接口调用凭证
	//-----------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------
	//常用功能相关
	const API_COMM_SHORT_URL = 'cgi-bin/shorturl'; //微信短链接
	//-----------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------
	//消息管理相关
	const API_MSG_TMPL_SEND = 'cgi-bin/message/template/send'; //发送模板消息
	//-----------------------------------------------------------------------------------------------------------------------
}