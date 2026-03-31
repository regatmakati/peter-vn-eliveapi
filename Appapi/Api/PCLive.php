<?php

/**
 * PC端直播模块
 */
class Api_PCLive extends PhalApi_Api
{

    public function getRules()
    {
        return array(
            'getAuth' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),
            'getClassMatch' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveclassid' => array('name' => 'liveclassid', 'type' => 'int', 'default' => '0', 'desc' => '分类ID'),
            ),
            'createRoom' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'title' => array('name' => 'title', 'type' => 'string', 'require' => true, 'desc' => '直播标题'),
                'liveclassid' => array('name' => 'liveclassid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '直播分类ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名 getAuth接口获取的stream，取？号前面的字符串'),
                'contract' => array('name' => 'contract', 'type' => 'array', 'desc' => '主播联系方式(非必填)，contract[status]：显示状态0（默认）隐藏全部，1显示全部 ，2显示按钮，contract[contract]：联系QQ'),
                'notice' => array('name' => 'notice', 'type' => 'string', 'desc' => '直播公告'),
                'match_id' => array('name' => 'match_id', 'type' => 'int', 'desc' => '关联的赛事ID'),
                'file' => array('name' => 'file', 'type' => 'file', 'min' => 0, 'max' => 1024 * 1024 * 2, 'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png')),
            ),
            'updateRoom' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名 getAuth接口获取的stream，取？号前面的字符串'),
                'contract' => array('name' => 'contract', 'type' => 'array', 'desc' => '主播联系方式(非必填)，contract[status]：显示状态0（默认）隐藏全部，1显示全部 ，2显示按钮，contract[contract]：联系QQ'),
                'title' => array('name' => 'title', 'type' => 'string', 'desc' => '直播标题'),
                'liveclassid' => array('name' => 'liveclassid', 'type' => 'int', 'min' => 1, 'desc' => '直播分类ID'),
                'notice' => array('name' => 'notice', 'type' => 'string', 'desc' => '直播公告'),
                'match_id' => array('name' => 'match_id', 'type' => 'int', 'desc' => '关联的赛事ID'),
                'file' => array('name' => 'file', 'type' => 'file', 'min' => 0, 'max' => 1024 * 1024 * 2, 'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png')),
            ),
            'getSDK' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
            ),
            'changeLive' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
                'status' => array('name' => 'status', 'type' => 'int', 'require' => true, 'desc' => '直播状态 0关闭 1直播'),
            ),
            'changeLiveType' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
                'type' => array('name' => 'type', 'type' => 'int', 'default' => '0', 'desc' => '直播类型，0是一般直播，1是私密直播，2是收费直播，3是计时直播'),
                'type_val' => array('name' => 'type_val', 'type' => 'string', 'default' => '', 'desc' => '类型值'),
            ),
            'stopRoom' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
            ),

            'stopInfo' => array(
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
            ),

            'checkLive' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
            ),

            'roomCharge' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
            ),
            'timeCharge' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
            ),

            'enterRoom' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
                'city' => array('name' => 'city', 'type' => 'string', 'default' => '', 'desc' => '城市'),
            ),
            'leaveRoom' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
            ),

            'showVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '上麦会员ID'),
                'pull_url' => array('name' => 'pull_url', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '连麦用户播流地址'),
            ),

            'getZombie' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '流名'),
            ),

            'getUserLists' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '页数'),
            ),

            'getPop' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
            ),

            'getGiftList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'sendGift' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
                'giftid' => array('name' => 'giftid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物ID'),
                'giftcount' => array('name' => 'giftcount', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物数量'),
                'ispack' => array('name' => 'ispack', 'type' => 'int', 'default' => '0', 'desc' => '是否背包'),
                'is_sticker' => array('name' => 'is_sticker', 'type' => 'int', 'default' => '0', 'desc' => '是否为贴纸礼物：0：否；1：是'),
            ),

            'sendBarrage' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
                'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '弹幕内容'),
            ),

            'setAdmin' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
            ),

            'getAdminList' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
            ),

            'setReport' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
                'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '举报内容'),
            ),

            'getVotes' => array(
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
            ),

            'setShutUp' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '禁言用户ID'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'default' => '0', 'desc' => '禁言类型,0永久，1本场'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'default' => '0', 'desc' => '流名，0永久'),
            ),

            'kicking' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
            ),

            'superStopRoom' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'default' => 0, 'desc' => '关播类型 0表示关闭当前直播 1表示禁播，2表示封禁账号'),
            ),
            'searchMusic' => array(
                'key' => array('name' => 'key', 'type' => 'string', 'require' => true, 'desc' => '关键词'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '页数'),
            ),

            'getDownurl' => array(
                'audio_id' => array('name' => 'audio_id', 'type' => 'int', 'require' => true, 'desc' => '歌曲ID'),
            ),

            'getCoin' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),

            'checkLiveing' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '会员ID'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'require' => false, 'desc' => '其它主播ID'),
//                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'min' => 1, 'desc' => '会员token'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'desc' => '流名'),
            ),

            'getLiveInfo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => '会员token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '主播ID'),
            ),

            'setLiveGoodsIsShow' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
                'goodsid' => array('name' => 'goodsid', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '商品ID'),

            ),



            'getLiveByUid' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
            ),

            'getLiveByMatchId' => array(
                'match_id' => array('name' => 'match_id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '比赛ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'min' => 2, 'require' => true, 'desc' => '比赛类型，2=篮球，4=足球'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),
        );
    }

    /**
     * 获取直播用户是否授权和播流地址
     * @desc 获取直播用户是否授权和播流地址
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].auth 是否授权直播，1已授权 2未授权
     * @return string info[0].cdn  PC端推流地址
     * @return string info[0].stream PC流名
     * @return string msg 提示信息
     */

    public function getAuth()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);

        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_PCLive();
        $key = 'getAuth_' . $uid;
        $info = getcaches($key);
        if (!$info) {
            $info = $domain->getAuth($uid);
            setcaches($key, $info, 60);
        }

        //已授权返回直播地址
        $cdn = '';
        $stream = '';
        if ($info == 1) {
            $configpri = getConfigPri();
            /*设置stream*/
            $stream = $uid . "_" . time();

            /* 推流 */
            if ($configpri['cdn_switch'] == 5) {
                $wyinfo = PrivateKeyA('http', $stream, 1);
                $push = $wyinfo['ret']['pushUrl'];
//            $wy_cid=$wyinfo['ret']['cid'];
            } else {
                $push = PrivateKeyA('rtmp', $stream, 1);
            }

            $pushArr = explode('live/', $push);
            $cdn = $pushArr[0] . 'live';
            $stream = $pushArr[1];
            setcaches('stream_' . $uid, $stream, 60 * 60 * 24);
        }

        $auth[] = array('auth' => $info, 'cdn' => $cdn, 'stream' => $stream);
        $rs['info'] = $auth;
        return $rs;
    }

    /**
     * 获取直播用户是否授权和播流地址
     * @desc 获取直播用户是否授权和播流地址
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].id  直播分类ID
     * @return string info[0].name  直播分类名称
     * @return string info[1].league_id  联赛ID
     * @return string info[1].league_cat 联赛分类
     * @return string info[1].league_id 游戏分类ID
     * @return string info[1].match_id 联赛ID
     * @return string info[1].league_name 游戏分类名称
     * @return string info[1].match_name 联赛名称
     * @return string info[1].logo_a A战队LOGO
     * @return string info[1].logo_b B战队LOGO
     * @return string info[1].team_name_a A战队名称
     * @return string info[1].team_name_b B战队名称
     * @return string info[1].team_a_score 主队得分
     * @return string info[1].team_b_score 客队得分
     * @return string info[1].status 比赛中,未开始
     * @return string info[1].viewnum 观看次数
     * @return string info[1].starttime 开始时间
     * @return string info[1].startdate 开始时间
     * @return string msg 提示信息
     */
    public function getClassMatch()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);

        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_PCLive();
        $key = 'liveClass';
        $info = getcaches($key);
        if (!$info) {
            $info = $domain->getClass();
            setcaches($key, $info, 60 * 5);
        }

        $liveclassid = $this->liveclassid;
        $class_arr = [];
        $match_arr = [];
        if (!empty($info) && $liveclassid) {
            foreach ($info as $k => $v) {
                $class_arr[] = $v['id'];
            }
            if (in_array($liveclassid, $class_arr)) {
                $m_key = 'match_' . $liveclassid;
                $match_arr = getcaches($m_key);
                if (!$match_arr) {
                    $match_arr = $domain->getMatch($liveclassid);
                    setcaches($m_key, $match_arr, 60 * 5);
                }
            }
        }

        $list[] = $info;
        $list[] = $match_arr;
        $rs['info'] = $list;
        return $rs;
    }

    /**
     * 直播设置和赛事关联
     * @desc 直播设置和赛事关联
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].chatserver  聊天服务地址
     * @return string info[0].stream  流名
     * @return string info[0].user_nicename  用户昵称
     * @return string info[0].avatar_thumb  用户头像
     * @return string info[0].pull  拉流地址
     * @return string info[0].thumb  直播图片
     * @return string msg 提示信息
     */

    public function createRoom()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $token = checkNull($this->token);
        $title = checkNull($this->title);
        $notice = checkNull($this->notice);
        $liveclassid = checkNull($this->liveclassid);
        $stream = checkNull($this->stream);

        $checkToken = checkToken($uid, $token);

        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $stream_cache = getcaches('stream_' . $uid);
        if (!$stream_cache || $stream_cache != $stream) {
            $rs['code'] = 1004;
            $rs['msg'] = '播流地址错误或者失效';
            return $rs;
        }

        $domain = new Domain_PCLive();
        $islive = $domain->getAuth($uid);
        if ($islive != 1) {
            $rs['code'] = 1003;
            $rs['msg'] = '暂未授权，不能进行直播！';
            return $rs;
        }

        $configpub = getConfigPub();
        if ($configpub['maintain_switch'] == 1) {
            $rs['code'] = 1002;
            $rs['msg'] = $configpub['maintain_tips'];
            return $rs;

        }
        $isban = isBan($uid);
        if (!$isban) {
            $rs['code'] = 1001;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $domain = new Domain_Live();
        $result = $domain->checkBan($uid);
        if ($result) {
            $rs['code'] = 1015;
            $rs['msg'] = '已被禁播';
            return $rs;
        }
        $configpri = getConfigPri();
        $pull_domain = $configpri['tx_pull'];

        $userinfo = getUserInfo($uid);


        $stream_b = explode('?', $stream);
        $stream = $stream_b[0];
        $stream_a = explode("_", $stream);
        $showid = $stream_a[1];

        $liang = getUserLiang($uid);
        $goodnum = 0;
        if ($liang['name'] != 0) {
            $goodnum = $liang['name'];
        }

        if (checkSensitiveWords($title)) {
            $rs['code'] = 1002;
            $rs['msg'] = '直播标题不能包含违禁词';
            return $rs;
        }

        if (checkSensitiveWords($notice)) {
            $rs['code'] = 1002;
            $rs['msg'] = '直播公告不能包含违禁词';
            return $rs;
        }
//        $sensitivewords = sensitiveField($title);
//        if ($sensitivewords == 1001) {
//            $rs['code'] = 10011;
//            $rs['msg'] = '输入非法，请重新输入';
//            return $rs;
//        }

        $pull = PrivateKeyA('rtmp', $stream, 0);

        $city = '好像在火星';

        $match_id = 0;
        if (checkNull($this->match_id)) {
            $match_id = $this->match_id;
        }

        $thumb = '';
        if ($_FILES) {
            if ($_FILES["file"]["error"] > 0) {
                $rs['code'] = 1003;
                $rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
                DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']);
                return $rs;
            }

            if (!checkExt($_FILES["file"]['name'])) {
                $rs['code'] = 1004;
                $rs['msg'] = '图片仅能上传 jpg,png,jpeg';
                return $rs;
            }

            $uptype = DI()->config->get('app.uptype');
            if ($uptype == 1) {
                //七牛
                $url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name']);

                if (!empty($url)) {
                    $thumb = $url . '?imageView2/2/w/600/h/600'; //600 X 600
                }
            } else if ($uptype == 2) {
                //本地上传
                //设置上传路径 设置方法参考3.2
                DI()->ucloud->set('save_path', 'thumb/' . date("Ymd"));

                //新增修改文件名设置上传的文件名称
                // DI()->ucloud->set('file_name', $this->uid);

                //上传表单名
                $res = DI()->ucloud->upfile($_FILES['file']);

                $files = '../upload/' . $res['file'];
                $PhalApi_Image = new Image_Lite();
                //打开图片
                $PhalApi_Image->open($files);
                /**
                 * 可以支持其他类型的缩略图生成，设置包括下列常量或者对应的数字：
                 * IMAGE_THUMB_SCALING      //常量，标识缩略图等比例缩放类型
                 * IMAGE_THUMB_FILLED       //常量，标识缩略图缩放后填充类型
                 * IMAGE_THUMB_CENTER       //常量，标识缩略图居中裁剪类型
                 * IMAGE_THUMB_NORTHWEST    //常量，标识缩略图左上角裁剪类型
                 * IMAGE_THUMB_SOUTHEAST    //常量，标识缩略图右下角裁剪类型
                 * IMAGE_THUMB_FIXED        //常量，标识缩略图固定尺寸缩放类型
                 */
                $PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
                $PhalApi_Image->save($files);

                $thumb = $res['url'];
            } else if ($uptype == 3) {
                //华为
                $url = DI()->huawei->uploadFile($_FILES['file']['tmp_name'], 'live');

                if (!empty($url)) {
                    $thumb = $url . '?imageView2/2/w/600/h/600'; //600 X 600
                }
            }

            @unlink($_FILES['file']['tmp_name']);
        }

        $dataroom = array(
            "uid" => $uid,
            "showid" => $showid,
            "starttime" => time(),
            "title" => $title,
            "city" => $city,
            "stream" => $stream,
            "thumb" => $thumb,
            "pull" => $pull,
            "goodnum" => $goodnum,
            "isvideo" => 0,
            "islive" => 1,
            "liveclassid" => $liveclassid,
            "hotvotes" => 0,
            "pkuid" => 0,
            "pkstream" => '',
            "banker_coin" => 10000000,
            "notice" => $notice,
            "match_id" => $match_id
        );

        $domain = new Domain_Live();
        $result = $domain->createRoom($uid, $dataroom);
        if ($result === false) {
            $rs['code'] = 1011;
            $rs['msg'] = '开播失败，请重试';
            return $rs;
        }

//        /* 主播VIP */
//        $vip=getUserVip($uid);
//        $info['vip']=$vip;

        $data = array('city' => $city);
        $domain2 = new Domain_User();
        $info2 = $domain2->userUpdate($uid, $data);

        $userinfo['city'] = $city;
        $userinfo['usertype'] = 50;
        $userinfo['sign'] = '0';

        DI()->redis->set($token, json_encode($userinfo));

        $votestotal = $domain->getVotes($uid);

        $info['userlist_time'] = $configpri['userlist_time'];
        $info['barrage_fee'] = $configpri['barrage_fee'];
        $info['chatserver'] = $configpri['chatserver'];

        $info['votestotal'] = $votestotal;
        $info['stream'] = $stream;
        $info['uid'] = $uid;
        $info['user_nicename'] = $userinfo['user_nicename'];
        $info['avatar_thumb'] = $userinfo['avatar_thumb'];
//        $info['push']=$push;

        $info['pull'] = "{$pull_domain}/live/{$stream}.flv";
        $info['pull_h5'] = "{$pull_domain}/live/{$stream}.m3u8";

        //返回直播封面
        if ($thumb) {
            $info['thumb'] = get_upload_path($thumb);
        } else {
            $info['thumb'] = $userinfo['avatar_thumb'];
        }
        $userinfo = getUserInfo($uid, 1);

        $score = '001';
        $ip = getRealIp();

        DI()->redis->zAdd('user_' . $stream, $score, $ip);
        DI()->redis->zAdd('userpc_' . $stream, $score, $ip);
		DI()->redis  -> set("{$uid}_is_chat_off", 2);
		
        $rs['info'][0] = $info;

        /* 清除连麦PK信息 */
//        DI()->redis  -> hset('LiveConnect',$uid,0);
//        DI()->redis  -> hset('LivePK',$uid,0);
//        DI()->redis  -> hset('LivePK_gift',$uid,0);

        /* 后台禁用后再启用，恢复发言 */
//        DI()->redis -> hDel($uid . 'shutup',$uid);
        //删除聊天室聊天记录
        DI()->redis -> del("live{$uid}");

        //增加主播联系方式
        if (!empty($_REQUEST['contract'])) (new Domain_Contract())->createOrUpdate($uid, $_REQUEST['contract']);

        return $rs;
    }

    /**
     * 获取SDK
     * @desc 用于获取SDK类型
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].live_sdk SDK类型，0金山SDK 1腾讯SDK
     * @return object info[0].android 安卓CDN配置
     * @return object info[0].ios IOS CDN配置
     * @return string info[0].isshop 是否有店铺，0否1是
     * @return string msg 提示信息
     */
    public function getSDK()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid = checkNull($this->uid);
        $configpri = getConfigPri();

        //$info['live_sdk']=$configpri['live_sdk'];

        $cdnset = include API_ROOT . '/../data/config/cdnset.php';

        $cdnset['live_sdk'] = $configpri['live_sdk'];

        /* 店铺信息 */
        $isshop = checkShopIsPass($uid);

        $cdnset['isshop'] = $isshop;
        $rs['info'][0] = $cdnset;


        return $rs;
    }

    /**
     * 修改直播状态
     * @desc 用于主播修改直播状态
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].msg 成功提示信息
     * @return string msg 提示信息
     */
    public function changeLive()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);
        $stream = checkNull($this->stream);
        $status = $this->status;

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_Live();
        $info = $domain->changeLive($uid, $stream, $status);

        $configpri = getConfigPri();
        /* 极光推送 */
        $app_key = $configpri['jpush_key'];
        $master_secret = $configpri['jpush_secret'];

        if ($app_key && $master_secret && $status == 1 && $info) {
            require API_ROOT . '/../sdk/JPush/autoload.php';
            // 初始化
            $client = new \JPush\Client($app_key, $master_secret, null);

            $userinfo = getUserInfo($uid);

            $anthorinfo = array(
                "uid" => $info['uid'],
                "avatar" => $info['avatar'],
                "avatar_thumb" => $info['avatar_thumb'],
                "user_nicename" => $info['user_nicename'],
                "title" => $info['title'],
                "city" => $info['city'],
                "stream" => $info['stream'],
                "pull" => $info['pull'],
                "thumb" => $info['thumb'],
                "isvideo" => '0',
                "type" => $info['type'],
                "type_val" => $info['type_val'],
                "game_action" => '0',
                "goodnum" => $info['goodnum'],
                "anyway" => $info['anyway'],
                "nums" => 0,
                "level_anchor" => $userinfo['level_anchor'],
                "game" => '',
            );
            $title = '你的好友：' . $anthorinfo['user_nicename'] . '正在直播，邀请你一起';
            $apns_production = false;
            if ($configpri['jpush_sandbox']) {
                $apns_production = true;
            }

            $pushids = $domain->getFansIds($uid);
            $nums = count($pushids);
            for ($i = 0; $i < $nums;) {
                $alias = array_slice($pushids, $i, 900);
                $i += 900;
                try {
                    $result = $client->push()
                        ->setPlatform('all')
                        ->addRegistrationId($alias)
                        ->setNotificationAlert($title)
                        ->iosNotification($title, array(
                            'sound' => 'sound.caf',
                            'category' => 'jiguang',
                            'extras' => array(
                                'type' => '1',
                                'userinfo' => $anthorinfo
                            ),
                        ))
                        ->androidNotification('', array(
                            'extras' => array(
                                'title' => $title,
                                'type' => '1',
                                'userinfo' => $anthorinfo
                            ),
                        ))
                        ->options(array(
                            'sendno' => 100,
                            'time_to_live' => 0,
                            'apns_production' => $apns_production,
                        ))
                        ->send();
                } catch (Exception $e) {
                    file_put_contents('./jpush.txt', date('y-m-d h:i:s') . '提交参数信息 设备名:' . json_encode($alias) . "\r\n", FILE_APPEND);
                    file_put_contents('./jpush.txt', date('y-m-d h:i:s') . '提交参数信息:' . $e . "\r\n", FILE_APPEND);
                }
            }
        }
        /* 极光推送 */

        $rs['info'][0]['msg'] = '成功';
        return $rs;
    }

    /**
     * 修改直播类型
     * @desc 用于主播修改直播类型
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].msg 成功提示信息
     * @return string msg 提示信息
     */
    public function changeLiveType()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);
        $stream = checkNull($this->stream);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $type = checkNull($this->type);
        $type_val = checkNull($this->type_val);

        if ($type == 1 && $type_val == '') {
            $rs['code'] = 1002;
            $rs['msg'] = '密码不能为空';
            return $rs;
        } else if ($type > 1 && $type_val <= 0) {
            $rs['code'] = 1002;
            $rs['msg'] = '价格不能小于等于0';
            return $rs;
        }


        $data = array(
            "type" => $type,
            "type_val" => $type_val,
        );

        $domain = new Domain_Live();
        $info = $domain->changeLiveType($uid, $stream, $data);

        $rs['info'][0]['msg'] = '成功';
        return $rs;
    }


    /**
     * 修改直播信息
     * @desc 修改直播信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].chatserver  聊天服务地址
     * @return string info[0].stream  流名
     * @return string info[0].user_nicename  用户昵称
     * @return string info[0].avatar_thumb  用户头像
     * @return string info[0].pull  拉流地址
     * @return string info[0].thumb  直播图片
     * @return string msg 提示信息
     */

    public function updateRoom()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
        $token = checkNull($this->token);
        $title = checkNull($this->title);
        $notice = checkNull($this->notice);
        $liveclassid = checkNull($this->liveclassid);
        $stream = checkNull($this->stream);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $sensitivewords = sensitiveField($title);
        if ($sensitivewords == 1001) {
            $rs['code'] = 10011;
            $rs['msg'] = '输入非法，请重新输入';
            return $rs;
        }
        if (!empty($title)) {
            $data['title'] = $title;
        }
        if (!empty($liveclassid)) {
            $data['liveclassid'] = $liveclassid;
        }
        if (!empty($notice)) {
            $data['notice'] = $notice;
        }
        $match_id = 0;
        if (checkNull($this->match_id)) {
            $match_id = $this->match_id;
        }
        if (!empty($match_id)) {
            $data['match_id'] = $match_id;
        }
        $thumb = '';
        if ($_FILES) {
            if ($_FILES["file"]["error"] > 0) {
                $rs['code'] = 1003;
                $rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
                DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']);
                return $rs;
            }

            if (!checkExt($_FILES["file"]['name'])) {
                $rs['code'] = 1004;
                $rs['msg'] = '图片仅能上传 jpg,png,jpeg';
                return $rs;
            }

            $uptype = DI()->config->get('app.uptype');
            if ($uptype == 1) {
                //七牛
                $url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name']);

                if (!empty($url)) {
                    $thumb = $url . '?imageView2/2/w/600/h/600'; //600 X 600
                }
            } else if ($uptype == 2) {
                //本地上传
                //设置上传路径 设置方法参考3.2
                DI()->ucloud->set('save_path', 'thumb/' . date("Ymd"));

                //新增修改文件名设置上传的文件名称
                // DI()->ucloud->set('file_name', $this->uid);

                //上传表单名
                $res = DI()->ucloud->upfile($_FILES['file']);

                $files = '../upload/' . $res['file'];
                $PhalApi_Image = new Image_Lite();
                //打开图片
                $PhalApi_Image->open($files);
                /**
                 * 可以支持其他类型的缩略图生成，设置包括下列常量或者对应的数字：
                 * IMAGE_THUMB_SCALING      //常量，标识缩略图等比例缩放类型
                 * IMAGE_THUMB_FILLED       //常量，标识缩略图缩放后填充类型
                 * IMAGE_THUMB_CENTER       //常量，标识缩略图居中裁剪类型
                 * IMAGE_THUMB_NORTHWEST    //常量，标识缩略图左上角裁剪类型
                 * IMAGE_THUMB_SOUTHEAST    //常量，标识缩略图右下角裁剪类型
                 * IMAGE_THUMB_FIXED        //常量，标识缩略图固定尺寸缩放类型
                 */
                $PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
                $PhalApi_Image->save($files);

                $thumb = $res['url'];
            } else if ($uptype == 3) {
                //华为
                $url = DI()->huawei->uploadFile($_FILES['file']['tmp_name'], 'live');

                if (!empty($url)) {
                    $thumb = $url . '?imageView2/2/w/600/h/600'; //600 X 600
                }
            }

            @unlink($_FILES['file']['tmp_name']);
        }
        if (!empty($thumb)) {
            $data['thumb'] = $thumb;
        }
        $city = '好像在火星';
        $configpri = getConfigPri();
        $pull = PrivateKeyA('rtmp', $stream, 0);

        $domain = new Domain_Live();
        $result = $domain->updateRoom($uid, $stream, $data);
        if ($result === false) {
            $rs['code'] = 1011;
            $rs['msg'] = '修改失败，请重试';
            return $rs;
        }
        $data = array('city' => $city);
        $domain2 = new Domain_User();
        $info2 = $domain2->userUpdate($uid, $data);
        $userinfo = getUserInfo($uid);

        $userinfo['city'] = $city;
        $userinfo['usertype'] = 50;
        $userinfo['sign'] = '0';

        DI()->redis->set($token, json_encode($userinfo));

        $votestotal = $domain->getVotes($uid);

        $info['userlist_time'] = $configpri['userlist_time'];
        $info['barrage_fee'] = $configpri['barrage_fee'];
        $info['chatserver'] = $configpri['chatserver'];

        $info['votestotal'] = $votestotal;
        $info['stream'] = $stream;
        $info['uid'] = $uid;
        $info['user_nicename'] = $userinfo['user_nicename'];
        $info['avatar_thumb'] = $userinfo['avatar_thumb'];
//        $info['push']=$push;
        $info['pull'] = $pull;


        //返回直播封面
        if ($thumb) {
            $info['thumb'] = get_upload_path($thumb);
        } else {
            $info['thumb'] = $userinfo['avatar_thumb'];
        }

        $rs['info'][0] = $info;

        /* 清除连麦PK信息 */
//        DI()->redis  -> hset('LiveConnect',$uid,0);
//        DI()->redis  -> hset('LivePK',$uid,0);
//        DI()->redis  -> hset('LivePK_gift',$uid,0);

        /* 后台禁用后再启用，恢复发言 */
//        DI()->redis -> hDel($uid . 'shutup',$uid);

        //增加主播联系方式
        if (!empty($_REQUEST['contract'])) (new Domain_Contract())->createOrUpdate($uid, $_REQUEST['contract']);

        return $rs;
        $rs['info'][0]['msg'] = '成功';
        return $rs;

    }


    /**
     * 关闭直播
     * @desc 用于用户结束直播
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].msg 成功提示信息
     * @return string msg 提示信息
     */
    public function stopRoom()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        file_put_contents(API_ROOT . '/Runtime/stopRoom_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 开始:' . "\r\n", FILE_APPEND);
        file_put_contents(API_ROOT . '/Runtime/stopRoom_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 _REQUEST:' . json_encode($_REQUEST) . "\r\n", FILE_APPEND);
        $uid = $this->uid;
        $token = checkNull($this->token);
        $stream = checkNull($this->stream);

        $key = 'stopRoom_' . $stream;
        $isexist = getcaches($key);
        file_put_contents(API_ROOT . '/Runtime/stopRoom_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 isexist:' . json_encode($isexist) . "\r\n", FILE_APPEND);
        if (!$isexist) {
            $domain = new Domain_Live();

            $checkToken = checkToken($uid, $token);
            file_put_contents(API_ROOT . '/Runtime/stopRoom_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 checkToken:' . json_encode($checkToken) . "\r\n", FILE_APPEND);
            setcaches($key, '1', 10);
            if ($checkToken == 700) {
                $domain->stopRoom($uid, $stream);
                $rs['code'] = $checkToken;
                $rs['msg'] = '您的登陆状态失效，请重新登陆！';
                return $rs;
            }
            $info = $domain->stopRoom($uid, $stream);
        }

        $rs['info'][0]['msg'] = '关播成功';
        file_put_contents(API_ROOT . '/Runtime/stopRoom_' . date('Y-m-d') . '.txt', date('Y-m-d H:i:s') . ' 提交参数信息 结束:' . "\r\n", FILE_APPEND);

        return $rs;
    }

    /**
     * 直播结束信息
     * @desc 用于直播结束页面信息展示
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].nums 人数
     * @return string info[0].length 时长
     * @return string info[0].votes 映票数
     * @return string msg 提示信息
     */
    public function stopInfo()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $stream = checkNull($this->stream);

        $domain = new Domain_Live();
        $info = $domain->stopInfo($stream);

        $rs['info'][0] = $info;
        return $rs;
    }

    /**
     * 检查直播
     * @desc 用于用户进房间时检查直播
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].type 房间类型
     * @return string info[0].type_val 收费房间价格，默认0
     * @return string info[0].type_msg 提示信息
     * @return string msg 提示信息
     */
    public function checkLive()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);
        $liveuid = $this->liveuid;
        $stream = checkNull($this->stream);

        $configpub = getConfigPub();
        if ($configpub['maintain_switch'] == 1) {
            $rs['code'] = 1002;
            $rs['msg'] = $configpub['maintain_tips'];
            return $rs;

        }

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }


        $isban = isBan($uid);
        if (!$isban) {
            $rs['code'] = 1001;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }


        if ($uid == $liveuid) {
            $rs['code'] = 1011;
            $rs['msg'] = '不能进入自己的直播间';
            return $rs;
        }


        $domain = new Domain_Live();
        $info = $domain->checkLive($uid, $liveuid, $stream);

        if ($info == 1005) {
            $rs['code'] = 1005;
            $rs['msg'] = '直播已结束';
            return $rs;
        } else if ($info == 1007) {
            $rs['code'] = 1007;
            $rs['msg'] = '超管不能进入1v1房间';
            return $rs;
        } else if ($info == 1008) {
            $rs['code'] = 1004;
            $rs['msg'] = '您已被踢出房间';
            return $rs;
        }


        $configpri = getConfigPri();

        $info['live_sdk'] = $configpri['live_sdk'];

        $rs['info'][0] = $info;


        return $rs;
    }



    /**
     * 查询主播的直播间
     * @desc
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].type 房间类型
     * @return string info[0].type_val 收费房间价格，默认0
     * @return string info[0].type_msg 提示信息
     * @return string msg 提示信息
     */
    public function getLiveByUid()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $domain = new Domain_Live();
        $info = $domain->getLiveByUid($uid);
        if($info){
            $rs['info'] = $info;
        }

        return $rs;
    }



    public function getLiveByMatchId()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $match_id = $this->match_id;
        $type = $this->type;
        $domain = new Domain_Live();
        $info = $domain->getLiveByMatchId($match_id,$type);
        if($info){
            $rs['info'][0] = $info;
        }

        return $rs;
    }

    /**
     * 房间扣费
     * @desc 用于房间扣费
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].coin 用户余额
     * @return string msg 提示信息
     */
    public function roomCharge()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);
        $liveuid = $this->liveuid;
        $stream = checkNull($this->stream);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_Live();
        $info = $domain->roomCharge($uid, $liveuid, $stream);

        if ($info == 1005) {
            $rs['code'] = 1005;
            $rs['msg'] = '直播已结束';
            return $rs;
        } else if ($info == 1006) {
            $rs['code'] = 1006;
            $rs['msg'] = '该房间非扣费房间';
            return $rs;
        } else if ($info == 1007) {
            $rs['code'] = 1007;
            $rs['msg'] = '房间费用有误';
            return $rs;
        } else if ($info == 1008) {
            $rs['code'] = 1008;
            $rs['msg'] = '余额不足';
            return $rs;
        }
        $rs['info'][0]['coin'] = $info['coin'];

        return $rs;
    }

    /**
     * 房间计时扣费
     * @desc 用于房间计时扣费
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].coin 用户余额
     * @return string msg 提示信息
     */
    public function timeCharge()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);
        $liveuid = $this->liveuid;
        $stream = checkNull($this->stream);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_Live();

        $key = 'timeCharge_' . $stream . '_' . $uid;
        $cache = getcaches($key);
        if ($cache) {
            $coin = $domain->getUserCoin($uid);
            $rs['info'][0]['coin'] = $coin['coin'];
            return $rs;
        }


        $info = $domain->roomCharge($uid, $liveuid, $stream);

        if ($info == 1005) {
            $rs['code'] = 1005;
            $rs['msg'] = '直播已结束';
            return $rs;
        } else if ($info == 1006) {
            $rs['code'] = 1006;
            $rs['msg'] = '该房间非扣费房间';
            return $rs;
        } else if ($info == 1007) {
            $rs['code'] = 1007;
            $rs['msg'] = '房间费用有误';
            return $rs;
        } else if ($info == 1008) {
            $rs['code'] = 1008;
            $rs['msg'] = '余额不足';
            return $rs;
        }
        $rs['info'][0]['coin'] = $info['coin'];

        setcaches($key, 1, 50);

        return $rs;
    }


    /**
     * 进入直播间
     * @desc 用于用户进入直播
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].votestotal 直播映票
     * @return string info[0].barrage_fee 弹幕价格
     * @return string info[0].userlist_time 用户列表获取间隔
     * @return string info[0].chatserver socket地址
     * @return string info[0].isattention 是否关注主播，0表示未关注，1表示已关注
     * @return string info[0].nums 房间人数
     * @return string info[0].pull_url 播流地址
     * @return string info[0].linkmic_uid 连麦用户ID，0表示未连麦
     * @return string info[0].linkmic_pull 连麦播流地址
     * @return array info[0].userlists 用户列表
     * @return array info[0].game 押注信息
     * @return array info[0].gamebet 当前用户押注信息
     * @return string info[0].gametime 游戏剩余时间
     * @return string info[0].gameid 游戏记录ID
     * @return string info[0].gameaction 游戏类型，1表示炸金花，2表示牛牛，3表示转盘
     * @return string info[0].game_bankerid 庄家ID
     * @return string info[0].game_banker_name 庄家昵称
     * @return string info[0].game_banker_avatar 庄家头像
     * @return string info[0].game_banker_coin 庄家余额
     * @return string info[0].game_banker_limit 上庄限额
     * @return object info[0].vip 用户VIP信息
     * @return string info[0].vip.type VIP类型，0表示无VIP，1表示普通VIP，2表示至尊VIP
     * @return object info[0].liang 用户靓号信息
     * @return string info[0].liang.name 号码，0表示无靓号
     * @return object info[0].guard 守护信息
     * @return string info[0].guard.type 守护类型，0表示非守护，1表示月守护，2表示年守护
     * @return string info[0].guard.endtime 到期时间
     * @return string info[0].guard_nums 主播守护数量
     * @return object info[0].pkinfo 主播连麦/PK信息
     * @return string info[0].pkinfo.pkuid 连麦用户ID
     * @return string info[0].pkinfo.pkpull 连麦用户播流地址
     * @return string info[0].pkinfo.ifpk 是否PK
     * @return string info[0].pkinfo.pk_time 剩余PK时间（秒）
     * @return string info[0].pkinfo.pk_gift_liveuid 主播PK总额
     * @return string info[0].pkinfo.pk_gift_pkuid 连麦主播PK总额
     * @return string info[0].isred 是否显示红包
     * @return string info[0].show_goods 直播间在售商品展示
     * @return string info[0].show_goods['goodsid'] 直播间展示的在售商品ID
     * @return string info[0].show_goods['goods_name'] 直播间展示的在售商品名称
     * @return string info[0].show_goods['goods_thumb'] 直播间展示的在售商品封面
     * @return string info[0].show_goods['goods_price'] 直播间展示的在售商品价格
     * @return string info[0].show_goods['goods_type'] 直播间展示的在售商品 商品类型 0 站内商品 1 站外商品
     * @return string msg 提示信息
     */
    public function enterRoom()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid = $this->uid;
        $token = checkNull($this->token);
        $liveuid = $this->liveuid;
        $city = checkNull($this->city);
        $stream = checkNull($this->stream);
        if ($uid) {
            $checkToken = checkToken($uid, $token);
            if ($checkToken == 700) {
                $rs['code'] = $checkToken;
                $rs['msg'] = '您的登陆状态失效，请重新登陆！';
                return $rs;
            }


            $isban = isBan($uid);
            if (!$isban) {
                $rs['code'] = 1001;
                $rs['msg'] = '该账号已被禁用';
                return $rs;
            }


            $domain = new Domain_Live();

            $domain->checkShut($uid, $liveuid);
            $userinfo = getUserInfo($uid);

            $carinfo = getUserCar($uid);
            $userinfo['car'] = $carinfo;
            $issuper = '0';
            if ($userinfo['issuper'] == 1) {
                $issuper = '1';
                DI()->redis->hset('super', $userinfo['id'], '1');
            } else {
                DI()->redis->hDel('super', $userinfo['id']);
            }
            if (!$city) {
                $city = '好像在火星';
            }

            $data = array('city' => $city);
            $domain2 = new Domain_User();
            $info = $domain2->userUpdate($uid, $data);
            $userinfo['city'] = $city;

            $usertype = isAdmin($uid, $liveuid);
            $userinfo['usertype'] = $usertype;

            $stream2 = explode('_', $stream);
            $showid = $stream2[1];

            $contribution = '0';
            if ($showid) {
                $contribution = $domain->getContribut($uid, $liveuid, $showid);
            }

            $userinfo['contribution'] = $contribution;


            unset($userinfo['issuper']);

            /* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info = $domain_guard->getUserGuard($uid, $liveuid);

            $guard_nums = $domain_guard->getGuardNums($liveuid);
            $userinfo['guard_type'] = $guard_info['type'];
            /* 等级+100 保证等级位置位数相同，最后拼接1 防止末尾出现0 */
            $userinfo['sign'] = $userinfo['contribution'] . '.' . ($userinfo['level'] + 100) . '1';

            DI()->redis->set($token, json_encode($userinfo));

            /* 用户列表 */
            $userlists = $this->getUserList($liveuid, $stream);

            /* 用户连麦 */
            $linkmic_uid = '0';
            $linkmic_pull = '';
            $showVideo = DI()->redis->hGet('ShowVideo', $liveuid);
            // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 liveuid:'.json_encode($liveuid)."\r\n",FILE_APPEND);
            // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 showVideo:'.json_encode($showVideo)."\r\n",FILE_APPEND);
            if ($showVideo) {
                $showVideo_a = json_decode($showVideo, true);
                $linkmic_uid = $showVideo_a['uid'];
                $linkmic_pull = $this->getPullWithSign($showVideo_a['pull_url']);
            }

            /* 主播连麦 */
            $pkinfo = array(
                'pkuid' => '0',
                'pkpull' => '0',
                'ifpk' => '0',
                'pk_time' => '0',
                'pk_gift_liveuid' => '0',
                'pk_gift_pkuid' => '0',
            );
            $pkuid = DI()->redis->hGet('LiveConnect', $liveuid);
            //file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 进房间:'."\r\n",FILE_APPEND);
            //file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
            //file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 liveuid:'.json_encode($liveuid)."\r\n",FILE_APPEND);
            if ($pkuid) {
                $pkinfo['pkuid'] = $pkuid;
                /* 在连麦 */
                $pkpull = DI()->redis->hGet('LiveConnect_pull', $pkuid);
                $pkinfo['pkpull'] = $this->getPullWithSign($pkpull);
                $ifpk = DI()->redis->hGet('LivePK', $liveuid);
                if ($ifpk) {
                    $pkinfo['ifpk'] = '1';
                    $pk_time = DI()->redis->hGet('LivePK_timer', $liveuid);
                    if (!$pk_time) {
                        $pk_time = DI()->redis->hGet('LivePK_timer', $pkuid);
                    }
                    $nowtime = time();
                    if ($pk_time && $pk_time > 0 && $pk_time < $nowtime) {
                        $cha = 5 * 60 - ($nowtime - $pk_time);
                        $pkinfo['pk_time'] = (string)$cha;

                        $pk_gift_liveuid = DI()->redis->hGet('LivePK_gift', $liveuid);
                        if ($pk_gift_liveuid) {
                            $pkinfo['pk_gift_liveuid'] = (string)$pk_gift_liveuid;
                        }
                        $pk_gift_pkuid = DI()->redis->hGet('LivePK_gift', $pkuid);
                        if ($pk_gift_pkuid) {
                            $pkinfo['pk_gift_pkuid'] = (string)$pk_gift_pkuid;
                        }

                    } else {
                        $pkinfo['ifpk'] = '0';
                    }
                }

            }
            //file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 pkinfo:'.json_encode($pkinfo)."\r\n",FILE_APPEND);
            $configpri = getConfigPri();

            $game = array(
                "brand" => array(),
                "bet" => array('0', '0', '0', '0'),
                "time" => "0",
                "id" => "0",
                "action" => "0",
                "bankerid" => "0",
                "banker_name" => "吕布",
                "banker_avatar" => "",
                "banker_coin" => "0",
            );
            $info = array(
                'votestotal' => $userlists['votestotal'],
                'barrage_fee' => $configpri['barrage_fee'],
                'userlist_time' => $configpri['userlist_time'],
                'chatserver' => $configpri['chatserver'],
                'linkmic_uid' => $linkmic_uid,
                'linkmic_pull' => $linkmic_pull,
                'nums' => $userlists['nums'],
                'game' => $game['brand'],
                'gamebet' => $game['bet'],
                'gametime' => $game['time'],
                'gameid' => $game['id'],
                'gameaction' => $game['action'],
                'game_bankerid' => $game['bankerid'],
                'game_banker_name' => $game['banker_name'],
                'game_banker_avatar' => $game['banker_avatar'],
                'game_banker_coin' => $game['banker_coin'],
                'game_banker_limit' => $configpri['game_banker_limit'],
                'speak_limit' => $configpri['speak_limit'],
                'barrage_limit' => $configpri['barrage_limit'],
                'vip' => $userinfo['vip'],
                'liang' => $userinfo['liang'],
                'issuper' => (string)$issuper,
                'usertype' => (string)$usertype,
                'turntable_switch' => (string)$configpri['turntable_switch'],
                'is_chat_off' => (int)DI()->redis->get("{$liveuid}_is_chat_off"),
				'is_gift_off' => (int)DI()->redis->get("{$liveuid}_is_gift_off"),
				'is_fold_off' => (int)DI()->redis->get("{$liveuid}_is_fold_off"),
            );
            $info['isattention'] = (string)isAttention($uid, $liveuid);
            $info['userlists'] = $userlists['userlist'];

            /* 用户余额 */
            $domain2 = new Domain_User();
            $usercoin = $domain2->getBalance($uid);
            $info['coin'] = $usercoin['coin'];

            /* 守护 */
            $info['guard'] = $guard_info;
            $info['guard_nums'] = $guard_nums;

            /* 主播连麦/PK */
            $info['pkinfo'] = $pkinfo;

            /* 红包 */
            $key = 'red_list_' . $stream;
            $nums = DI()->redis->lLen($key);
            $isred = '0';
            if ($nums > 0) {
                $isred = '1';
            }
            $info['isred'] = $isred;

            /* 奖池 */
            $info['jackpot_level'] = '-1';
            $jackpotset = getJackpotSet();
            if ($jackpotset['switch']) {
                $jackpotinfo = getJackpotInfo();
                $info['jackpot_level'] = $jackpotinfo['level'];
            }
            /** 敏感词集合*/

            $info['sensitive_words'] = get_sensitives();

            //获取直播间在售商品的正在展示的商品
            $info['show_goods'] = $domain->getLiveShowGoods($liveuid);
            $pull = getPull($stream);
            //$info['pull']=$pull;

            $info['pull'] = "{$configpri['tx_pull']}/live/{$stream}.flv";
            $info['pull_h5'] = "{$configpri['tx_pull']}/live/{$stream}.m3u8";

            $userinfo = getUserInfo($uid, 1);
//            $score = '0.' . ($userinfo['level'] + 100) . '1';
            $score = '001';
            $ip = getRealIp();
            DI()->redis->zAdd('user_' . $stream, $score, $ip);
            DI()->redis->zAdd('userpc_' . $stream, $score, $ip);
            addUserView($liveuid);

			$configpub=getConfigPub();
			$info['chat_visitor_chat__switch'] = $configpub['chat_visitor_chat__switch'];
			$info['chat_visitor_record_infofold__switch'] = $configpub['chat_visitor_record_infofold__switch'];
			//$info['is_fold_off'] = (int)DI()->redis->get("{$liveuid}_is_fold_off");		
            $rs['info'][0] = $info;
            return $rs;
        } else {
            $score = '001';
            $ip = getRealIp();
            DI()->redis->zAdd('user_' . $stream, $score, $ip);
            DI()->redis->zAdd('userpc_' . $stream, $score, $ip);

			$configpub=getConfigPub();
            $info = ['is_fold_off' => (int)DI()->redis->get("{$liveuid}_is_fold_off"),'is_gift_off' => (int)DI()->redis->get("{$liveuid}_is_gift_off"),'is_chat_off' => (int)DI()->redis->get("{$liveuid}_is_chat_off"),'chat_visitor_chat__switch' => $configpub['chat_visitor_chat__switch'],'chat_visitor_record_infofold__switch' => $configpub['chat_visitor_record_infofold__switch']];			
            $rs['info'][0] = $info;

            return $rs;
        }

    }

    /**
     * 离开直播间
     * @desc 用于用户离开直播间
     * @return int code 操作码，0表示成功
     * @return array info
     * */
    public function leaveRoom()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
//        $uid = $this->uid;
//        $token = checkNull($this->token);
//        $stream = checkNull($this->stream);
//
//        if ($uid) {
//            DI()->redis->zRem('userpc_' . $stream, $uid);
//        } else {
//            $uidlist = DI()->redis->zRevRange('userpc_' . $stream, 0, -1);
//            foreach ($uidlist as $key => $value) {
//                if ($value >= 1000000) {
//                    DI()->redis->zRem('userpc_' . $stream, $value);
//                    break;
//                }
//            }
//        }
        return $rs;
    }

    /**
     * 连麦信息
     * @desc 用于主播同意连麦 写入redis
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */

    public function showVideo()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);
        $touid = checkNull($this->touid);
        $pull_url = checkNull($this->pull_url);

        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 token:'.json_encode($token)."\r\n",FILE_APPEND);
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 touid:'.json_encode($touid)."\r\n",FILE_APPEND);
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 pull_url:'.json_encode($pull_url)."\r\n",FILE_APPEND);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $data = array(
            'uid' => $touid,
            'pull_url' => $pull_url,
        );

        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 set:'.json_encode($data)."\r\n",FILE_APPEND);

        DI()->redis->hset('ShowVideo', $uid, json_encode($data));

        return $rs;
    }

    /**
     * 获取最新流地址
     * @desc 用于连麦获取最新流地址
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */

    protected function getPullWithSign($pull)
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        if ($pull == '') {
            return '';
        }
        $list1 = preg_split('/\?/', $pull);
        $originalUrl = $list1[0];

        $list = preg_split('/\//', $originalUrl);
        $url = preg_split('/\./', end($list));

        $stream = $url[0];

        $play_url = PrivateKeyA('rtmp', $stream, 0);

        return $play_url;
    }


    /**
     * 获取僵尸粉
     * @desc 用于获取僵尸粉
     * @return int code 操作码，0表示成功
     * @return array info 僵尸粉信息
     * @return string msg 提示信息
     */

    public function getZombie()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $stream = checkNull($this->stream);

        $stream2 = explode('_', $stream);
        $liveuid = $stream2[0];


        $domain = new Domain_Live();

        $iszombie = $domain->isZombie($liveuid);

        if ($iszombie == 0) {
            $rs['code'] = 1001;
            $rs['info'] = '未开启僵尸粉';
            $rs['msg'] = '未开启僵尸粉';
            return $rs;

        }

        /* 判断用户是否进入过 */
        $isvisit = DI()->redis->sIsMember($liveuid . '_zombie_uid', $uid);

        if ($isvisit) {
            $rs['code'] = 1003;
            $rs['info'] = '用户已访问';
            $rs['msg'] = '用户已访问';
            return $rs;

        }

        $times = DI()->redis->get($liveuid . '_zombie');

        if ($times && $times > 10) {
            $rs['code'] = 1002;
            $rs['info'] = '次数已满';
            $rs['msg'] = '次数已满';
            return $rs;
        } else if ($times) {
            $times = $times + 1;

        } else {
            $times = 0;
        }

        DI()->redis->set($liveuid . '_zombie', $times);
        DI()->redis->sAdd($liveuid . '_zombie_uid', $uid);

        /* 用户列表 */

        $uidlist = DI()->redis->zRevRange('user_' . $stream, 0, -1);

        $uid = implode(",", $uidlist);

        $where = '0';
        if ($uid) {
            $where .= ',' . $uid;
        }

        $where = str_replace(",,", ',', $where);
        $where = trim($where, ",");
        $rs['info'][0]['list'] = $domain->getZombie($stream, $where);

        $nums = DI()->redis->zCard('user_' . $stream);
        if (!$nums) {
            $nums = 0;
        }

        $rs['info'][0]['nums'] = (string)$nums;

        return $rs;
    }

    /**
     * 用户列表
     * @desc 用于直播间获取用户列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].userlist 用户列表
     * @return string info[0].nums 房间人数
     * @return string info[0].votestotal 主播映票
     * @return string info[0].guard_type 守护类型
     * @return string msg 提示信息
     */
    public function getUserLists()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $liveuid = $this->liveuid;
        $stream = checkNull($this->stream);
        $p = $this->p;

        /* 用户列表 */
        $info = $this->getUserList($liveuid, $stream, $p);

        $rs['info'][0] = $info;

        return $rs;
    }

    protected function getUserList($liveuid, $stream, $p = 1)
    {
        /* 用户列表 */
        $n = 1;
        $pnum = 20;
        $start = ($p - 1) * $pnum;

        $domain_guard = new Domain_Guard();

        /* $key="getUserLists_".$stream.'_'.$p;
        $list=getcaches($key);
        if(!$list){  */
        $list = array();

        $uidlist = DI()->redis->zRevRange('user_' . $stream, $start, $pnum, true);

        foreach ($uidlist as $k => $v) {
            $userinfo = getUserInfo($k);
            $info = explode(".", $v);
            $userinfo['contribution'] = (string)$info[0];

            /* 守护 */
            $guard_info = $domain_guard->getUserGuard($k, $liveuid);
            $userinfo['guard_type'] = $guard_info['type'];

            $list[] = $userinfo;
        }

        /*     if($list){
                setcaches($key,$list,30);
            }
		} */

        if (!$list) {
            $list = array();
        }

        $nums = DI()->redis->zCard('user_' . $stream);
        if (!$nums) {
            $nums = 0;
        }

        $rs['userlist'] = $list;
        $rs['nums'] = (string)$nums;

        /* 主播信息 */
        $domain = new Domain_Live();
        $rs['votestotal'] = $domain->getVotes($liveuid);


        return $rs;
    }


    /**
     * 弹窗
     * @desc 用于直播间弹窗信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].consumption 消费总数
     * @return string info[0].votestotal 票总数
     * @return string info[0].follows 关注数
     * @return string info[0].fans 粉丝数
     * @return string info[0].isattention 是否关注，0未关注，1已关注
     * @return string info[0].action 操作显示，0表示自己，30表示普通用户，40表示管理员，501表示主播设置管理员，502表示主播取消管理员，60表示超管管理主播，70表示对方是超管
     * @return object info[0].vip 用户VIP信息
     * @return string info[0].vip.type VIP类型，0表示无VIP，1表示普通VIP，2表示至尊VIP
     * @return object info[0].liang 用户靓号信息
     * @return string info[0].liang.name 号码，0表示无靓号
     * @return array info[0].label 印象标签
     * @return string msg 提示信息
     */
    public function getPop()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $liveuid = $this->liveuid;
        $touid = $this->touid;

        $info = getUserInfo($touid);
        if (!$info) {
            $rs['code'] = 1002;
            $rs['msg'] = '用户信息不存在';
            return $rs;
        }
        $info['follows'] = getFollows($touid);
        $info['fans'] = getFans($touid);

        $info['isattention'] = (string)isAttention($uid, $touid);
        if ($uid == $touid) {
            $info['action'] = '0';
        } else {
            $uid_admin = isAdmin($uid, $liveuid);
            $touid_admin = isAdmin($touid, $liveuid);

            if ($uid_admin == 40 && $touid_admin == 30) {
                $info['action'] = '40';
            } else if ($uid_admin == 50 && $touid_admin == 30) {
                $info['action'] = '501';
            } else if ($uid_admin == 50 && $touid_admin == 40) {
                $info['action'] = '502';
            } else if ($uid_admin == 60 && $touid_admin < 50) {
                $info['action'] = '40';
            } else if ($uid_admin == 60 && $touid_admin == 50) {
                $info['action'] = '60';
            } else if ($touid_admin == 60) {
                $info['action'] = '70';
            } else {
                $info['action'] = '30';
            }

        }

        /* 标签 */
        $labels = array();
        if ($touid == $liveuid) {
            $key = "getMyLabel_" . $touid;
            $label = getcaches($key);
            if (!$label) {
                $domain2 = new Domain_User();
                $label = $domain2->getMyLabel($touid);

                setcaches($key, $label);
            }

            $labels = array_slice($label, 0, 2);
        }
        $info['label'] = $labels;

        $rs['info'][0] = $info;
        return $rs;
    }

    /**
     * 礼物列表
     * @desc 用于获取礼物列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].coin 余额
     * @return array info[0].giftlist 礼物列表
     * @return string info[0].giftlist[].id 礼物ID
     * @return string info[0].giftlist[].type 礼物类型
     * @return string info[0].giftlist[].mark 礼物标识
     * @return string info[0].giftlist[].giftname 礼物名称
     * @return string info[0].giftlist[].needcoin 礼物价格
     * @return string info[0].giftlist[].gifticon 礼物图片
     * @return string msg 提示信息
     */
    public function getGiftList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_Live();
        $giftlist = $domain->getGiftList();
        $proplist = $domain->getPropgiftList();

        $domain2 = new Domain_User();
        $coin = $domain2->getBalance($uid);

        $rs['info'][0]['giftlist'] = $giftlist;
        $rs['info'][0]['proplist'] = $proplist;
        $rs['info'][0]['coin'] = $coin['coin'];
        return $rs;
    }

    /**
     * 赠送礼物
     * @desc 用于赠送礼物
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].gifttoken 礼物token
     * @return string info[0].level 用户等级
     * @return string info[0].coin 用户余额
     * @return string msg 提示信息
     */
    public function sendGift()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid = $this->uid;
        $token = $this->token;
        $liveuid = $this->liveuid;
        $stream = checkNull($this->stream);
        $giftid = $this->giftid;
        $giftcount = $this->giftcount;
        $ispack = $this->ispack;
        $is_sticker = $this->is_sticker;


        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_Live();
        if ($is_sticker == '1') {
            $giftlist = $domain->getPropgiftList();

            $gift_info = array();
            foreach ($giftlist as $k => $v) {
                if ($giftid == $v['id']) {
                    $gift_info = $v;
                }
            }
        } else {
            $giftlist = $domain->getGiftList();
            $gift_info = array();
            foreach ($giftlist as $k => $v) {
                if ($giftid == $v['id']) {
                    $gift_info = $v;
                }
            }
        }

        if (!$gift_info) {
            $rs['code'] = 1002;
            $rs['msg'] = '礼物信息不存在';
            return $rs;
        }

        if ($gift_info['mark'] == 2) {
            /* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info = $domain_guard->getUserGuard($uid, $liveuid);
            if ($guard_info['type'] == 0) {
                $rs['code'] = 1002;
                $rs['msg'] = '该礼物是守护专属礼物奥~';
                return $rs;
            }
        }

        $domain = new Domain_Live();
        $result = $domain->sendGift($uid, $liveuid, $stream, $giftid, $giftcount, $ispack);

        if ($result == 1001) {
            $rs['code'] = 1001;
            $rs['msg'] = '余额不足';
            return $rs;
        } else if ($result == 1003) {
            $rs['code'] = 1003;
            $rs['msg'] = '背包中数量不足';
            return $rs;
        } else if ($result == 1002) {
            $rs['code'] = 1002;
            $rs['msg'] = '礼物信息不存在';
            return $rs;
        }

        $rs['info'][0]['gifttoken'] = $result['gifttoken'];
        $rs['info'][0]['level'] = $result['level'];
        $rs['info'][0]['coin'] = $result['coin'];

        unset($result['gifttoken']);

        DI()->redis->set($rs['info'][0]['gifttoken'], json_encode($result));


        return $rs;
    }

    /**
     * 发送弹幕
     * @desc 用于发送弹幕
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].barragetoken 礼物token
     * @return string info[0].level 用户等级
     * @return string info[0].coin 用户余额
     * @return string msg 提示信息
     */
    public function sendBarrage()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid = $this->uid;
        $token = $this->token;
        $liveuid = $this->liveuid;
        $stream = checkNull($this->stream);
        $giftid = 0;
        $giftcount = 1;

        $content = checkNull($this->content);
        if ($content == '') {
            $rs['code'] = 1003;
            $rs['msg'] = '弹幕内容不能为空';
            return $rs;
        }

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_Live();
        $result = $domain->sendBarrage($uid, $liveuid, $stream, $giftid, $giftcount, $content);

        if ($result == 1001) {
            $rs['code'] = 1001;
            $rs['msg'] = '余额不足';
            return $rs;
        } else if ($result == 1002) {
            $rs['code'] = 1002;
            $rs['msg'] = '礼物信息不存在';
            return $rs;
        }

        $rs['info'][0]['barragetoken'] = $result['barragetoken'];
        $rs['info'][0]['level'] = $result['level'];
        $rs['info'][0]['coin'] = $result['coin'];

        unset($result['barragetoken']);

        DI()->redis->set($rs['info'][0]['barragetoken'], json_encode($result));

        return $rs;
    }

    /**
     * 设置/取消管理员
     * @desc 用于设置/取消管理员
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].isadmin 是否是管理员，0表示不是管理员，1表示是管理员
     * @return string msg 提示信息
     */
    public function setAdmin()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = $this->token;
        $liveuid = $this->liveuid;
        $touid = $this->touid;

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        if ($uid != $liveuid) {
            $rs['code'] = 1001;
            $rs['msg'] = '你不是该房间主播，无权操作';
            return $rs;
        }

        $domain = new Domain_Live();
        $info = $domain->setAdmin($liveuid, $touid);

        if ($info == 1004) {
            $rs['code'] = 1004;
            $rs['msg'] = '最多设置5个管理员';
            return $rs;
        } else if ($info == 1003) {
            $rs['code'] = 1003;
            $rs['msg'] = '操作失败，请重试';
            return $rs;
        }

        if ($info == 1) {   //设置管理员
            global $socketSecretKey;
            $domain = new Domain_User();
            $user = $domain->getUser($touid);
            sendDataToChatServer([
                'secretKey' => $socketSecretKey,
                'type' => 'setManagerNotice',
                'msg' => [
                    'liveuid' => $liveuid,
                    'touid' => $touid,
                    'tousername' => $user['user_nicename'] ?? '',
                ],
            ]);
        }

        $rs['info'][0]['isadmin'] = $info;
        return $rs;
    }

    /**
     * 管理员列表
     * @desc 用于获取管理员列表
     * @return int code 操作码，0表示成功
     * @return array info 管理员列表
     * @return array info[0]['list'] 管理员列表
     * @return array info[0]['list'][].userinfo 用户信息
     * @return string info[0]['nums'] 当前人数
     * @return string info[0]['total'] 总数
     * @return string msg 提示信息
     */
    public function getAdminList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Live();
        $info = $domain->getAdminList($this->liveuid);

        $rs['info'][0] = $info;
        return $rs;
    }

    /**
     * 举报类型
     * @desc 用于获取举报类型
     * @return int code 操作码，0表示成功
     * @return array info 列表
     * @return string info[].name 类型名称
     * @return string msg 提示信息
     */
    public function getReportClass()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Live();
        $info = $domain->getReportClass();


        $rs['info'] = $info;
        return $rs;
    }


    /**
     * 用户举报
     * @desc 用于用户举报
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].msg 举报成功
     * @return string msg 提示信息
     */
    public function setReport()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);
        $touid = $this->touid;
        $content = checkNull($this->content);
        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        if (!$content) {
            $rs['code'] = 1001;
            $rs['msg'] = '举报内容不能为空';
            return $rs;
        }

        if (mb_strlen($account) > 200) {
            $rs['code'] = 1002;
            $rs['msg'] = '账号长度不能超过200个字符';
            return $rs;
        }

        $domain = new Domain_Live();
        $info = $domain->setReport($uid, $touid, $content);
        if ($info === false) {
            $rs['code'] = 1002;
            $rs['msg'] = '举报失败，请重试';
            return $rs;
        }

        $rs['info'][0]['msg'] = "举报成功";
        return $rs;
    }

    /**
     * 主播映票
     * @desc 用于获取主播映票
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].votestotal 用户总数
     * @return string msg 提示信息
     */
    public function getVotes()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Live();
        $info = $domain->getVotes($this->liveuid);

        $rs['info'][0] = $info;
        return $rs;
    }

    /**
     * 禁言
     * @desc 用于 禁言操作
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */

    public function setShutUp()
    {
        $rs = array('code' => 0, 'msg' => '禁言成功', 'info' => array());

        $uid = $this->uid;
        $token = $this->token;
        $liveuid = $this->liveuid;
        $touid = $this->touid;
        $type = $this->type;
        $stream = $this->stream;

        //file_put_contents('./setShutUp.txt',date('Y-m-d H:i:s').' 提交参数信息 request:'.json_encode($_REQUEST)."\r\n",FILE_APPEND);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = 700;
            $rs['msg'] = 'token已过期，请重新登陆';
            return $rs;
        }

        $uidtype = isAdmin($uid, $liveuid);

        if ($uidtype == 30) {
            $rs["code"] = 1001;
            $rs["msg"] = '无权操作';
            return $rs;
        }

        $touidtype = isAdmin($touid, $liveuid);

        if ($touidtype == 60) {
            $rs["code"] = 1001;
            $rs["msg"] = '对方是超管，不能禁言';
            return $rs;
        }

        if ($uidtype == 40) {
            if ($touidtype == 50) {
                $rs["code"] = 1002;
                $rs["msg"] = '对方是主播，不能禁言';
                return $rs;
            }
            if ($touidtype == 40) {
                $rs["code"] = 1002;
                $rs["msg"] = '对方是管理员，不能禁言';
                return $rs;
            }

            /* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info = $domain_guard->getUserGuard($touid, $liveuid);

            if ($uid != $liveuid && $guard_info && $guard_info['type'] == 2) {
                $rs["code"] = 1004;
                $rs["msg"] = '对方是尊贵守护，不能禁言';
                return $rs;
            }

        }
        $showid = 0;
        if ($type == 1 || $stream) {
            $showid = 1;
        }
        $domain = new Domain_Live();
        $result = $domain->setShutUp($uid, $liveuid, $touid, $showid);

        if ($result == 1002) {
            $rs["code"] = 1003;
            $rs["msg"] = '对方已被禁言';
            return $rs;

        } else if (!$result) {
            $rs["code"] = 1005;
            $rs["msg"] = '操作失败，请重试';
            return $rs;
        }

        global $socketSecretKey;
        $domain = new Domain_User();
        $user = $domain->getUser($touid);

        $redis = connectionRedis();
        $redis->zAdd("chatShutUp.{$liveuid}", 1, $touid);
        sendDataToChatServer([
            'secretKey' => $socketSecretKey,
            'type' => 'shutUpNotice',
            'msg' => [
                'liveuid' => $liveuid,
                'touid' => $touid,
                'tousername' => $user['user_nicename'] ?? '',
            ],
        ]);

        return $rs;
    }

    /**
     * 踢人
     * @desc 用于直播间踢人
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].msg 踢出成功
     * @return string msg 提示信息
     */
    public function kicking()
    {
        $rs = array('code' => 0, 'msg' => '踢人成功', 'info' => array());

        $uid = $this->uid;
        $token = $this->token;
        $liveuid = $this->liveuid;
        $touid = $this->touid;

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $admin_uid = isAdmin($uid, $liveuid);
        if ($admin_uid == 30) {
            $rs['code'] = 1001;
            $rs['msg'] = '无权操作';
            return $rs;
        }
        $admin_touid = isAdmin($touid, $liveuid);

        if ($admin_touid == 60) {
            $rs["code"] = 1002;
            $rs["msg"] = '对方是超管，不能被踢出';
            return $rs;
        }

        if ($admin_uid != 60) {
            if ($admin_touid == 50) {
                $rs['code'] = 1001;
                $rs['msg'] = '对方是主播，不能被踢出';
                return $rs;
            }

            if ($admin_touid == 40) {
                $rs['code'] = 1002;
                $rs['msg'] = '对方是管理员，不能被踢出';
                return $rs;
            }
            /* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info = $domain_guard->getUserGuard($touid, $liveuid);

            if ($uid != $liveuid && $guard_info && $guard_info['type'] == 2) {
                $rs["code"] = 1004;
                $rs["msg"] = '对方是尊贵守护，不能被踢出';
                return $rs;
            }
        }

        $domain = new Domain_Live();

        $result = $domain->kicking($uid, $liveuid, $touid);
        if ($result == 1002) {
            $rs["code"] = 1005;
            $rs["msg"] = '对方已被踢出';
            return $rs;
        } else if (!$result) {
            $rs["code"] = 1006;
            $rs["msg"] = '操作失败，请重试';
            return $rs;
        }

        $rs['info'][0]['msg'] = '踢出成功';
        return $rs;
    }

    /**
     * 超管关播
     * @desc 用于超管关播
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].msg 提示信息
     * @return string msg 提示信息
     */

    public function superStopRoom()
    {

        $rs = array('code' => 0, 'msg' => '关闭成功', 'info' => array());

        $uid = checkNull($this->uid);
        $token = checkNull($this->token);
        $liveuid = checkNull($this->liveuid);
        $type = checkNull($this->type);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }


        $domain = new Domain_Live();

        $result = $domain->superStopRoom($uid, $liveuid, $type);
        if ($result == 1001) {
            $rs['code'] = 1001;
            $rs['msg'] = '你不是超管，无权操作';
            $rs['info'][0]['msg'] = '你不是超管，无权操作';
            return $rs;
        } else if ($result == 1002) {
            $rs['code'] = 1002;
            $rs['msg'] = '该主播已被禁播';
            $rs['info'][0]['msg'] = '该主播已被禁播';
            return $rs;
        }
        $rs['info'][0]['msg'] = '关闭成功';

        return $rs;
    }

    /**
     * 用户余额
     * @desc 用于获取用户余额
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].coin 余额
     * @return string msg 提示信息
     */
    public function getCoin()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = $this->uid;
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }


        $domain2 = new Domain_User();
        $coin = $domain2->getBalance($uid);

        $rs['info'][0]['coin'] = $coin['coin'];
        return $rs;
    }

    /**
     * 检测房间状态
     * @desc 用于检测房间状态
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].status 状态 0关1开
     * @return string msg 提示信息
     */
    public function checkLiveing()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid = checkNull($this->uid);
//        $token=checkNull($this->token);
        $liveuid = checkNull($this->liveuid);
        $stream = checkNull($this->stream);

        $domain = new Domain_Live();

//        $checkToken=checkToken($uid,$token);
//
//        if($checkToken==700){
//
//            //将主播关播
//            $domain->stopRoom($uid,$stream);
//
//            $rs['code'] = $checkToken;
//            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
//            return $rs;
//        }

        //file_put_contents(API_ROOT.'/Runtime/checkLiveing_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
        //file_put_contents(API_ROOT.'/Runtime/checkLiveing_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 stream:'.json_encode($stream)."\r\n",FILE_APPEND);

        $info = $domain->checkLiveing($uid, $liveuid, $stream);

        //file_put_contents(API_ROOT.'/Runtime/checkLiveing_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 info:'.json_encode($info)."\r\n",FILE_APPEND);

        $rs['info'][0]['status'] = $info;
        return $rs;
    }

    /**
     * 获取直播信息
     * @desc 用于个人中心进入直播间获取直播信息
     * @return int code 操作码，0表示成功
     * @return array info  直播信息
     * @return string msg 提示信息
     */
    public function getLiveInfo()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid = checkNull($this->uid);
        $token = checkNull($this->token);

        $checkToken = checkToken($uid, $token);
        $liveuid = checkNull($this->liveuid);

        if ($liveuid < 1) {
            $rs['code'] = 1001;
            $rs['msg'] = '参数错误';
            return $rs;
        }


        $domain2 = new Domain_Live();
        $info = $domain2->getLiveInfo($liveuid, $uid);
        if (!$info) {
            $rs['code'] = 1002;
            $rs['msg'] = '直播已结束';
            return $rs;
        }

        $rs['info'][0] = $info;
        return $rs;
    }

    /**
     * 直播间在售商品列表是否正在展示状态
     * @desc 用于主播改变直播间在售商品列表是否正在展示状态
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return int info[0]['status'] 商品是否展示 0 不展示 1 展示
     * @return int info[0]['goods_type'] 商品类型 0 站内商品 1 站外商品
     */
    public function setLiveGoodsIsShow()
    {
        $rs = array('code' => 0, 'msg' => '设置成功', 'info' => array());
        $uid = checkNull($this->uid);
        $token = checkNull($this->token);
        $goodsid = checkNull($this->goodsid);

        $checkToken = checkToken($uid, $token);
        if ($checkToken == 700) {
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        $domain = new Domain_Live();
        $res = $domain->setLiveGoodsIsShow($uid, $goodsid);
        if ($res == 1001) {
            $rs['code'] = 1001;
            $rs['msg'] = '商品不存在';
            return $rs;
        } else if ($res == 1002) {
            $rs['code'] = 1002;
            $rs['msg'] = '商品不可售';
            return $rs;
        } else if ($res == 1003) {
            $rs['code'] = 1003;
            $rs['msg'] = '商品取消展示失败';
            return $rs;
        } else if ($res == 1004) {
            $rs['code'] = 1004;
            $rs['msg'] = '商品设置展示失败';
            return $rs;
        }

        $rs['info'][0] = $res;

        return $rs;
    }


} 
