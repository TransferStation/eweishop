<?php
/*珍贵资源 请勿转卖*/
if(!defined('IN_IA')) {
    exit('Access Denied');
}

return array(
    'version'=>'1.0',
    'id'=>'bargain',
    'name'=>'砍价活动',
    'v3'=>true,
    'menu'=>array(
        'title'=>'页面',
        'plugincom'=>1,
        'icon'=>'page',
        'items'=>array(
            array(
                'title'=>'砍价商品',
                'items'=>array(
                    array(
                        'title'=>'砍价中',
                        'route'=>''
                    ),
                     array(
                         'title'=>'已售罄',
                         'route'=>'soldout'
                     ),

                    array(
                        'title'=>'未开始',
                        'route'=>'notstart'
                    ),
                    array(
                        'title'=>'已结束',
                        'route'=>'complete'
                    ),
                    array(
                        'title'=>'砍价失败',
                        'route'=>'fails'
                    ),
                    array(
                        'title'=>'已下架',
                        'route'=>'out'
                    ),
                    array(
                        'title'=>'回收站',
                        'route'=>'recycle'
                    ),
                    array(
                        'title'=>'添加',
                        'route'=>'warehouse',
                        'extend'=> 'bargain.act'
                    )
                ),
                'extend'=> 'bargain.react'
            ),
            array(
                'title'=>'商品订单',
                'items'=>array(
                    array(
                        'title'=>'待发货',
                        'route'=>'record.daifahuo'
                    ),
                    array(
                        'title'=>'待收货',
                        'route'=>'record.daishouhuo'
                    ),
                    array(
                        'title'=>'待付款',
                        'route'=>'record.daifukuan'
                    ),
                    array(
                        'title'=>'已关闭',
                        'route'=>'record.yiguanbi'
                    ),
                    array(
                        'title'=>'已完成',
                        'route'=>'record.yiwancheng'
                    ),
                    array(
                        'title'=>'全部订单',
                        'route'=>'record'
                    )
                )
            ),

            array(
                'title'=>'全局设置',
                'items'=>array(
                    array(
                        'title'=>'分享设置',
                        'route'=>'set'
                    ),
                    array(
                        'title'=>'消息通知',
                        'route'=>'messageset'
                    ),
                    array(
                        'title'=>'其他设置',
                        'route'=>'otherset'
                    )
                )
            ),

        )
    )
);