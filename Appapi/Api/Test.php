<?php
/**
 * 测试
 */
class Api_Test extends PhalApi_Api {
    /**
     * 测试接口服务器是否正常
     * @desc 用于用户举报
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
    public function test()
    {
        return;
    }
//	public function test()
//    {
//        global $socketSecretKey;
//        $domain = new Domain_User();
//        $user = $domain->getUser(1);
//        sendDataToChatServer([
//            'secretKey' => $socketSecretKey,
//            'type' => 'setManagerNotice',
//            'msg' => [
//                'liveuid' => 1,
//                'touid' => 1,
//                'tousername' => $user['user_nicename'] ?? '',
//            ],
//        ]);
//    }

//    public function test()
//    {
//        global $socketSecretKey;
//        sendDataToChatServer([
//            'secretKey' => $socketSecretKey,
//            'type' => 'adminEndLive',
//            'msg' => [
//                'liveuid' => 1,
//            ]
//        ]);
//    }
}
