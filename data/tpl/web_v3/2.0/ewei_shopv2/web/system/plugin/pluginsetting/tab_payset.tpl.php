<?php defined('IN_IA') or exit('Access Denied');?><div class="panel panel-default" >
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-9 col-xs-12">
                <h4 class="set_title">微信支付</h4>
            </div>
            <div class="col-lg pull-right" style="padding-top:10px;text-align: right" >
                <input type="checkbox" class="js-switch" name="data[weixin]" value="1" <?php  if($data['weixin']==1) { ?>checked<?php  } ?> />
            </div>
        </div>
        <div class="panel-body" id='certs' <?php  if(empty($data['weixin'])) { ?>style="display:none"<?php  } ?>>
            <div class="form-group">
                <label class="col-lg control-label">身份标识(appId)</label>
                <div class="col-sm-9 col-xs-12">
                    <input type='text' class='form-control' name='data[appid]' value="<?php  echo $data['appid'];?>" />
                    <div class="form-control-static">公众号身份标识</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">微信支付商户号(MchId)</label>
                <div class="col-sm-9 col-xs-12">
                    <input type='text' class='form-control' name='data[mchid]' value="<?php  echo $data['mchid'];?>" />
                    <div class="form-control-static">公众号支付请求中用于加密的密钥Key</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">商户支付密钥(API密钥)</label>
                <div class="col-sm-9 col-xs-12">
                        <input type='text' class='form-control' name='data[apikey]' value="<?php  echo $data['apikey'];?>" />
                    <div class="form-control-static">此值需要手动在腾讯商户后台API密钥保持一致</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default" >
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-9 col-xs-12">
                <h4 class="set_title">支付宝支付</h4>
            </div>
            <div class="col-lg pull-right" style="padding-top:10px;text-align: right" >
                <input type="checkbox" class="js-switch" name="data[alipay]" value="1" <?php  if($data['alipay']==1) { ?>checked<?php  } ?> />
            </div>
        </div>
        <div class="panel-body" id='alipay' <?php  if(empty($data['alipay'])) { ?>style="display:none"<?php  } ?>>
        <div class="form-group">
            <label class="col-lg control-label">收款支付宝账号</label>
            <div class="col-sm-9 col-xs-12">
                <input type='text' class='form-control' name='data[account]' value="<?php  echo $data['account'];?>" />
                <div class="form-control-static">如果开启兑换或交易功能，请填写真实有效的支付宝账号，用于收取用户以现金兑换交易积分的相关款项。如账号无效或安全码有误，将导致用户支付后无法正确对其积分账户自动充值，或进行正常的交易对其积分账户自动充值，或进行正常的交易。 如您没有支付宝帐号，
                    <a href="https://memberprod.alipay.com/account/reg/enterpriseIndex.htm" target="_blank">请点击这里注册</a></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">合作者身份</label>
            <div class="col-sm-9 col-xs-12">
                <input type='text' class='form-control' name='data[partner]' value="<?php  echo $data['partner'];?>" />
                <div class="form-control-static">支付宝签约用户请在此处填写支付宝分配给您的合作者身份，签约用户的手续费按照您与支付宝官方的签约协议为准。
                    如果您还未签约，<a href="https://memberprod.alipay.com/account/reg/enterpriseIndex.htm" target="_blank">请点击这里签约</a>；如果已签约,<a href="https://b.alipay.com/order/pidKey.htm?pid=2088501719138773&product=fastpay" target="_blank">请点击这里获取PID、Key</a>;</div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">校验密钥</label>
            <div class="col-sm-9 col-xs-12">
                <input type='text' class='form-control' name='data[secret]' value="<?php  echo $data['secret'];?>" />
                <div class="form-control-static">支付宝签约用户可以在此处填写支付宝分配给您的交易安全校验码，此校验码您可以到支付宝官方的商家服务功能处查看</div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(function () {

        $(":checkbox[name='data[weixin]']").click(function () {
            if ($(this).prop('checked')) {
                $("#certs").show();
                $(this).val(1);
            }else {
                $("#certs").hide();
                $(this).val(-1);
            }
        });
        $(":checkbox[name='data[alipay]']").click(function () {
            if ($(this).prop('checked')) {
                $("#alipay").show();
                $(this).val(1);
            }
            else {
                $("#alipay").hide();
                $(this).val(-1);
            }
        });
    })
</script>