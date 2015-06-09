<?php
/**
 * +------------------------------------+
 * +    红包接口  and 企业付款 demo     +
 * +------------------------------------+
 * @author sixian
 * @version v1.0
 * @copyright  小农民科技 
 * creat_time  2015-06-04 22:17
 */

require_once "./WxPay.pub.config.php";
require_once "./sendWallet_app.php";



/**
 * 红包发送如下数据
 * 微信官方红包接口 http://pay.weixin.qq.com/wiki/doc/api/cash_coupon.php?chapter=13_5
 * +----------------------------- 红包发送demo start  --------------------------------+
 */
$SendRedpack = new sendWallet(); //红包 与 企业付款公用 类

$SendRedpack->set_mch_billno( date('YmdHis') );  				//唯一订单号
//$SendRedpack->set_mch_id( WxPayConf_pub::MCHID ); 	     	// 商户号 默认已在配置文件中配置
//$SendRedpack->set_wxappid( WxPayConf_pub::APPID );			// appid  默认已在配置文件中配置
$SendRedpack->set_nick_name('小农民科技');                		// 提供方名称     小农民科技
$SendRedpack->set_send_name(1);                 				// 红包发送者名称  商户名称
$SendRedpack->set_re_openid('o357TssWYMICmC2m1oidR5PhsQbY');    // 用户在wxappid下的openid

$SendRedpack->set_total_amount(100); // 付款金额，单位分
$SendRedpack->set_min_value(100);     // 最小红包金额，单位分
$SendRedpack->set_max_value(100);     // 最大红包金额，单位分（ 最小金额等于最大金额： min_value=max_value =total_amount）
$SendRedpack->set_total_num(1);		 // 红包发放总人数
$SendRedpack->set_wishing(1);		 // 红包祝福语 感谢您参加猜灯谜活动，祝您元宵节快乐！
$SendRedpack->set_client_ip( walletWeixinUtil::getRealIp() ); //调用接口的机器Ip地址

$SendRedpack->set_act_name('猜灯谜抢红包活动');  // 活动名称 猜灯谜抢红包活动
$SendRedpack->set_act_id(1);  					 // 活动id
$SendRedpack->set_remark('红包测试'); 			 // 备注信息 猜越多得越多，快来抢！
$SendRedpack->set_logo_imgurl('');			     // 商户logo的url
$SendRedpack->set_share_content('');			 // 分享文案
$SendRedpack->set_share_url('');				 // 分享链接
$SendRedpack->set_share_imgurl('');				 // 分享的图片url
$SendRedpack->set_nonce_str( walletWeixinUtil::getNonceStr() ); // 随机字符串

// 得到签名和其它设置的 xml 数据
$getNewData  = $SendRedpack->getSendRedpackXml($SendRedpack);

// $data = walletWeixinUtil::curl_post_ssl($getNewData['api_url'], $getNewData['xml_data']);
// echo "微信红包发送接口：<br/>\n";
// $res = @simplexml_load_string($data,NULL,LIBXML_NOCDATA);
// foreach ($res as $key => $value) {
// 	echo ($key.":<span style='color:red'>".$value. "</span><br/>\n");
// }
// exit;

//  +----------------------------- 红包发送demo end  --------------------------------+



/**
 * 企业付款发送如下数据
 * 微信官方 企业付款接口 http://pay.weixin.qq.com/wiki/doc/api/mch_pay.php?chapter=14_2
 * +----------------------------- 企业付款demo start  --------------------------------+
 */

$SendTransfers = new sendWallet();  //红包 与 企业付款公用 类

//$SendTransfers->set_mch_appid( WxPayConf_pub::APPID ); // appid  默认已在配置文件中配置
//$SendTransfers->set_mchid( WxPayConf_pub::MCHID );     // 商户号 默认已在配置文件中配置

$SendTransfers->set_nonce_str( walletWeixinUtil::getNonceStr() );    // 随机字符串
$SendTransfers->set_partner_trade_no( WxPayConf_pub::MCHID. date('YmdHis') ); // 商户订单号，需保持唯一性
$SendTransfers->set_openid('o357TssWYMICmC2m1oidR5PhsQbY');   		 // 用户在wxappid下的openid
$SendTransfers->set_check_name('NO_CHECK');     					 // 是否校验真实姓名
$SendTransfers->set_re_user_name('22');								 // 真实姓名
$SendTransfers->set_amount(1);										 // 企业付款金额，单位为分
$SendTransfers->set_desc('开发测试');								 // 企业付款操作说明信息。必填 
$SendTransfers->set_spbill_create_ip( walletWeixinUtil::getRealIp() );   // 调用接口的机器Ip地址


// 得到签名和其它设置的 xml 数据
$getNewData  = $SendTransfers->getSendTransfersXml($SendTransfers);


$data = walletWeixinUtil::curl_post_ssl($getNewData['api_url'], $getNewData['xml_data']);
$res = @simplexml_load_string($data,NULL,LIBXML_NOCDATA);
$res = json_decode(json_encode($res),true);
echo "企业付款接口：<br/>\n";
foreach ($res as $key => $value) {
	//$value = is_array($value) ? json_encode($value) : $value;
	echo ($key.":<span style='color:red'>".$value. "</span><br/>\n");
}
//  +----------------------------- 企业付款demo end  --------------------------------+

?>