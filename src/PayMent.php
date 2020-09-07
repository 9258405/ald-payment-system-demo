<?php
/**
 *
 *支付类。
 * @author      phper_duan
 * @mail        9258405@qq.com
 * @version     1.0 版本号
 */


namespace think\duanguobin;


class PayMent
{
    protected $appName; //项目名称
    protected $appId; //项目ID
    protected $appKey;  //项目秘钥
    protected $url;//请求API地址

    public function __construct()
    {
        $this->appName = config('payment.app_name');
        $this->appId = config('payment.app_id');
        $this->appKey = config('payment.app_key');
        $this->url = config('payment.url');
    }

    /**
     * @param $order
     * @return false|mixed
     * 请求支付，只需要传入参数即可
     */
//        $order=array(
//        'order'=>'123213213',
//        'amount'=>'100',//金额 分
//        'pay_type'=>'wx_xxx',//微信支付
//        'ip'=>'10.21.141.12'
//        );
    public function payOrder($order)
    {
        $order['app_name'] = $this->appName;
        $order['app_id'] = $this->appId;
        $url = $this->url . "index";
        $data = $this->md5Sign($order);
        $result = $this->curlResult($url, $data);
        if (!$result) {
            return false;
        }
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * @param $orderNum [订单号]
     * @return false|mixed
     * 查询订单 [只需要传入订单号即可]
     */
    public function queryOrder($orderNum)
    {
        $data=[
            'order'=>$orderNum,
            'app_name'=>$this->appName,
            'app_id'=>$this->appId
        ];
        $url = $this->url . "query";
        $data = $this->md5Sign($data);
        $result = $this->curlResult($url, $data);
        if (!$result) {
            return false;
        }
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * @param $orderNum [订单号]
     * @return false|mixed
     * 订单退款接口
     */
    public function refundOrder($orderNum)
    {
        $data=[
            'order'=>$orderNum,
            'app_name'=>$this->appName,
            'app_id'=>$this->appId
        ];
        $url = $this->url . "refund";
        $data = $this->md5Sign($data);
        $result = $this->curlResult($url, $data);
        if (!$result) {
            return false;
        }
        $result = json_decode($result, true);
        return $result;

    }

    /**
     * @param $param
     * @return bool
     * 验证签名
     */
    public function notifyVerufy($param){
        return $this->verifySign($param);
    }
    /**
     * @param $data [未带SIGN的数组]
     * @return array [带SIGN的数组]
     * 生成带SIGN的访问数据
     */
    private function md5Sign($data)
    {
        unset($data['sign']);
        $data = array_filter($data);
        ksort($data);
        $str = "";
        foreach ($data as $k => $v) {
            $str .= $k . "=" . $v . "&";
        }
        $str = $str . "key=" . $this->appKey;
        $data['sign'] = md5($str);
        return $data;
    }

    /**
     * @param $data [返回的数据]
     * @return bool [是否验证成功]
     * 验证签名是否对得上
     */
    private function verifySign($data)
    {
        if (empty($data['sign'])) {
            return false;
        }
        $sign = $data['sign'];
        $data = array_filter($data);
        unset($data['sign']);
        ksort($data);
        $str = "";
        foreach ($data as $k => $v) {
            $str .= $k . "=" . $v . "&";
        }
        $str = $str . "key=" . $this->appKey;
        return md5($str) == $sign;
    }

    /**
     * @param $url [接口]
     * @param $data [数据]
     * @return bool|string
     * POST请求获取数据
     */
    private function curlResult($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        if (!$output) {
            return false;
        }
        return $output;
    }
}