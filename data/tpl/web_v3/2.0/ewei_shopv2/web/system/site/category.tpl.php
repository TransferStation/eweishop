<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">
    当前位置：<span class="text-primary">文章分类  <?php if(cv('system.site.category.edit')) { ?>(拖动可以排序)<?php  } ?></span>
</div>

<div class="page-content">
    <form action="" method="post" class='form-validate'>

        <table class="table  table-responsive">
            <thead class="navbar-inner">
            <tr>
                <th style="width:60px;">ID</th>
                <th >分类名称</th>
                <th style="width:80px;"></th>
                <th  style="width:80px;"></th>
            </tr>
            </thead>
            <tbody id='tbody-items'>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <tr>
                        <td>
                            <?php  echo $row['id'];?>
                            <input type="hidden" name="catid[]" value="<?php  echo $row['id'];?>" >
                        </td>

                        <td>
                            <?php if(cv('system.site.category.edit')) { ?>
                            <input type="text" class="form-control" name="catname[]" value="<?php  echo $row['name'];?>" >
                            <?php  } else { ?>
                            <?php  echo $row['name'];?>
                            <?php  } ?>
                        </td>
                        <td>
                            <input type="hidden" class="form-control" name="status[]" value="<?php  echo $row['status'];?>">
                            <label class='checkbox-inline' onclick="$(this).prev(':hidden').val( $(this).find(':checkbox').get(0).checked?'1':'0' ); ">
                                <input style="margin-top: -5px;" type='checkbox' <?php  if($row['status']==1) { ?>checked<?php  } ?> / > 显示
                            </label>
                        </td>
                        <td>
                            <?php if(cv('system.site.category.delete')) { ?>
                            <a href="<?php  echo webUrl('system/site/category/delete', array('id' => $row['id']))?>" data-toggle='ajaxRemove' class="btn btn-default btn-sm btn-op btn-operation" data-confirm="确认删除此分类?">
                                <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                                    <i class='icow icow-shanchu1'></i>
                                </span>
                            </a><?php  } ?>
                        </td>
                    </tr>
                <?php  } } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <?php if(cv('system.site.category.add')) { ?>
                        <input name="button" type="button" class="btn btn-default" value="添加分类" onclick='addCategory()'>
                        <?php  } ?>
                        <?php if(cv('system.site.category.edit|system.site.category.add')) { ?>
                        <input type="submit" class="btn btn-primary" value="保存分类">
                        <?php  } ?>
                    </td>
                </tr>
            </tfoot>
        </table>
        <?php  echo $pager;?>
    </form>
</div>
<script>

    <?php if(cv('system.site.category.edit')) { ?>
    require(['jquery.ui'],function(){
        $('#tbody-items').sortable();
    })
    <?php  } ?>

    function addCategory(){
        var html ='<tr>';
        html+='<td><i class="fa fa-plus"></i></td>';
        html+='<td>';
        html+='<input type="hidden" class="form-control" name="catid[]" value=""><input type="text" class="form-control" name="catname[]" value="">';
        html+='</td>';
        html+='<td>';
        html+='</td>';

        html+='<td></td></tr>';;
        $('#tbody-items').append(html);
    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--913702023503242914-->