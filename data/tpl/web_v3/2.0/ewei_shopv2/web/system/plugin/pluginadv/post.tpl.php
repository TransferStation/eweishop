<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">
	当前位置：<span class="text-primary"><?php  if(!empty($item['id'])) { ?>编辑<?php  } else { ?>添加<?php  } ?>幻灯片 <small><?php  if(!empty($item['id'])) { ?>修改【<?php  echo $item['advname'];?>】<?php  } ?></small></span>
</div>
 
 <div class="page-content">
     <div class="page-sub-toolbar">
            <a class="btn btn-primary btn-sm" href="<?php  echo webUrl('system/plugin/pluginadv/add')?>">添加新幻灯片</a>
     </div>
    <form action="" method="post" class="form-horizontal form-validate" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
 
                <div class="form-group">
                    <label class="col-lg control-label">排序</label>
                    <div class="col-sm-9 col-xs-12">
                                <input type="text" name="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />
                                <span class='help-block'>数字越大，排名越靠前</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label must">幻灯片标题</label>
                    <div class="col-sm-9 col-xs-12 ">
                        <input type="text" id='advname' name="advname" class="form-control" value="<?php  echo $item['advname'];?>" data-rule-required="true" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label">幻灯片图片</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image2('thumb', $item['thumb'])?>
                        <span class='help-block'>建议尺寸:1280*350 , 请将所有幻灯片图片尺寸保持一致</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label">幻灯片链接</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="link" class="form-control" value="<?php  echo $item['link'];?>" data-rule-url="true" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label">状态</label>
                    <div class="col-sm-9 col-xs-12">
                        <label class='radio-inline'>
                            <input type='radio' name='enabled' value=1' <?php  if($item['enabled']==1) { ?>checked<?php  } ?> /> 显示
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='enabled' value=0' <?php  if($item['enabled']==0) { ?>checked<?php  } ?> /> 隐藏
                        </label>
                    </div>
                </div>
            
            <div class="form-group">
                    <label class="col-lg control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="submit" value="提交" class="btn btn-primary"  />
                       <input type="button" name="back" onclick='history.back()' style='margin-left:10px;' value="返回列表" class="btn btn-default" />
                    </div>
            </div>
 
    </form>
 </div>

<script language='javascript'>
    function formcheck() {
        if ($("#advname").isEmpty()) {
            Tip.focus("advname", "请填写幻灯片名称!");
            return false;
        }
        return true;
    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>