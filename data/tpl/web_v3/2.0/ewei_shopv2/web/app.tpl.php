<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<link href="../addons/ewei_shopv2/plugin/app/static/css/page.css?v=20170922" rel="stylesheet" type="text/css"/>

<div class="page-header">
    当前位置：
    <span class="text-primary"><?php  echo m('plugin')->getName('app')?></span>
</div>

<div class="page-content">

    <div class="alert alert-primary">
        <p><b>小程序说明</b></p>
        <p>小程序是微信小程序的管理后台，可在此设置个性化首页排版、基本设置、设置微信支付、审核发布。</p>
    </div>

    <?php  if($error) { ?>
    <div class="page-tips">
        <p><?php  echo $error;?></p>
    </div>
    <?php  } else { ?>
    <div class="wxapp-list">
        <?php  if(is_array($list)) { foreach($list as $item) { ?>
        <div class="wxapp-item">
            <div class="logo">
                <img src="<?php  echo $item['xcx_head_img'];?>" onerror="this.src='../addons/ewei_shopv2/static/images/app.jpg'"  />
            </div>
            <div class="name"><?php echo empty($item['xcx_name'])? '未设置': $item['xcx_name']?></div>
            <div class="qrcode">
                <?php  if($item['audit_status']==5) { ?>
                <img src="<?php  echo $item['xcx_head_qrcode'];?>" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'" />
                <?php  } else if($item['audit_status']>1) { ?>
                <img src="<?php  echo $item['xcx_qrcode_url'];?>" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'" />
                <?php  } else { ?>
                <div class="layer">未提交</div>
                <img src="../addons/ewei_shopv2/plugin/app/static/images/qrcode.png"  />
                <?php  } ?>
            </div>
            <div class="line"></div>
            <div class="text">
                <p>线上版本：<?php echo $item['audit_status']==1? '未提交': $item['version_id']?></p>
                <p>审核状态：
                    <?php  if(empty($auth['is_auth'])) { ?>
                        <span  class="text-success" data-toggle="popover" data-html="true" data-trigger="hover" data-placement="right" data-content="请到微信小程序公众平台查看审核状态~">新版发布</span>
                    <?php  } else { ?>
                        <?php  if($item['audit_status']==1) { ?><span>未提交</span>
                        <?php  } else if($item['audit_status']==2) { ?><span class="text-warning">待提交审核</span>
                        <?php  } else if($item['audit_status']==3) { ?><span class="text-warning">审核中</span>
                        <?php  } else if($item['audit_status']==4) { ?><span class="text-success">发布中(4)</span>
                        <?php  } else if($item['audit_status']==5) { ?><span class="text-success">发布成功</span>
                        <?php  } else if($item['audit_status']==6) { ?><span class="text-danger">审核失败</span>
                        <?php  } ?>
                    <?php  } ?>
                </p>
                <p>提交时间：
                    <span <?php  if($item['audit_status']>1 && !empty($item['version_time'])) { ?>data-toggle="tooltip"
                    data-placement="top"
                    data-original-title="<?php  echo date('Y-m-d H:i', $item['version_time'])?>"<?php  } ?>><?php echo $item['audit_status']>1? date('Y-m-d', $item['version_time']): '未提交'?></span></p>
            </div>
        </div>
        <?php  } } ?>
    </div>
    <?php  } ?>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+4-->