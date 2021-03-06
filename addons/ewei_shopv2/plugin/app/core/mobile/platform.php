<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
require_once EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';

class Platform_EweiShopV2Page extends AppMobilePage
{

    /**
     * 获取公众号列表
     */
    public function get_wx_list() {
        global $_GPC;

        // 验证授权信息
        $this->verifySign();

        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;

        list($list,$total) = $this->oldAccount($pindex,$psize);

        if(!empty($list)) {
            foreach($list as &$account) {
                if (function_exists('uni_account_list')) {
                    $account_details = uni_accounts($account['uniacid']);
                }else{
                    $account_details = $this->uni_accounts($account['uniacid']);
                }

                if(!empty($account_details)) {
                    $account_detail = $account_details[$account['uniacid']];
                    $account['thumb'] = tomedia('headimg_'.$account_detail['acid']. '.jpg').'?time='.time();
                    $account['appid'] = $account_detail['key'];
                }
            }
            unset($account_val);
            unset($account);
        }

        die(app_json(array(
            'list'=>$list,
            'pagesize'=>$psize,
            'total'=>$total
        )));
    }

    private function uni_accounts($uniacid = 0) {
        global $_W;
        $uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
        $account_info = pdo_get('account', array('uniacid' => $uniacid));
        if (!empty($account_info)) {
            if (function_exists('uni_account_type')) {
                $account_tablename = uni_account_type($account_info['type']);
            }else{
                $account_details = $this->uni_account_type($account['uniacid']);
            }
            $account_tablename = $account_tablename['table_name'];
            $accounts = pdo_fetchall("SELECT w.*, a.type, a.isconnect FROM " . tablename('account') . " a INNER JOIN " . tablename($account_tablename) . " w USING(acid) WHERE a.uniacid = :uniacid AND a.isdeleted <> 1 ORDER BY a.acid ASC", array(':uniacid' => $uniacid), 'acid');
        }
        return !empty($accounts) ? $accounts : array();
    }

    /**
     * 微擎方法没有
     * @auth xzx
     */
    function uni_account_type($type = 0) {
        $all_account_type = array(
            ACCOUNT_TYPE_OFFCIAL_NORMAL => array(
                'title' => '公众号',
                'type_sign' => ACCOUNT_TYPE_SIGN,
                'table_name' => 'account_wechats',
                'module_support_name' => MODULE_SUPPORT_ACCOUNT_NAME,
                'module_support_value' => MODULE_SUPPORT_ACCOUNT,
                'store_type_module' => STORE_TYPE_MODULE,
                'store_type_number' => STORE_TYPE_ACCOUNT,
                'store_type_renew' => STORE_TYPE_ACCOUNT_RENEW,
            ),
            ACCOUNT_TYPE_OFFCIAL_AUTH => array(
                'title' => '公众号',
                'type_sign' => ACCOUNT_TYPE_SIGN,
                'table_name' => 'account_wechats',
                'module_support_name' => MODULE_SUPPORT_ACCOUNT_NAME,
                'module_support_value' => MODULE_SUPPORT_ACCOUNT,
                'store_type_module' => STORE_TYPE_MODULE,
                'store_type_number' => STORE_TYPE_ACCOUNT,
                'store_type_renew' => STORE_TYPE_ACCOUNT_RENEW,
            ),
            ACCOUNT_TYPE_APP_NORMAL => array(
                'title' => '微信小程序',
                'type_sign' => WXAPP_TYPE_SIGN,
                'table_name' => 'account_wxapp',
                'support_version' => 1,
                'version_tablename' => 'wxapp_versions',
                'module_support_name' => MODULE_SUPPORT_WXAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_WXAPP,
                'store_type_module' => STORE_TYPE_WXAPP_MODULE,
                'store_type_number' => STORE_TYPE_WXAPP,
                'store_type_renew' => STORE_TYPE_WXAPP_RENEW,
            ),
            ACCOUNT_TYPE_APP_AUTH => array(
                'title' => '微信小程序',
                'type_sign' => WXAPP_TYPE_SIGN,
                'table_name' => 'account_wxapp',
                'support_version' => 1,
                'version_tablename' => 'wxapp_versions',
                'module_support_name' => MODULE_SUPPORT_WXAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_WXAPP,
                'store_type_module' => STORE_TYPE_WXAPP_MODULE,
                'store_type_number' => STORE_TYPE_WXAPP,
                'store_type_renew' => STORE_TYPE_WXAPP_RENEW,
            ),
            ACCOUNT_TYPE_WEBAPP_NORMAL => array(
                'title' => 'PC',
                'type_sign' => WEBAPP_TYPE_SIGN,
                'table_name' => 'account_webapp',
                'module_support_name' => MODULE_SUPPORT_WEBAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_WEBAPP,
                'store_type_module' => STORE_TYPE_WEBAPP_MODULE,
                'store_type_number' => STORE_TYPE_WEBAPP,
                'store_type_renew' => STORE_TYPE_WEBAPP_RENEW,
            ),
            ACCOUNT_TYPE_PHONEAPP_NORMAL => array(
                'title' => 'APP',
                'type_sign' => PHONEAPP_TYPE_SIGN,
                'table_name' => 'account_phoneapp',
                'support_version' => 1,
                'version_tablename' => 'wxapp_versions',
                'module_support_name' => MODULE_SUPPORT_PHONEAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_PHONEAPP,
                'store_type_module' => STORE_TYPE_PHONEAPP_MODULE,
                'store_type_number' => STORE_TYPE_PHONEAPP,
                'store_type_renew' => STORE_TYPE_PHONEAPP_RENEW,
            ),
            ACCOUNT_TYPE_XZAPP_NORMAL => array(
                'title' => '熊掌号',
                'type_sign' => XZAPP_TYPE_SIGN,
                'table_name' => 'account_xzapp',
                'module_support_name' => MODULE_SUPPORT_XZAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_XZAPP,
                'store_type_module' => STORE_TYPE_XZAPP_MODULE,
                'store_type_number' => STORE_TYPE_XZAPP,
                'store_type_renew' => STORE_TYPE_XZAPP_RENEW,
            ),
            ACCOUNT_TYPE_XZAPP_AUTH => array(
                'title' => '熊掌号',
                'type_sign' => XZAPP_TYPE_SIGN,
                'table_name' => 'account_xzapp',
                'module_support_name' => MODULE_SUPPORT_XZAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_XZAPP,
                'store_type_module' => STORE_TYPE_XZAPP_MODULE,
                'store_type_number' => STORE_TYPE_XZAPP,
                'store_type_renew' => STORE_TYPE_XZAPP_RENEW,
            ),
            ACCOUNT_TYPE_ALIAPP_NORMAL => array(
                'title' => '支付宝小程序',
                'type_sign' => ALIAPP_TYPE_SIGN,
                'table_name' => 'account_aliapp',
                'support_version' => 1,
                'version_tablename' => 'wxapp_versions',
                'module_support_name' => MODULE_SUPPORT_ALIAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_ALIAPP,
                'store_type_module' => STORE_TYPE_ALIAPP_MODULE,
                'store_type_number' => STORE_TYPE_ALIAPP,
                'store_type_renew' => STORE_TYPE_ALIAPP_RENEW,
            ),
            ACCOUNT_TYPE_BAIDUAPP_NORMAL => array(
                'title' => '百度小程序',
                'type_sign' => BAIDUAPP_TYPE_SIGN,
                'table_name' => 'account_baiduapp',
                'support_version' => 1,
                'version_tablename' => 'wxapp_versions',
                'module_support_name' => MODULE_SUPPORT_BAIDUAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_BAIDUAPP,
                'store_type_module' => STORE_TYPE_BAIDUAPP_MODULE,
                'store_type_number' => STORE_TYPE_BAIDUAPP,
                'store_type_renew' => STORE_TYPE_BAIDUAPP_RENEW,
            ),
            ACCOUNT_TYPE_TOUTIAOAPP_NORMAL => array(
                'title' => '头条小程序',
                'type_sign' => TOUTIAOAPP_TYPE_SIGN,
                'table_name' => 'account_toutiaoapp',
                'support_version' => 1,
                'version_tablename' => 'wxapp_versions',
                'module_support_name' => MODULE_SUPPORT_TOUTIAOAPP_NAME,
                'module_support_value' => MODULE_SUPPORT_TOUTIAOAPP,
                'store_type_module' => STORE_TYPE_TOUTIAOAPP_MODULE,
                'store_type_number' => STORE_TYPE_TOUTIAOAPP,
                'store_type_renew' => STORE_TYPE_TOUTIAOAPP_RENEW,
            ),
        );
        if (!empty($type)) {
            return !empty($all_account_type[$type]) ? $all_account_type[$type] : array();
        }
        return $all_account_type;
    }
    /**
     * 验证域名
     */
    public function verifydomain() {
        // 验证授权信息
        $this->verifySign();

        load()->func('communication');
        $result = ihttp_post(EWEI_SHOPV2_AUTH_WXAPP. 'auth/auth', array(
            'host'=>$_SERVER['HTTP_HOST']
        ));
    }

    /**
     * 验证权限
     */
    private function verifySign() {
        global $_GPC;

        $time = trim($_GPC['time']);
        if(empty($time)){
            return app_error(AppError::$ParamsError, '参数错误(time)');
        }

        // 5分钟之内有效
        if(($time + 300) < time()) {
            return app_error(AppError::$ParamsError, 'sign失效');
        }

        $sign = trim($_GPC['sign']);
        if(empty($time)){
            return app_error(AppError::$ParamsError, '参数错误(sign)');
        }
        $setting = setting_load('site');

        $site_id = isset($setting['site']['key']) ? $setting['site']['key'] : (isset($setting['key']) ? $setting['key'] : '0');
        if(empty($site_id)){
            return app_error(AppError::$ParamsError, '参数错误(site_id)');
        }

        $sign_str = md5(md5('site_id='. $site_id. '&request_time='. $time. '&salt=FOXTEAM'));
        if($sign != $sign_str){
            return app_error(AppError::$RequestError);
        }
    }

    /**
     * 读取公众号列表
     * @param $pindex
     * @param $psize
     * @return array
     */
    private function oldAccount($pindex,$psize){
        global $_GPC,$_W;

        $start = ($pindex - 1) * $psize;
        $condition = '';
        $param = array();
        $keyword = trim($_GPC['keyword']);

        $condition .= " WHERE a.default_acid <> 0 AND b.isdeleted <> 1 AND (b.type = ".ACCOUNT_TYPE_OFFCIAL_NORMAL." OR b.type = ".ACCOUNT_TYPE_OFFCIAL_AUTH.")";
        $order_by = " ORDER BY a.`rank` DESC";

        if(!empty($keyword)) {
            $condition .=" AND a.`name` LIKE :name";
            $param[':name'] = "%{$keyword}%";
        }
        $tsql = "SELECT COUNT(*) FROM " . tablename('uni_account'). " as a LEFT JOIN". tablename('account'). " as b ON a.default_acid = b.acid {$condition} {$order_by}, a.`uniacid` DESC";
        $total = pdo_fetchcolumn($tsql, $param);

        $list = array();
        if(!empty($total)){
            $sql = "SELECT a.name, a.uniacid FROM ". tablename('uni_account'). " as a LEFT JOIN". tablename('account'). " as b ON a.default_acid = b.acid {$condition} {$order_by}, a.`uniacid` DESC LIMIT {$start}, {$psize}";
            $list = pdo_fetchall($sql, $param);
        }

        return array($list,$total);
    }

}