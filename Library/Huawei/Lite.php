<?php
/**
 * 华为接口调用
 * 1、图片文件上传
 *
 * 参考：https://support.huaweicloud.com/sdk-php-devg-obs/obs_28_0114.html
 *
 *
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

use Obs\ObsClient;

class Huawei_Lite {

    protected $config;

    /**
     * @param string $config['accessKey']  统一的key
     * @param string $config['secretKey']
     * @param string $config['space_bucket']  自定义配置的空间
     * @param string $config['space_host']  
     */
    public function __construct($config = NULL) {
        $this->config = $config;

        if ($this->config === NULL) {
            $this->config = DI()->config->get('app.Huawei');
        }
    }

    /**
     * 文件上传
     * @param string $filePath 待上传文件的绝对路径
     * @return string 上传成功后的URL，失败时返回空
     */
    public function uploadFile($filePath,$type)
    {

        $fileUrl = '';

        if (!file_exists($filePath)) {
            return $fileUrl;
        }
        $config = $this->config;
        $fileName = "image/".$type."/".date('Ymd', $_SERVER['REQUEST_TIME'])."/".
            md5(PhalApi_Tool::createRandStr(8) . microtime(true));
        $obsClient = new ObsClient([
            'key' => $config['accessKey'],
            'secret' => $config['secretKey'],
            'endpoint' => $config['endPoint']
        ]);
        $resp = $obsClient->putObject([
            'Bucket' => $config['bucket'],
            'Key' => $fileName,
            'SourceFile' => $filePath  // localfile为待上传的本地文件路径，需要指定到具体的文件名
        ]);
        $fileUrl =  $fileName;
        return $fileUrl;

    }
    

}
