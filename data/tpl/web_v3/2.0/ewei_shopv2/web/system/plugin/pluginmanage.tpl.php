<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .table>thead>tr>td.full, .table>tbody>tr>td.full, .table>tfoot>tr>td.full{overflow: hidden;}
</style>
<div class="page-header">
    当前位置：<span class="text-primary">应用管理</span>
</div>

<div class="page-content">
    <form action="./index.php" method="get" class="form-horizontal" plugins="form">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r"  value="system.plugin.pluginmanage" />
        <div class="page-toolbar">
            <div class="col-sm-6">
                <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('system/plugin/pluginmanage/add')?>"><i class='fa fa-plus'></i> 添加应用</a>
            </div>
            <div class="col-sm-6 pull-right">
                <div class="input-group">
                    <div class="input-group-select">
                        <select name='state' class='form-control '  >
                            <option value='' <?php  if($_GPC['state']=='') { ?>selected<?php  } ?>>状态</option>
                            <option value='1' <?php  if($_GPC['state']=='1') { ?>selected<?php  } ?>>开启</option>
                            <option value='0' <?php  if($_GPC['state']=='0') { ?>selected<?php  } ?> >关闭</option>
                        </select>
                    </div>
                    <input type="text" class=" form-control" name='keyword' value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键词"> <span class="input-group-btn">
                    <button class="btn  btn-primary" type="submit"> 搜索</button> </span>
                </div>
            </div>
        </div>
    </form>
    <form action="" method="post">
        <?php  if(!empty($list)) { ?>
        <div class="page-table-header">
            <input type="checkbox">
            <div class="btn-group">
                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch'  data-href="<?php  echo webUrl('system/plugin/pluginmanage/status',array('status'=>0))?>">
                    <i class='icow icow-jinyong'></i> 关闭
                </button>
                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('system/plugin/pluginmanage/status',array('status'=>1))?>">
                    <i class='icow icow-qiyong'></i> 开启
                </button>
                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除吗?" data-href="<?php  echo webUrl('system/plugin/pluginmanage/delete')?>">
                    <i class='icow icow-shanchu1'></i> 删除
                </button>
            </div>
        </div>
        <table class="table table-hover table-responsive">
            <thead>
            <tr>
                <th style="width:25px;"></th>
                <!--<th style="width:50px;">排序</th>-->
                <th style="width:75px;">组件/插件</th>
                <th>&nbsp;</th>
                <th style="width: 70px;text-align: center;">状态</th>
                <th style="width: 95px;text-align: center;">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($list)) { foreach($list as $row) { ?>
            <tr>
                <td>
                    <input type='checkbox'   value="<?php  echo $row['id'];?>"/>
                </td>
                <!--<td>
                    <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('system/plugin/manage/property',array('type'=>'displayorder','id'=>$row['id']))?>" ><?php  echo $row['displayorder'];?></a>
                </td>-->
                <td>
                    <img src="<?php  echo tomedia($row['thumb'])?>" style="width:40px;height:40px;padding:1px;border:1px solid #ccc;"  onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'"/>
                </td>
                <td class='full'>
                    <?php  if(!empty($row['name'])) { ?><?php  echo $row['name'];?><?php  } else { ?><?php  echo $row['pname'];?><?php  } ?>
                </td>
                <td style="text-align: center;">
                    <span class='label <?php  if($row['state']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
                    data-toggle='ajaxSwitch'
                    data-switch-value='<?php  echo $row['state'];?>'
                    data-switch-value0='0|关闭|label label-default|<?php  echo webUrl('system/plugin/pluginmanage/property',array('type'=>'state', 'value'=>1,'id'=>$row['id']))?>'
                    data-switch-value1='1|开启|label label-success|<?php  echo webUrl('system/plugin/pluginmanage/property',array('type'=>'state', 'value'=>0,'id'=>$row['id']))?>'>
                    <?php  if($row['state']==1) { ?>开启<?php  } else { ?>关闭<?php  } ?></span>
                </td>
                <td style="text-align: center;">
                    <a class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('system/plugin/pluginmanage/edit',array('id' => $row['id']));?>" title="">
                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                            <i class="icow icow-bianji2"></i>
                         </span>
                    </a>
                    <a href="javascript:;" class='btn btn-default btn-sm btn-op btn-operation js-clip' data-url="<?php  echo webUrl('plugingrant/detail', array('id' => $row['id'],'type'=>'plugin'),true)?>">
                         <span data-toggle="tooltip" data-placement="top"  data-original-title="复制链接">
                           <i class='icow icow-lianjie2'></i>
                       </span>
                    </a>
                    <a class='btn btn-default btn-sm btn-op btn-operation' data-toggle='ajaxRemove' href="<?php  echo webUrl('system/plugin/pluginmanage/delete',array('id' => $row['id']));?>" data-confirm="确定要删除该商品吗？">
                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                            <i class="icow icow-shanchu1"></i>
                         </span>
                    </a>
                </td>
            </tr>
            <?php  } } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><input type="checkbox"></td>
                    <td colspan="2">
                        <div class="btn-group">
                            <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch'  data-href="<?php  echo webUrl('system/plugin/pluginmanage/status',array('status'=>0))?>">
                                <i class='icow icow-jinyong'></i> 关闭
                            </button>
                            <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('system/plugin/pluginmanage/status',array('status'=>1))?>">
                                <i class='icow icow-qiyong'></i> 开启
                            </button>
                            <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除吗?" data-href="<?php  echo webUrl('system/plugin/pluginmanage/delete')?>">
                                <i class='icow icow-shanchu1'></i> 删除
                            </button>
                        </div>
                    </td>
                    <td colspan="2" class="text-right"><?php  echo $pager;?></td>
                </tr>
            </tfoot>
        </table>
        <?php  } else { ?>
        <div class='panel panel-default'>
            <div class='panel-body' style='text-align: center;padding:30px;'>
                暂时没有任何记录!
            </div>
        </div>
        <?php  } ?>

    </form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
