<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
require_once EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';

class Refund_EweiShopV2Page extends AppMobilePage {

    protected function globalData() {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];
        $orderid = intval($_GPC['id']);

        $order = pdo_fetch("select id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime,isverify,`virtual`,refundstate,merchid from " . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1'
            , array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));
        if (empty($order)) {
            return app_error(AppError::$OrderNotFound);
        }

        $_err = '';
        if ($order['status'] <= 0) {
            $_err = '订单未付款或已关闭，不能申请退款!';
        } else {
            if ($order['status'] == 3) {
                if (!empty($order['virtual']) || $order['isverify'] == 1) {
                    $_err = '此订单不允许退款!';
                } else {
                    if ($order['refundstate'] == 0) {
                        //申请退款
                        $tradeset = m('common')->getSysset('trade');
                        $refunddays = intval($tradeset['refunddays']);
                        if ($refunddays > 0) {
                            $days = intval((time() - $order['finishtime']) / 3600 / 24);
                            if ($days > $refunddays) {
                                $_err = '订单完成已超过 ' . $refunddays . ' 天, 无法发起退款申请!';
                            }
                        } else {
                            $_err = '订单完成, 无法申请退款!';
                        }
                    }
                }
            }
        }
        $refund = pdo_fetch("select id,orderid from ".tablename('ewei_shop_order_refund')." where uniacid = :uniacid and orderid = :orderid and status = 1 limit 1 ",array(':uniacid'=>$uniacid,':orderid'=>$orderid));
        if(!empty($refund)){
            $_err = '此订单已完成维权，不能申请退款';
        }

        if (!empty($_err)) {
            return app_error(AppError::$OrderCanNotRefund, $_err);
        }


        //订单不能退货商品

        /*********************************************************************/
        $order['cannotrefund'] = true;
        $refundgoods = array(
            'refund' => true,
            'returngoods' => true,
            'exchange' => true,
        );

        if($order['status']>1){
            $goods = pdo_fetchall("select og.goodsid, og.price, og.total, og.optionname, g.cannotrefund,g.refund,g.returngoods,g.exchange, g.thumb, g.type, g.title from".tablename("ewei_shop_order_goods") ." og left join ".tablename("ewei_shop_goods")." g on g.id=og.goodsid where og.orderid=".$order['id']);
            if(!empty($goods)){
                foreach ($goods as $g){
                    /*
                     * 退款优化V1.10
                     * 张洪利2019-09-16
                     * */
                    if(empty($g['cannotrefund'])){
                        $g['refund'] =  true ;
                        $g['returngoods'] = true;
                        $g['exchange'] = true;
                    }
                    if($order['status']>=2){
                        /*
                         * 退款优化V1.10
                         * 张洪利2019-09-16
                         * */
                        if(!empty($g['cannotrefund']) && empty($g['refund']) && empty($g['returngoods']) && empty($g['exchange'])){
                            $order['cannotrefund'] =  false ;
                        }
                    }
                    if($order['status']==1){
                        /*
                         * 退款优化V1.10
                         * 张洪利2019-09-16
                         * */
                        if(!empty($g['cannotrefund']) && empty($g['refund'])){
                            $order['cannotrefund'] =  false ;
                        }
                    }
                    //虚拟商品完成订单
                    if($order['status']>=3 && $g['type']==2 ){
                        $g['returngoods'] = false;
                        $g['exchange'] = false;
                    }
                    $refundgoods['refund'] = empty($refundgoods['refund']) ? false :$g['refund'];
                    $refundgoods['returngoods'] = empty($refundgoods['returngoods']) ? false :$g['returngoods'];
                    $refundgoods['exchange'] = empty($refundgoods['exchange']) ? false :$g['exchange'];
                    /*if($g['cannotrefund']==1){
                        $order['cannotrefund'] = true;
                        break;
                    }*/
                }
            }
        }

        if($order['cannotrefund'] && empty($refundgoods['refund']) && empty($refundgoods['returngoods']) && empty($refundgoods['exchange'])){
            return app_error(AppError::$OrderCanNotRefund,'此订单不可退换货');
        }


        //是否全返商品，并检测是否允许退款
        $fullback_log = pdo_fetchall("select * from ".tablename('ewei_shop_fullback_log')." where orderid = ".$orderid." and uniacid = ".$uniacid." ");
        $fullbackkprice = 0;
        if($fullback_log&&is_array($fullback_log)){
            foreach ($fullback_log as $key=>$value){
                $fullbackgoods = pdo_fetch("select refund from ".tablename('ewei_shop_fullback_goods')." where goodsid = ".$value['goodsid']." and uniacid = ".$uniacid." ");
                if($fullbackgoods['refund'] ==0){
                    $this->message("此订单包含全返产品不允许退款");
                }
            }
            foreach ($fullback_log as $k=>$val){
                if($val['fullbackday']>0){
                    if($val['fullbackday']<$val['day']){
                        $fullbackkprice += $val['priceevery'] * $val['fullbackday'];
                    }else{
                        $fullbackkprice += $val['price'];
                    }
                }
            }
        }
        $order['price'] = $order['price'] - $fullbackkprice;




        $rtypeArr = array();
        if($order['cannotrefund'] ){
            $rtypeArr = array(
                0 => $refundgoods['refund'] ? '退款(仅退款不退货)':'',
                1 => $refundgoods['returngoods'] ? '退货退款':'',
                2 => $refundgoods['exchange'] ? '换货':'',
            );
            $rtypeArr = array_filter($rtypeArr);
        }else{
            $rtypeArr = array(
                '退款(仅退款不退货)',
                '退货退款',
                '换货'
            );
        }



        //应该退的钱 在线支付的+积分抵扣的+余额抵扣的(运费包含在在线支付或余额里）
        $order['refundprice'] = $order['price'] + $order['deductcredit2'];
        if ($order['status'] >= 2) {
            //如果发货，扣除运费
            $order['refundprice']-= $order['dispatchprice'];
        }
        $order['refundprice'] = round($order['refundprice'],2);

        return array('uniacid' => $uniacid, 'openid' => $_W['openid'], 'orderid' => $orderid, 'order' => $order, 'rtypeArr' => $rtypeArr , 'refundid' => $order['refundid']);
    }

    function main() {

        global $_W, $_GPC;
        $globaldata = $this->globalData();
        if(is_error($globaldata)){
            return app_error($globaldata['message']);
        }
        extract($globaldata);
        if ( $order['status'] == '-1')
            return app_error(AppError::$OrderCanNotResubmit);
        $refund = array();
        $imgnum = 0;
        if ($order['refundstate'] > 0) {
            if (!empty($refundid)) {
                $refund = pdo_fetch("select * from " . tablename('ewei_shop_order_refund') . ' where id=:id and uniacid=:uniacid and orderid=:orderid limit 1'
                    , array(':id' => $refundid, ':uniacid' => $uniacid, ':orderid' => $orderid));
                if (!empty($refund['refundaddress'])) {
                    $refund['refundaddress'] = iunserializer($refund['refundaddress']);
                }
            }
            if (!empty($refund['imgs'])) {
                $refund['imgs'] = iunserializer($refund['imgs']);
            }
        }

        if (empty($refund)) {
            $show_price =round( $order['refundprice'],2);
        } else {
            $show_price = round($refund['applyprice'],2);
            $refund['createtime'] = date('Y-m-d H:i:s',$refund['createtime']);

            $reasonArr = array('不想要了','卖家缺货','拍错了/订单信息错误','其它');
            $reasonIndex = array_search($refund['reason'],$reasonArr);
            if ($refund['status']==3){
                $refund['statusstr'] = '需填写快递单号';
            }elseif($refund['status']==4){
                $refund['statusstr'] = '等待商家确认';
            }elseif($refund['status']==5){
                $refund['statusstr'] = '商家已经发货';
            }
        }

        $express_list = m('express')->getExpressList();

        return app_json(array(
            'title' => ($order['status']==1?'退款':'售后').'申请',
            'refundtype' => $refund['rtype'],
            'refundreason' => ($refund['rtype']=='2'?'换货':'退款').'原因',
            'refundexplain' => ($refund['rtype']=='2'?'换货':'退款').'说明',
            'price' => $show_price,
            'content' => isset($refund['content'])?$refund['content']:'',
            'rtypeArr'=>$rtypeArr,
            'order'=>$order,
            'refund'=>$refund,
            'images'=>is_array($refund['imgs'])?$refund['imgs']:array(),
            'imgs'=>!empty($images)?$images:array(),
            'express_list'=>$express_list,
            'rtypeIndex'=> !empty($refund)?$refund['rtype']:0,
            'reasonIndex'=> !empty($reasonIndex)?$reasonIndex:0
        ));
    }

    //提交
    function submit() {

        global $_W, $_GPC;
        extract($this->globalData());
        if($order['status'] == '-1'){
            return app_error(AppError::$OrderCanNotRefund, '订单已经处理完毕');
        }

        $price = trim($_GPC['price']);
        $rtype = intval($_GPC['rtype']);
        if ($rtype != 2) {
            if (empty($price) && $order['deductprice'] == 0) {
                return app_error(AppError::$OrderCanNotRefund, '退款金额不能为0元');
            }
            if ($price > $order['refundprice']) {
                return app_error(AppError::$OrderCanNotRefund, '退款金额不能超过' . $order['refundprice'] . '元');
            }
        }

        $images = $_GPC['images'];
        if (is_string($images)){
            $images = htmlspecialchars_decode(str_replace('\\','', $images));
            $images = @json_decode( $images  ,true);
        }

        $refund = array(
            'uniacid' => $uniacid,
            'merchid' => $order['merchid'],
            'applyprice' => $price,
            'rtype' => $rtype,
            'reason' => trim($_GPC['reason']),
            'content' => trim($_GPC['content']),
            'imgs' => iserializer($images)
        );

        if ($refund['rtype'] == 2) {
            $refundstate = 2;
        } else {
            $refundstate = 1;
        }
        if ($order['refundstate'] == 0) {
            //新建一条退款申请
            $refund['createtime'] = time();
            $refund['orderid'] = $orderid;
            $refund['orderprice'] = $order['refundprice'];
            $refund['refundno'] = m('common')->createNO('order_refund', 'refundno', 'SR');
            pdo_insert('ewei_shop_order_refund', $refund);
            $refundid = pdo_insertid();
            pdo_update('ewei_shop_order', array('refundid' => $refundid, 'refundstate' => $refundstate), array('id' => $orderid, 'uniacid' => $uniacid));
        } else {
            //修改退款申请
            pdo_update('ewei_shop_order', array('refundstate' => $refundstate), array('id' => $orderid, 'uniacid' => $uniacid));
            pdo_update('ewei_shop_order_refund', $refund, array('id' => $refundid, 'uniacid' => $uniacid));
        }
        //模板消息
        m('notice')->sendOrderMessage($orderid, true);
        return app_json();
    }

    //取消
    function cancel() {

        global $_W, $_GPC;
        extract($this->globalData());
        $change_refund = array();
        $change_refund['status'] = -2;
        $change_refund['refundtime'] = time();
        pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $refundid, 'uniacid' => $uniacid));
        pdo_update('ewei_shop_order', array('refundstate' => 0), array('id' => $orderid, 'uniacid' => $uniacid));
        return app_json();
    }

    //填写快递单号
    function express() {

        global $_W, $_GPC;
        extract($this->globalData());
        if (empty($refundid)) {
            return app_error(AppError::$ParamsError, '参数错误');
        }
        if (empty($_GPC['expresssn'])) {
            return app_error(AppError::$ParamsError, '请填写快递单号');
        }
        $refund = array(
            'status'=>4,
            'express'=>trim($_GPC['express']),
            'expresscom'=>trim($_GPC['expresscom']),
            'expresssn'=>trim($_GPC['expresssn']),
            'sendtime'=>time()
        );
        pdo_update('ewei_shop_order_refund', $refund, array('id' => $refundid, 'uniacid' => $uniacid));
        return app_json();
    }

    //收到换货商品
    function receive(){

        global $_W, $_GPC;
        extract($this->globalData());
        $refundid = intval($_GPC['refundid']);
        $refund =  pdo_fetch("select * from " . tablename('ewei_shop_order_refund') . ' where id=:id and uniacid=:uniacid and orderid=:orderid limit 1'
            , array(':id' => $refundid, ':uniacid' => $uniacid, ':orderid' => $orderid));
        if (empty($refund)) {
            return app_error(AppError::$OrderNotFound, '换货申请未找到');
        }

        $time = time();
        $refund_data = array();
        $refund_data['status'] = 1;
        $refund_data['refundtime'] = $time;
        pdo_update('ewei_shop_order_refund', $refund_data, array('id'=>$refundid, 'uniacid' => $uniacid));

        $order_data = array();
        $order_data['refundstate'] = 0;
        $order_data['status'] = -1;
        $order_data['refundtime'] = $time;
        pdo_update('ewei_shop_order', $order_data, array('id'=>$orderid, 'uniacid' => $uniacid));

        return app_json();
    }

    //查询商家重新发货快递
    function refundexpress() {

        global $_W, $_GPC;
        extract($this->globalData());

        $express = trim($_GPC['express']);
        $expresssn = trim($_GPC['expresssn']);
        $expresscom = trim($_GPC['expresscom']);
        $expresslist = m('util')->getExpressList($express, $expresssn);

        return app_json(array('list'=>$expresslist));
    }
}
