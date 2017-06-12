<?php
/**
 * Created by PhpStorm.
 * User: lilhorse
 * Date: 2017/6/12
 * Time: 下午4:29
 */
return [
    //个推配置
    'getui' => [
        'app_id' => env('GT_APP_ID'),
        'app_key' => env('GT_APP_KEY'),
        'app_secret' => env('GT_APP_SECRET'),
        'master_secret' => env('GT_MASTER_SECRET'),
        'is_offline' => env('GT_IS_OFFLINE', true),
        //消息离线存储有效期，单位分钟，默认12小时（720分钟）
        'offline_expire_time' => env('GT_OFFLINE_EXPIRE_TIME', 720),
    ],

    'xiaomi' => [
        'app_id' => env('XM_APP_ID'),
        'app_key' => env('XM_APP_KEY'),
        'app_secret' => env('XM_APP_SECRET'),
        'app_package' => env('XM_APP_PACKAGE'),
    ],
];