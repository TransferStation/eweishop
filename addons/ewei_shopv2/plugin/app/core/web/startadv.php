<?php 
if( !defined("IN_IA") ) 
{
    exit( "Access Denied" );
}


class Startadv_EweiShopV2Page extends PluginWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $pindex = max(1, intval($_GPC["page"]));
        $psize = 20;
        $condition = " uniacid=:uniacid ";
        $params = array( ":uniacid" => $_W["uniacid"] );
        $keyword = trim($_GPC["keyword"]);
        if( !empty($keyword) ) 
        {
            $condition .= " AND name LIKE :keyword ";
            $params[":keyword"] = "%" . $keyword . "%";
        }

        $limit = " LIMIT " . ($pindex - 1) * $psize . "," . $psize;
        $list = pdo_fetchall("SELECT id, `name`, createtime, lastedittime, status FROM " . tablename("ewei_shop_wxapp_startadv") . " WHERE " . $condition . " ORDER BY id DESC" . $limit, $params);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_wxapp_startadv") . " WHERE " . $condition, $params);
        $pager = pagination2($total, $pindex, $psize);
        include($this->template());
    }

    public function add()
    {
        $this->post();
    }

    public function edit()
    {
        $this->post();
    }

    protected function post()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC["id"]);
        if( !empty($id) ) 
        {
            $advs = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_wxapp_startadv") . " WHERE id=:id and uniacid=:uniacid limit 1 ", array( ":id" => $id, ":uniacid" => $_W["uniacid"] ));
            if( !empty($advs) ) 
            {
                $advs["data"] = base64_decode($advs["data"]);
                $advs["data"] = json_decode($advs["data"], true);
                $advs["data"]["status"] = intval($advs["status"]);
            }

        }

        if( $_W["ispost"] ) 
        {
            $data = $_GPC["advs"];
            $advsdata = array( "name" => $data["name"], "status" => intval($data["status"]), "data" => base64_encode(json_encode($data)), "lastedittime" => time() );
            if( !empty($id) ) 
            {
                plog("app.startadv.edit", "?????????????????? id: " . $id . "  ??????:" . $advsdata["name"]);
                pdo_update("ewei_shop_wxapp_startadv", $advsdata, array( "id" => $id, "uniacid" => $_W["uniacid"] ));
            }
            else
            {
                plog("app.startadv.add", "?????????????????? id: " . $id . "  ??????:" . $advsdata["name"]);
                $advsdata["uniacid"] = $_W["uniacid"];
                $advsdata["createtime"] = time();
                pdo_insert("ewei_shop_wxapp_startadv", $advsdata);
                $id = pdo_insertid();
            }

            show_json(1, array( "id" => $id ));
        }

        include($this->template());
    }

    /**
     * ??????/??????????????????
     */

    public function status()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC["id"]);
        if( empty($id) ) 
        {
            $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
        }

        $status = intval($_GPC["status"]);
        $list = pdo_fetchall("SELECT id, `name` FROM " . tablename("ewei_shop_wxapp_startadv") . " WHERE id in( " . $id . " ) AND uniacid=:uniacid", array( ":uniacid" => $_W["uniacid"] ));
        if( !empty($list) ) 
        {
            foreach( $list as $row ) 
            {
                pdo_update("ewei_shop_wxapp_startadv", array( "status" => $status ), array( "id" => $row["id"], "uniacid" => $_W["uniacid"] ));
                plog("app.startadv.edit", ("?????????????????? ID: " . $row["id"] . " ????????????: " . $row["name"] . " ????????????: " . $status == 1 ? "??????" : "??????"));
            }
        }

        show_json(1);
    }

    /**
     * ??????/????????????
     */

    public function delete()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC["id"]);
        if( empty($id) ) 
        {
            $id = (is_array($_GPC["ids"]) ? implode(",", $_GPC["ids"]) : 0);
        }

        $list = pdo_fetchall("SELECT id, `name` FROM " . tablename("ewei_shop_wxapp_startadv") . " WHERE id in( " . $id . " ) AND uniacid=:uniacid", array( ":uniacid" => $_W["uniacid"] ));
        if( !empty($list) ) 
        {
            foreach( $list as $row ) 
            {
                pdo_delete("ewei_shop_wxapp_startadv", array( "id" => $row["id"], "uniacid" => $_W["uniacid"] ));
                plog("app.startadv.delete", "???????????? ID: " . $row["id"] . " ????????????: " . $row["name"] . " ");
            }
        }

        show_json(1, array( "url" => referer() ));
    }

}
?>