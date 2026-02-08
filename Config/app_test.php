<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //'sign' => array('name' => 'sign', 'require' => true),
    ),
    'REDIS_HOST' => "192.168.0.10",
    'REDIS_AUTH' => "",
    'REDIS_PORT' => "6379",
    
    'sign_key' => '76576076c1f5f657b634e966c8836a06',
		
	'uptype'=>3,//上传方式：1表示 七牛，2表示 本地，3表示 华为
		/**
     * 七牛相关配置
     */
    'Qiniu' =>  array(
        //ak
        'accessKey' => 'wuUaS1drln6IZmWBqUu-sRexdKcPYLYLTIIHiFWl',
        //sk
        'secretKey' => '6dEiWU4zMrferxKQDB1Azhs_lW9OXHZBIi7pvFI_',
        //存储空间
        'space_bucket' => 'liveyb-test',
        //cdn加速域名 格式：http(s)://a.com
        'space_host' => 'http://img.zbitcloud.com',
        //区域上传域名(服务端)  参考文档：https://developer.qiniu.com/kodo/manual/1671/region-endpoint
        'uphost' => 'http://up-z2.qiniup.com', 
    ),
    /**
     * 华为相关配置
     */
    'Huawei' =>  array(
        //ak
        'accessKey' => 'GIKDSLUAANLI3TAB8WCX',
        //sk
        'secretKey' => '3TYTLrcnRO9yvhlNKSbECmQv9y1B9Qx6CQltzbL6',
        //endPoint
        'endPoint' => 'http://obs.cn-south-1.myhuaweicloud.com',
        //桶
        'bucket' => 'elive-test',
        //访问域名
        'domain' => 'http://img.zbitcloud.com',
    ),
		 /**
     * 本地上传
     */
    'UCloudEngine' => 'local',

    /**
     * 本地存储相关配置（UCloudEngine为local时的配置）
     */
    'UCloud' => array(
        //对应的文件路径  站点域名
        'host' => 'http://live.zbitcLoud.com/upload'
    ),
		
		/**
     * 云上传引擎,支持local,oss,upyun
     */
    //'UCloudEngine' => 'oss',

    /**
     * 云上传对应引擎相关配置
     * 如果UCloudEngine不为local,则需要按以下配置
     */
   /*  'UCloud' => array(
        //上传的API地址,不带http://,以下api为阿里云OSS杭州节点
        'api' => 'oss-cn-hangzhou.aliyuncs.com',

        //统一的key
        'accessKey' => '',
        'secretKey' => '',

        //自定义配置的空间
        'bucket' => '',
        'host' => 'http://image.xxx.com', //必带http:// 末尾不带/

        'timeout' => 90
    ), */


    'chatUrl' => '192.168.0.211',
    'chatPort' => 9511,
    'chatSocketSecretKey' => 'f7s8v8bnm9ad54c5badda7d6304r0higfuad',
);
