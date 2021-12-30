<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">当前位置：<span class="text-primary">留言内容<?php if(cv('system.site.guestbook.edit')) { ?>(拖动可以排序)<?php  } ?></span></div>

<div class="page-content">

    <table class="table  table-responsive">
        <thead class="navbar-inner">
        <tr>
            <th style="width:60px;">ID</th>
            <th  style="width:100px;">昵称</th>
            <th style="width: 150px;">标题</th>
            <th>留言内容</th>
            <th style="width: 150px;">时间</th>
            <th style="width: 65px;">操作</th>
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
                    <?php  echo $row['nickname'];?>
                </td>
                <td>
                    <?php  echo $row['title'];?>
                </td>
                <td>
                    <?php  echo $row['content'];?>
                </td>
                <td>
                    <?php  echo date('Y-m-d H:i:s',$row['createtime'])?>
                </td>
                <td>
                    <a href="<?php  echo webUrl('system/site/guestbook/view', array('id' => $row['id']))?>" data-toggle='ajaxModal' class="btn  btn-op btn-operation">
                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="查看">
                            <i class='icow icow-chakan-copy'></i>
                        </span>
                    </a>

                    <?php if(cv('system.site.guestbook.delete')) { ?>
                    <a href="<?php  echo webUrl('system/site/guestbook/delete', array('id' => $row['id']))?>" data-toggle='ajaxRemove' class="btn  btn-op btn-operation" data-confirm="确认删除此留言?">
                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                            <i class='icow icow-shanchu1'></i>
                        </span>
                    </a>
                    <?php  } ?>
                </td>
            </tr>
        <?php  } } ?>
        </tbody>
    </table>
    <?php  echo $pager;?>
</div>

<script>
    function addguestbook(){
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
<!--OTEzNzAyMDIzNTAzMjQyOTE0-->