<?php
/*珍贵资源 请勿转卖*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class QuickModel extends PluginModel
{

    public function getAdv() {

    }

    public function update($data, $page = array()) {
        global $_W;

        if(empty($data)){
            return $data;
        }
        if(!is_array($data)){
            $data = json_decode($data, true);
        }
        if(!is_array($data) || !is_array($data['datas']) || empty($data['datas'])){
            return $data;
        }

        $goodsids = array();
        $cateids = array();
        $groupids = array();
        // 遍历获取多有的商品id、分类id、分组id
        foreach ($data['datas'] as $index=>$item){
            if($item['datatype']==0){
                $item_ids = !empty($item['goodsids'])?$item['goodsids']:array();
                if(empty($item_ids) && count($item['data'])>0){
                    $data['datas'][$index]['goodsids'] = $item_ids = $this->getGids($item['data']);
                }
                if(!empty($item_ids)){
                    $goodsids = array_merge($goodsids, $item_ids);
                    $goodsids = array_unique($goodsids);
                }
            }
            elseif($item['datatype']==1){
                if($item['cateid'] && !in_array($item['cateid'], $cateids)){
                    $cateids[] = $item['cateid'];
                }
            }
            elseif($item['datatype']==2){
                if($item['groupid'] && !in_array($item['groupid'], $groupids)){
                    $groupids[] = $item['groupid'];
                }
            }
        }

        // 后台处理： 读取商品信息、分类信息、分组信息 并赋值

        // 读取商品数据
        $goodsids = array_filter($goodsids);
        if(!empty($goodsids)){
            $goodsids = implode(",", $goodsids);
            $allGoods = pdo_fetchall("SELECT id, title, subtitle, minprice, total, sales FROM". tablename("ewei_shop_goods")." WHERE uniacid=:uniacid AND id in({$goodsids}) AND `deleted`=0 AND `status`=1", array(":uniacid"=>$_W['uniacid']), "id");
        }
        // 读取分类数据
        $cateids = array_filter($cateids);
        if(!empty($cateids)){
            $cateids = implode(",", $cateids);
            $allCates = pdo_fetchall("SELECT * FROM". tablename("ewei_shop_category")." WHERE uniacid=:uniacid AND id in({$cateids}) AND enabled=1", array(':uniacid'=>$_W['uniacid']), "id");
        }
        // 读取分组数据
        $groupids = array_filter($groupids);
        if(!empty($groupids)){
            $groupids = implode(",", $groupids);
            $allGroups = pdo_fetchall("SELECT * FROM". tablename("ewei_shop_goods_group")." WHERE uniacid=:uniacid AND id in({$groupids}) AND enabled=1", array(':uniacid'=>$_W['uniacid']), "id");
        }
        foreach ($data['datas'] as $index=>&$item){
            if($item['datatype']==0){
                if(!empty($item['data'])){
                    foreach ($item['data'] as $i=>$g){
                        $gid = $g['gid'];
                        if(empty($allGoods[$gid])){
                            unset($data['datas'][$index]['data'][$i]);
                        }
                        $item['data'][$i]['title'] = $allGoods[$gid]['title'];
                        $item['data'][$i]['subtitle'] = $allGoods[$gid]['subtitle'];
                        $item['data'][$i]['price'] = $allGoods[$gid]['minprice'];
                        $item['data'][$i]['total'] = $allGoods[$gid]['total'];
                    }
                }
            }
            elseif($item['datatype']==1){
                $cateid = $item['cateid'];
                $item['catename'] = !empty($allCates[$cateid])?$allCates[$cateid]['name']:$item['catename'];
            }
            elseif($item['datatype']==2){
                $groupid = $item['groupid'];
                $item['groupname'] = !empty($allGroups[$groupid])?$allGroups[$groupid]['name']:$item['groupname'];
            }
        }
        unset($item);

        if(!empty($page)) {
            $data['title'] = $page['title'];
        }

        return json_encode($data);
    }

    public function mobile($data, $merchid=0){
        global $_W;

        if(empty($data)){
            return $data;
        }
        if(!is_array($data)){
            $data = json_decode($data, true);
        }
        if(!is_array($data) || !is_array($data['datas']) || empty($data['datas'])){
            return $data;
        }
        // 手机端处理数据
        foreach ($data['datas'] as $index=>&$item){
            if($item['datatype']==0){
                unset($item['data'], $item['cateid'], $item['catename'], $item['groupid'], $item['groupname']);
                if(!empty($item['goodsids'])){
                    $item['goodsids'] = implode(",", $item['goodsids']);
                }
            }
            elseif($item['datatype']==1){
                unset($item['data'], $item['goodsids'], $item['catename'], $item['groupid'], $item['groupname']);
            }
            elseif($item['datatype']==2){
                unset($item['data'], $item['goodsids'], $item['cateid'], $item['catename'], $item['groupname']);
            }
            $item['page'] = 1;
            $item['num'] = 0;
        }
        unset($item);

        $template = $data['template'];

        $returnData = array(
            'template'=>$template,
            'style'=>$data['style'][$template],
        );
        if($data['template']==0){
            $returnData['cartdata'] = intval($data['cartdata']);
            $returnData['datas'] = json_encode($data['datas']);
            if ($data['showadv']==1){
                if($merchid>0){
                    $returnData['advs'] = pdo_fetchall("SELECT * FROM".tablename("ewei_shop_quick_adv")."WHERE uniacid=:uniacid AND merchid=:merchid AND enabled=1", array(":uniacid"=>$_W['uniacid'], ":merchid"=>$merchid));
                }else{
                    $returnData['advs'] = pdo_fetchall("SELECT * FROM".tablename("ewei_shop_quick_adv")."WHERE uniacid=:uniacid AND enabled=1", array(":uniacid"=>$_W['uniacid']));
                }
            }
            elseif ($data['showadv']==2 && !empty($data['advs'])){
                $returnData['advs'] = array();
                foreach ($data['advs'] as $advitem){
                    $returnData['advs'][] = array('link'=>$advitem['linkurl'], 'thumb'=>$advitem['imgurl']);
                }
                unset($advitem);
            }
        }else{
            $newDatas = array();
            if(!empty($data['datas'])){
                foreach ($data['datas'] as $index=>$d){
                    $orderby = '';
                    if($d['datatype']==0 || $d['datatype']==2){
                        if($d['goodssort']==1){
                            $orderby = " sales desc, displayorder desc";
                        }
                        else if($d['goodssort']==2){
                            $orderby = " minprice desc, displayorder desc";
                        }
                        else if($d['goodssort']==3){
                            $orderby = " minprice asc, displayorder desc";
                        }
                    }

                    if($d['datatype']==2 && !empty($d['groupid'])){
                        $group = pdo_fetch('select * from ' . tablename('ewei_shop_goods_group') . ' where id=:id and uniacid=:uniacid and enabled=1 limit 1 ', array(':id'=>$d['groupid'], ':uniacid' => $_W['uniacid']));
                        if(!empty($group) && !empty($group['goodsids'])){
                            $d['goodsids'] = $group['goodsids'];
                        }
                    }

                    if ($d['datatype']==1 && !empty($d['cateid'])){
                        $pagesize = !empty($d['goodsnum'])?$d['goodsnum']:5;
                        $goodslist = $this->getList(array('cate'=>$d['cateid'], 'order'=>$orderby, 'pagesize'=>$pagesize, 'page'=>1));
                        $d['data'] = $goodslist['list'];
                    }
                    elseif (!empty($d['goodsids'])){
                        $goodslist = $this->getList(array('ids'=>$d['goodsids'], 'order'=>$orderby));
                        $d['data']= $goodslist['list'];
                        if($d['datatype']==0){
                            $d['data'] = $this->sort($d['goodsids'], $d['data']);
                        }
                    }
                    $newDatas[] = $d;
                }
            }

            $returnData['datas'] = $newDatas;
            if($returnData['style']['notice']==1){
                $limit = !empty($returnData['style']['noticenum'])?$returnData['style']['noticenum']:5;
                if($merchid>0){
                    $returnData['notices'] = pdo_fetchall("SELECT id, title FROM".tablename("ewei_shop_merch_notice")."WHERE uniacid=:uniacid AND status=1 AND merchid=:merchid LIMIT ".$limit, array(":uniacid"=>$_W['uniacid'], ":merchid"=>$merchid));
                }else{
                    $returnData['notices'] = pdo_fetchall("SELECT id, title FROM".tablename("ewei_shop_notice")."WHERE uniacid=:uniacid AND status=1 AND iswxapp=0 LIMIT ".$limit, array(":uniacid"=>$_W['uniacid']));
                }
            }
            elseif($returnData['style']['notice']==2 && !empty($data['notices'])){
                $returnData['notices'] = $data['notices'];
            }
            $returnData['shopmenu'] = $data['shopmenu'];
            $returnData['diymenu'] = $data['diymenu'];
        }

        //print_r($returnData);exit();

        return $returnData;
    }

    public function getQuick($id) {
        global $_W;

        if(empty($id)){
            return array();
        }
        return pdo_fetch("SELECT * FROM".tablename("ewei_shop_quick")."WHERE id=:id AND uniacid=:uniacid AND status=1 LIMIT 1", array(":id"=>$id, ":uniacid"=>$_W['uniacid']));
    }

    public function getPageList($merch=false, $type=0, $condition = '', $params = array()) {
        global $_W;

        $condition .= " uniacid=:uniacid ";
        $params[':uniacid'] = $_W['uniacid'];
        $condition .= " AND type=:type";
        if(!empty($merch)){
            $condition .= " AND merchid=:merchid ";
            $params[":merchid"] = $merch;
        }
        $params[":type"] = $type;

        return pdo_fetchall("SELECT id, title, status FROM". tablename("ewei_shop_quick")."WHERE ".$condition." ORDER BY createtime DESC", $params);
    }

    public function getList($args){
        global $_W;

        $page = !empty($args['page']) ? intval($args['page']) : 1;
        $merchid = !empty($args['merchid']) ? intval($args['merchid']) : 0;
        $pagesize = !empty($args['pagesize']) ? intval($args['pagesize']) : 10;
        $displayorder = 'displayorder';
        $order = !empty($args['order']) ? $args['order'] : ' ' . $displayorder . ' desc,createtime desc';
        $orderby = empty($args['order']) ? '' : (!empty($args['by']) ? $args['by'] : '' );
        //多商户
        $merch_plugin = p('merch');
        $merch_data = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        // 过滤状态
        $condition = ' and `uniacid` = :uniacid AND `deleted` = 0 and status=1 and bargain=0 and `type`<>4 and `type`<>9 ';
        $params = array(':uniacid' => $_W['uniacid']);

        if (!empty($merchid)) {
            $condition.=" and merchid=:merchid and checked=0 ";
            $params[':merchid'] = $merchid;
        } else {
            if ($is_openmerch == 0) {
                //未开启多商户的情况下,只读取平台商品
                $condition .= ' and `merchid` = 0';
            } else {
                //开启多商户的情况下,过滤掉未通过审核的商品
                $condition .= ' and `checked` = 0';
            }
        }
        //指定ID
        $ids = !empty($args['ids']) ? trim($args['ids']) : '';
        if (!empty($ids)) {
            $condition.=" and id in ( " . $ids . ")";
        }
        //分类
        if(!empty($args['cate'])){
            $category = m('shop')->getAllCategory();
            $catearr = array($args['cate']);
            foreach ($category as $index => $row) {
                if ($row['parentid'] == $args['cate']) {
                    $catearr[] = $row['id'];
                    foreach ($category as $ind => $ro) {
                        if ($ro['parentid'] == $row['id']) {
                            $catearr[] = $ro['id'];
                        }
                    }
                }
            }
            $catearr = array_unique($catearr);
            $condition .= " AND ( ";
            foreach ($catearr as $key=>$value){
                if ($key==0) {
                    $condition .= "FIND_IN_SET({$value},cates)";
                }else{
                    $condition .= " || FIND_IN_SET({$value},cates)";
                }
            }
            $condition .= " <>0 )";
        }
        // 会员权限
        $member =m('member')->getMember($_W['openid']);
        if (!empty($member)) {
            $levelid = intval($member['level']);
            $groupid = intval($member['groupid']);
            $condition.=" and ( ifnull(showlevels,'')='' or FIND_IN_SET( {$levelid},showlevels)<>0 ) ";
            $condition.=" and ( ifnull(showgroups,'')='' or FIND_IN_SET( {$groupid},showgroups)<>0 ) ";
        } else {
            $condition.=" and ifnull(showlevels,'')='' ";
            $condition.=" and   ifnull(showgroups,'')='' ";
        }

        $sql = "SELECT id,title,subtitle,thumb,minprice,marketprice,sales,salesreal,total,bargain,`type`,ispresell,presellend,preselltimeend,hasoption,total,maxbuy,minbuy,usermaxbuy,isverify,cannotrefund,diyformtype,diyformid,showsales,showtotal FROM " . tablename('ewei_shop_goods') . " where 1 {$condition} ORDER BY {$order} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
        $total = pdo_fetchcolumn("select count(*) from " . tablename('ewei_shop_goods') . " where 1 {$condition} ",$params);

        $list = pdo_fetchall($sql, $params);
        $list = set_medias($list, 'thumb');

        if(!empty($list) && is_array($list)){
            foreach ($list as $i=>&$g){
                // 过滤 预售、砍价、过滤不能加购物车
                $g['sales'] = $g['sales']+$g['salesreal'];

                $totalmaxbuy = $g['total'];
                // 单次购买量
                if ($g['maxbuy'] > 0) {
                    if ($totalmaxbuy != -1) {
                        if ($totalmaxbuy > $g['maxbuy']) {
                            $totalmaxbuy = $g['maxbuy'];
                        }
                    } else {
                        $totalmaxbuy = $g['maxbuy'];
                    }
                }
                //总购买量
                if ($g['usermaxbuy'] > 0) {
                    $order_goodscount = pdo_fetchcolumn('select ifnull(sum(og.total),0)  from ' . tablename('ewei_shop_order_goods') . ' og ' . ' left join ' . tablename('ewei_shop_order') . ' o on og.orderid=o.id '
                        . ' where og.goodsid=:goodsid and  o.status>=1 and o.openid=:openid  and og.uniacid=:uniacid ', array(':goodsid' => $g['id'], ':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));
                    $last = $g['usermaxbuy'] - $order_goodscount;
                    if ($last <= 0) {
                        $last = 0;
                    }
                    if ($totalmaxbuy != -1) {
                        if ($totalmaxbuy > $last) {
                            $totalmaxbuy = $last;
                        }
                    } else {
                        $totalmaxbuy = $last;
                    }
                }
                //最小购买
                if ($g['minbuy'] > 0) {
                    if ($g['minbuy'] > $totalmaxbuy) {
                        $g['minbuy'] = $totalmaxbuy;
                    }
                }
                $g['totalmaxbuy'] = $totalmaxbuy;

                if(empty($totalmaxbuy)&& $g['total']!='0'){
                 $g['cannotbuy'] ="超出最高购买数量";

                }else if(empty($totalmaxbuy)&& $g['total']=='0'){

                 $g['cannotbuy'] ="该商品已售罄";
                }else{
                 $g['cannotbuy']='';
                };



                $g['unit'] = empty($g['unit']) ? '件' : $g['unit'];
                $g['num'] = 0;

//               $g['total']=$total;
//               $g['cannotbuy'] = empty($total) ?'该商品已售罄':'';

                // 开启预售则到详情页购买
                if($g['ispresell']>0 && (($g['presellend'] > 0 && $g['preselltimeend'] > time()) || ($g['preselltimeend'] == 0))){
                    $g['gotodetail'] = 1;
                    $g['presell'] = 1;
                }

                // 判断砍价商品跳转
                if (p('bargain') && !empty($g['bargain']) && empty($g['gotodetail'])) {
                    $bargain = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_bargain_goods') . " WHERE id = :id AND unix_timestamp(start_time)<" . time() . " AND unix_timestamp(end_time)>" . time() . " AND status = 0", array(':id' => $g['bargain']));
                    if($bargain){
                        $g['gotodetail'] = 1;
                        $g['isbargain'] = 1;
                    }
                }

                //是否可以加入购物车
                $g['canAddCart'] = true;

                // 过滤不能加入购物车：核销、虚拟商品、卡密、充值卡、不能退换货、记次时
                if ($g['isverify'] == 2 || $g['type'] == 2 || $g['type'] == 3 || $g['type'] == 20  || $g['type'] == 5) {
                    $g['canAddCart'] = false;
                }
                // 删除不用字段
                unset($g['ispresell'], $g['bargain'], $g['isverify'], $g['cannotrefund'], $g['salesreal'], $g['type'], $g['presellend'], $g['preselltimeend'], $g['bargain']);
            }
            unset($g);
        }

        return array('list'=>$list, 'total'=>$total);
    }

    public function getGids($arr){
        $ids = array();
        if(empty($arr) || !is_array($arr)){
            return $ids;
        }
        foreach ($arr as $index=>$item){
            if(empty($item['gid']) || in_array($item['gid'], $ids)){
                continue;
            }
            $ids[] = $item['gid'];
        }
        return $ids;
    }

    public function sort($ids, $list) {
        if(empty($ids) || empty($list)){
            return array();
        }
        if(!is_array($ids)){
            $ids = explode(",", $ids);
            if(!is_array($ids) || empty($ids)){
                return $list;
            }
        }
        $newArr = array();
        foreach ($ids as $k=>$v){
            foreach ($list as $i=>$g){
                if($v==$g['id']){
                    $newArr[] = $g;
                }
            }
        }
        return $newArr;
    }

    public function getCart($pageid, $json=true) {
        global $_W;

        $uniacid = $_W['uniacid'];
        $openid =$_W['openid'];
        $list = array();

        $condition = ' and f.uniacid= :uniacid and f.openid=:openid and f.deleted=0';
        $params = array(':uniacid' => $uniacid, ':openid' => $openid);
        $total = 0;
        $totalprice = 0;
        $ischeckall = true;
        //会员级别

        $tablename = empty($pageid)?"ewei_shop_member_cart":"ewei_shop_quick_cart";
        if(!empty($pageid)){
            $condition .= " and quickid=:quickid";
            $params[':quickid'] = $pageid;
        }
        $level = m('member')->getLevel($openid);
        $sql = 'SELECT f.id,f.total,f.goodsid,g.total as stock,g.preselltimeend,g.presellprice as gpprice,g.hasoption, o.stock as optionstock,g.presellprice,g.ispresell, g.maxbuy,g.title,g.thumb,ifnull(o.marketprice, g.marketprice) as marketprice,'
            . ' g.productprice,o.title as optiontitle,o.presellprice,f.optionid,o.specs,g.minbuy,g.maxbuy,g.unit,f.merchid,g.checked,g.isdiscount,g.isdiscount_discounts,g.isdiscount_time,g.isnodiscount,g.discounts,g.merchsale'
            . ' ,f.selected FROM ' . tablename($tablename) . ' f '
            . ' left join ' . tablename('ewei_shop_goods') . ' g on f.goodsid = g.id '
            . ' left join ' . tablename('ewei_shop_goods_option') . ' o on f.optionid = o.id '
            . ' where 1 ' . $condition . ' ORDER BY `id` DESC ';
        $list = pdo_fetchall($sql, $params);

        foreach ($list as &$g) {
            if($g['ispresell']>0 && ($g['preselltimeend'] == 0 || $g['preselltimeend'] > time())){
                $g['marketprice'] = intval($g['hasoption'])>0 ? $g['presellprice'] : $g['gpprice'];
            }
            $g['thumb'] = tomedia($g['thumb']);
            $seckillinfo = plugin_run('seckill::getSeckill',$g['goodsid'] ,$g['optionid'] ,true, $_W['openid']);
            if (!empty($g['optionid'])) {
                $g['stock'] = $g['optionstock'];
                //读取规格的图片
                if (!empty($g['specs'])) {
                    $thumb = m('goods')->getSpecThumb($g['specs']);
                    if (!empty($thumb)) {
                        $g['thumb'] =tomedia( $thumb );
                    }
                }
            }
            if($g['selected']){
                //促销或会员折扣
                $prices = m('order')->getGoodsDiscountPrice($g, $level, 1);
                $total+=$g['total'];
                $g['marketprice'] = $g['ggprice'] = $prices['price'];
                if( $seckillinfo && $seckillinfo['status']==0){
                    $seckilllast = 0;
                    if( $seckillinfo['maxbuy']>0) {
                        $seckilllast = $seckillinfo['maxbuy'] - $seckillinfo['selfcount'];
                    }
                    $normal = $g['total'] - $seckilllast;
                    if($normal<=0){
                        $normal =  0;
                    }
                    $totalprice+= $seckillinfo['price'] * $seckilllast  +  $g['marketprice'] * $normal;
                    $g['seckillmaxbuy'] = $seckillinfo['maxbuy'];
                    $g['seckillselfcount'] = $seckillinfo['selfcount'];
                    $g['seckillprice'] = $seckillinfo['price'];
                    $g['seckilltag'] = $seckillinfo['tag'];
                    $g['seckilllast'] = $seckilllast;
                } else{
                    $totalprice+=$g['marketprice'] * $g['total'];
                }
            }
            //库存
            $totalmaxbuy = $g['stock'];
            if( $seckillinfo && $seckillinfo['status']==0){
                if( $totalmaxbuy > $g['seckilllast']){
                    $totalmaxbuy = $g['seckilllast'];
                }
                if($g['total']>$totalmaxbuy){
                    $g['total'] = $totalmaxbuy;
                }
                $g['minbuy'] = 0;
            } else {
                //最大购买量
                if ($g['maxbuy'] > 0) {
                    if ($totalmaxbuy != -1) {
                        if ($totalmaxbuy > $g['maxbuy']) {
                            $totalmaxbuy = $g['maxbuy'];
                        }
                    } else {
                        $totalmaxbuy = $g['maxbuy'];
                    }
                }
                //总购买量
                if ($g['usermaxbuy'] > 0) {
                    $order_goodscount = pdo_fetchcolumn('select ifnull(sum(og.total),0)  from ' . tablename('ewei_shop_order_goods') . ' og '
                        . ' left join ' . tablename('ewei_shop_order') . ' o on og.orderid=o.id '
                        . ' where og.goodsid=:goodsid and  o.status>=1 and o.openid=:openid  and og.uniacid=:uniacid ', array(':goodsid' => $g['goodsid'], ':uniacid' => $uniacid, ':openid' => $openid));
                    $last = $g['usermaxbuy'] - $order_goodscount;
                    if ($last <= 0) {
                        $last = 0;
                    }
                    if ($totalmaxbuy != -1) {
                        if ($totalmaxbuy > $last) {
                            $totalmaxbuy = $last;
                        }
                    } else {
                        $totalmaxbuy = $last;
                    }
                }
                //最小购买
                if ($g['minbuy'] > 0) {
                    if ($g['minbuy'] > $totalmaxbuy) {
                        $g['minbuy'] = $totalmaxbuy;
                    }
                }
            }
            $g['totalmaxbuy'] = $totalmaxbuy;
            $g['unit'] = empty($g['unit']) ? '件' : $g['unit'];
            if(empty($g['selected'])){
                $ischeckall =false;
            }

            $g['total'] = intval($g['total']);
            $g['totalmaxbuy'] = intval($g['totalmaxbuy']);

            if($g['total']==$g['totalmaxbuy']){
                $g['dismax'] = 1;
            }
            if($g['total']==$g['minbuy']){
                $g['dismin'] = 1;
            }

            unset($g['checked'], $g['discounts'], $g['isdiscount'], $g['isdiscount_discounts'], $g['isdiscount_time'], $g['isnodiscount'], $g['selected'], $g['thumb']);
        }
        unset($g);
        $list = set_medias($list, 'thumb');
        /*
        $merch_user = array();
        $merch = array();
        $merch_plugin = p('merch');
        $merch_data = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $getListUser = $merch_plugin->getListUser($list);
            $merch_user = $getListUser['merch_user'];
            $merch = $getListUser['merch'];
        }*/

        $result = array(
            'list'=>$list ,
            'total'=>$total,
            'totalprice'=>round($totalprice,2),
        );

        if($json){
            return json_encode($result);
        }

        return $result;
    }
}
