<?php
/*珍贵资源 请勿转卖*/
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Show_EweiShopV2Page extends PluginWebPage
{

    function main()
    {
        global $_W, $_GPC;
        $id =  intval($_GPC['id']);
        $goodsId = $_GPC['goodsid'];
        $poster = pdo_fetch('select * from ' . tablename('ewei_shop_postera') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
   
        $this->model->create_folder();
        $httpImg = $this->model->previewPoster($poster,$goodsId,$id);
        header("Location: $httpImg", true, 301);
    }


}
