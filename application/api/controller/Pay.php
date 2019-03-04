<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

class Pay extends Common
{
    //微信支付回调验证
    public function notify() {
        //获取微信返回的数据结果
        //PHP在接收传值时，常用的为：$_POST 或者 $_GET,
        //读取文件时，常用：file_get_contents()或者curl
        $postData = file_get_contents("php://input");
        //将结果转换成数组
        $getData = $this->xmlstr_to_array($postData);
        //trim()移除字符两边的字符
        if($getData['total_fee'] && ($getData['result_code'] == 'SUCCESS')){
            $order_sn_submit = trim(['out_trade_no']);
            //更新数据表订单状态
            Db::name('order')->where('order_sn_submit', $order_sn_submit)->update(['order_status'=>2]);
            echo "success";
        }else{
            echo "error";
        }
    }

    //XML转数组
    public function xmlstr_to_array($xmlstr){
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);
        return $val;
    }
}