# alading/pay-server
这是一个私人的包，请勿下载

## 配置
### 公共配置
```
// 支付配置   ./config/payment.php里面
return  [
    'app_name'=>'xxxx', //项目名称
    'app_id'=>'xxxx', //项目ID
    'app_key'=>'xxxx',//项目秘钥
    'url'=>'xxxx' //支付API
];
```


## 使用
判断权限方法
```
// 引入类库
use think\duanguobin\PayMent;
// 支付
$order=array(
    'order'=>'123213213',
    'amount'=>'100',//金额 分
    'pay_type'=>'wx_pay',//微信支付
    'ip'=>'47.112.237.45'
);
(new PayMent())->payOrder($order);
// 退款
(new PayMent())->refundOrder('123213213');

// 查询
(new PayMent())->queryOrder('12321321113');
```
