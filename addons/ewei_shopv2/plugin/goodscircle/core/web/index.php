<?php
/*珍贵资源 请勿转卖*/
if (!defined('IN_IA')) {

    exit('Access Denied');

}

class Index_EweiShopV2Page extends PluginWebPage
{
    function main()
    {
        global $_W;

        if (cv('goodscircle.set')) {
            header('location: ' . webUrl('goodscircle.set'));
        } else {
            header('location: ' . webUrl());
        }

    }

    /**
     * 好物圈后台设置
     * author 洋葱
     */
    function set(){
        global $_W, $_GPC;
        $set = m('common')->getPluginset('goodscircle');
        if ($_W['ispost']){
            $type = $_GPC['type'];
            $status = intval($_GPC['status']);
            $set_items = array(
                'goods_share'   => '商品详情推荐',
                'order'   => '订单推荐',
                'cart'   => '购物车分享',
                'goods_sync'   => '物品信息同步',
            );
            if(!in_array($type,array_keys($set_items))){
                show_json(0, '未知的设置类型');
            }
            $post = array($type => $status);
            $data = array_merge($set,$post);
            m('common')->updatePluginset(array('goodscircle'=>$data));
            plog('goodscircle.set.main', "修改好物圈基本设置<br>{$set_items[$type]} : ".$status);
            show_json(1);
        }
        include $this->template();
    }

}