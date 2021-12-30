<?php defined('IN_IA') or exit('Access Denied');?><?php  if(empty($diyitem['params']['style']) || $diyitem['params']['style']=='default1') { ?>
<div style="overflow: hidden;height: 9rem;position: relative;background: #fff">
    <div class="headinfo" style="<?php  if(!empty($diyitem['style']['background'])) { ?>border: none;<?php  } ?>;z-index: 100">
        <?php  if(!empty($diyitem['params']['seticon'])) { ?>
        <a class="setbtn" style="color: <?php  echo $diyitem['style']['textcolor'];?>;" href="<?php echo empty($diyitem['params']['setlink'])?mobileUrl('member/info'):$diyitem['params']['setlink'];?>" data-nocache="true"><i class="icon <?php  echo $diyitem['params']['seticon'];?>"></i></a>
        <?php  } ?>
        <div class="child">
            <a href="./index.php?i=<?php  echo $_W['uniacid']?>&c=entry&m=ewei_shopv2&do=mobile&r=member.log">
            <div class="title" style="color: <?php  echo $diyitem['style']['textcolor'];?>;"><?php  echo $diyitem['info']['textmoney'];?></div>
            <div class="num" style="color: <?php  echo $diyitem['style']['textlight'];?>;"><?php  echo $diyitem['info']['money'];?></div>
            </a>
            <?php  if(!empty($diyitem['params']['leftnav'])) { ?>
            <a class="btn" style="color: <?php  echo $diyitem['style']['textcolor'];?>; border-color: <?php  echo $diyitem['style']['textcolor'];?>;" href="<?php echo empty($diyitem['params']['leftnavlink'])?mobileUrl('member/recharge'):$diyitem['params']['leftnavlink'];?>" data-nocache="true"><?php  echo $diyitem['params']['leftnav'];?></a>
            <?php  } ?>
        </div>
        <div class="child userinfo" style="color: <?php  echo $diyitem['style']['textcolor'];?>;">
            <a href="<?php  echo mobileUrl('member/info')?>" data-nocache="true" style="color: <?php  echo $diyitem['style']['textcolor'];?>;">
                <div class="face <?php  echo $diyitem['style']['headstyle'];?>"><img src="<?php  echo $diyitem['info']['avatar'];?>" onerror="this.src='../addons/ewei_shopv2/static/images/noface.png'"></div>
                <div class="name"><?php  echo $diyitem['info']['nickname'];?></div>
            </a>
            <?php  if(!empty($diyitem['params']['levellink'])) { ?>
            <a class="level" href="<?php  echo $diyitem['params']['levellink'];?>">[<?php  echo $diyitem['info']['levelname'];?>] <i class="icon icon-question1" style="font-size: 0.6rem;"></i></a>
            <?php  } else { ?>
            <div class="level">[<?php  echo $diyitem['info']['levelname'];?>]</div>
            <?php  } ?>
        </div>
        <div class="child">
            <a href="./index.php?i=<?php  echo $_W['uniacid']?>&c=entry&m=ewei_shopv2&do=mobile&r=creditshop.creditlog">
            <div class="title" style="color: <?php  echo $diyitem['style']['textcolor'];?>;"><?php  echo $diyitem['info']['textcredit'];?></div>
            <div class="num" style="color: <?php  echo $diyitem['style']['textlight'];?>;"><?php  echo $diyitem['info']['credit'];?></div>
            </a>
            <?php  if(!empty($diyitem['params']['rightnav'])) { ?>
            <a class="btn" style="color: <?php  echo $diyitem['style']['textcolor'];?>; border-color: <?php  echo $diyitem['style']['textcolor'];?>;" href="<?php echo empty($diyitem['params']['rightnavlink'])?mobileUrl('creditshop'):$diyitem['params']['rightnavlink'];?>" data-nocache="true"><?php  echo $diyitem['params']['rightnav'];?></a>
            <?php  } ?>
        </div>
    </div>
    <div class="member_header"style="background: <?php  echo $diyitem['style']['background'];?>;border-color: <?php  echo $diyitem['style']['background'];?>;"></div>

    <?php  if(p('membercard') && $canmembercard) { ?>
    <a href="<?php  echo mobileUrl('membercard/index')?>" style="color: #ffffff">
        <div class='member_card'>
            <img class='icon' src='../addons/ewei_shopv2/static/images/icon-huangguan.png' />
            <div class='title'>我的会员卡</div>
            <div class='card-num'>已拥有<?php  echo $diyitem['info']['membercard'];?>张</div>
        </div>
    </a>
    <image class='cover-img' src='../addons/ewei_shopv2/static/images/cover.png' />
    <?php  } ?>
</div>
<?php  } else if($diyitem['params']['style']=='default2') { ?>
<div class="headinfo style-2" style="background: <?php  echo $diyitem['style']['background'];?>; <?php  if(!empty($diyitem['style']['background'])) { ?>border: none;<?php  } ?>">
    <?php  if(p('membercard') && $canmembercard) { ?>
    <a href="<?php  echo mobileUrl('membercard/index')?>" >
        <img class="icon-style2" src="../addons/ewei_shopv2/static/images/header-style2.png"  style="z-index: 999"/>
    </a>
    <?php  } ?>
    <?php  if(!empty($diyitem['params']['seticon'])) { ?>
    <a class="setbtn" style="color: <?php  echo $diyitem['style']['textcolor'];?>;" href="<?php  echo $diyitem['params']['setlink'];?>" data-nocache="true"><i class="icon <?php  echo $diyitem['params']['seticon'];?>"></i></a>
    <?php  } ?>
    <div class="face <?php  echo $diyitem['style']['headstyle'];?>"><img src="<?php  echo $diyitem['info']['avatar'];?>" onerror="this.src='../addons/ewei_shopv2/static/images/noface.png'"></div>
    <div class="inner" style="color: <?php  echo $diyitem['style']['textcolor'];?>;">
        <div class="name"><?php  echo $diyitem['info']['nickname'];?></div>
        <?php  if(!empty($diyitem['params']['levellink'])) { ?>
        <a class="level" href="<?php  echo $diyitem['params']['levellink'];?>" style="color: <?php  echo $diyitem['style']['textcolor'];?>;">[<?php  echo $diyitem['info']['levelname'];?>] <i class="icon icon-question1" style="font-size: 0.6rem;"></i></a>
        <?php  } else { ?>
        <div class="level">[<?php  echo $diyitem['info']['levelname'];?>]</div>
        <?php  } ?>
        <div class="credit"><?php  echo $diyitem['info']['textmoney'];?>: <span style="color: <?php  echo $diyitem['style']['textlight'];?>;"><?php  echo $diyitem['info']['money'];?></span></div>
        <div class="credit"><?php  echo $diyitem['info']['textcredit'];?>: <span style="color: <?php  echo $diyitem['style']['textlight'];?>;"><?php  echo $diyitem['info']['credit'];?></span></div>
    </div>
</div>
<?php  } ?>