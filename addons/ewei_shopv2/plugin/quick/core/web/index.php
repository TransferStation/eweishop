<?php
/*珍贵资源 请勿转卖*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_EweiShopV2Page extends PluginWebPage {

    public function main() {
        global $_W;

        include $this->template();
    }

}
