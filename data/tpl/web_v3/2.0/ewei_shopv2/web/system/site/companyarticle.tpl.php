<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">当前位置：<span class="text-primary">内容管理</span></div>

<div class="page-content">

    <form action="./index.php" method="get" class="form-horizontal form-search" role="form">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r"  value="system.site.companyarticle" />
        <div class="page-toolbar">
            <div class="col-sm-4">
                <?php if(cv('shop.adv.add')) { ?>
                    <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('system/site/companyarticle/add')?>"><i class='fa fa-plus'></i> 添加内容</a>
                <?php  } ?>
            </div>

            <div class="col-sm-6 pull-right">
                <div class="input-group">
                    <span class="input-group-select">
                        <select name="enabled" class='form-control'>
                            <option value="" <?php  if($_GPC['status'] == '') { ?> selected<?php  } ?>>状态</option>
                            <option value="1" <?php  if($_GPC['status']== '1') { ?> selected<?php  } ?>>显示</option>
                            <option value="0" <?php  if($_GPC['status'] == '0') { ?> selected<?php  } ?>>隐藏</option>
                        </select>
                    </span>
                    <input type="text" class="form-control" name='keyword' value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键词" />
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"> 搜索</button>
                    </span>
                </div>
            </div>
        </div>
    </form>

    <form action="" method="post">
        <?php  if(count($list)>0) { ?>
            <div class="page-table-header">
                <input type='checkbox' />
                <div class="btn-group">
                    <?php if(cv('system.site.companyarticle.edit')) { ?>
                    <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('system/site/companyarticle/status',array('status'=>1))?>">
                        <i class='icow icow-xianshi'></i> 显示</button>
                    <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch'  data-href="<?php  echo webUrl('system/site/companyarticle/status',array('status'=>0))?>">
                        <i class='icow icow-yincang'></i> 隐藏</button>
                    <?php  } ?>
                    <?php if(cv('system.site.companyarticle.delete')) { ?>
                    <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('system/site/companyarticle/delete')?>">
                        <i class='icow icow-shanchu1'></i> 删除</button>
                    <?php  } ?>
                </div>
            </div>
            <table class="table table-responsive table-hover" >
                <thead class="navbar-inner">
                <tr>
                    <th style="width:25px;"></th>
                    <th style='width:50px'>顺序</th>
                    <th>标题</th>
                    <th style="width: 110px;">分类</th>
                    <th style='width:60px'>显示</th>
                    <th style="width: 65px;">操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                        <tr>
                            <td>
                                <input type='checkbox'   value="<?php  echo $row['id'];?>"/>
                            </td>
                            <td>
                                <?php if(cv('system.site.companyarticle.edit')) { ?>
                                    <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('system/site/companyarticle/displayorder',array('id'=>$row['id']))?>" ><?php  echo $row['displayorder'];?></a>
                                <?php  } else { ?>
                                    <?php  echo $row['displayorder'];?>
                                <?php  } ?>
                            </td>
                            <td><?php  echo $row['title'];?></td>
                            <td><?php  echo $category[$row['cate']]['name'];?></td>
                            <td>
                                <span class='label <?php  if($row['status']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
                                    <?php if(cv('system.site.companyarticle.edit')) { ?>
                                    data-toggle='ajaxSwitch'
                                    data-switch-value='<?php  echo $row['status'];?>'
                                    data-switch-value0='0|隐藏|label label-default|<?php  echo webUrl('system/site/companyarticle/status',array('status'=>1,'id'=>$row['id']))?>'
                                    data-switch-value1='1|显示|label label-primary|<?php  echo webUrl('system/site/companyarticle/status',array('status'=>0,'id'=>$row['id']))?>'
                                    <?php  } ?>
                                >
                                <?php  if($row['status']==1) { ?>显示<?php  } else { ?>隐藏<?php  } ?></span>
                            </td>
                            <td style="text-align:left;">
                                <?php if(cv('system.site.companyarticle.view|system.site.companyarticle.edit')) { ?>
                                <a href="<?php  echo webUrl('system/site/companyarticle/edit', array('id' => $row['id']))?>" class="btn btn-op btn-operation">
                                    <span data-toggle="tooltip" data-placement="top" data-original-title="<?php if(cv('system.site.companyarticle.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>">
                                        <i class='icow icow-bianji2'></i>
                                    </span>
                                </a>
                                <?php  } ?>
                                <?php if(cv('system.site.companyarticle.delete')) { ?>
                                    <a data-toggle='ajaxRemove' href="<?php  echo webUrl('system/site/companyarticle/delete', array('id' => $row['id']))?>"class="btn btn-op btn-operation" data-confirm='确认要删除此文章吗?'>
                                    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                                        <i class='icow icow-shanchu1'></i>
                                    </span>
                                </a>
                                 <?php  } ?>
                            </td>
                        </tr>
                    <?php  } } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <input type="checkbox"/>
                        </td>
                        <td colspan="2">
                            <div class="btn-group">
                                <?php if(cv('system.site.companyarticle.edit')) { ?>
                                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('system/site/companyarticle/status',array('status'=>1))?>">
                                    <i class='icow icow-xianshi'></i> 显示</button>
                                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch'  data-href="<?php  echo webUrl('system/site/companyarticle/status',array('status'=>0))?>">
                                    <i class='icow icow-yincang'></i> 隐藏</button>
                                <?php  } ?>
                                <?php if(cv('system.site.companyarticle.delete')) { ?>
                                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('system/site/companyarticle/delete')?>">
                                    <i class='icow icow-shanchu1'></i> 删除</button>
                                <?php  } ?>
                            </div>
                        </td>
                        <td colspan="3" style="text-align: right">
                            <?php  echo $pager;?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        <?php  } else { ?>
            <div class='panel panel-default'>
                <div class='panel-body empty-data'>暂时没有任何文章!</div>
            </div>
        <?php  } ?>
    </form>
</div>


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--青岛易联互动网络科技有限公司-->