<?php
namespace linzening\devtools\Aliyun;
use linzening\devtools\Aliyun\DySDKLite\SignatureHelper;
/**
 * 阿里云短信发送类
 */
class AliyunSms
{
    var $accessKeyId = null;
    var $accessKeySecret = null;
    var $params = ['PhoneNumbers'=>"157xxx9218",'SignName'=>"XX网络",'TemplateCode'=>'SMS_80XXX','TemplateParam'=>['code'=>"999999","product" => "XX网络"]];
    /**
     * 构造
     */
    function __construct($acc){
        $this->accessKeyId = $acc['accessKeyId'];
        $this->accessKeySecret = $acc['accessKeySecret'];
    }
    /**
     * 发送短信
     */
    public function sendSms($config) {
        $accessKeyId = $this->accessKeyId;
        $accessKeySecret = $this->accessKeySecret;
        $params = array_merge($this->params,$config);

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();
        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );
        return $content;
    }
    /**
     * 短信发送记录查询
     */
    public function querySendDetails($config) {
        // *** 需用户填写部分 ***
        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = $this->accessKeyId;
        $accessKeySecret = $this->accessKeySecret;

        // fixme 必填: 分页大小
        $params["PageSize"] = 10;
        // fixme 必填: 当前页码
        $params["CurrentPage"] = 1;
        // 覆盖优先
        $params = array_merge($params,$config);
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();
        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, ["RegionId" => "cn-hangzhou",
                "Action" => "QuerySendDetails",
                "Version" => "2017-05-25",])
        );
        return $content;
    }
    /**
     * 批量发送短信
     */
    public function sendBatchSms() {
        $params = $this->params;
        // *** 需用户填写部分 ***
        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = $this->accessKeyId;
        $accessKeySecret = $this->accessKeySecret;
        // fixme 必填: 待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $params["PhoneNumberJson"] = ["1500000000","1500000001",];
        // fixme 必填: 短信签名，支持不同的号码发送不同的短信签名，每个签名都应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignNameJson"] = ["云通信","云通信2",];
        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = "SMS_1000000";
        // fixme 必填: 模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
        // 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $params["TemplateParamJson"] = [
            ["name" => "Tom","code" => "123",],
            ["name" => "Jack","code" => "456",],
        ];
        // todo 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        // $params["SmsUpExtendCodeJson"] = json_encode(array("90997","90998"));
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        $params["TemplateParamJson"]  = json_encode($params["TemplateParamJson"], JSON_UNESCAPED_UNICODE);
        $params["SignNameJson"] = json_encode($params["SignNameJson"], JSON_UNESCAPED_UNICODE);
        $params["PhoneNumberJson"] = json_encode($params["PhoneNumberJson"], JSON_UNESCAPED_UNICODE);

        if(!empty($params["SmsUpExtendCodeJson"] && is_array($params["SmsUpExtendCodeJson"]))) {
            $params["SmsUpExtendCodeJson"] = json_encode($params["SmsUpExtendCodeJson"], JSON_UNESCAPED_UNICODE);
        }
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();
        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, ["RegionId" => "cn-hangzhou",
                "Action" => "SendBatchSms",
                "Version" => "2017-05-25",])
        );
        return $content;
    }
}
