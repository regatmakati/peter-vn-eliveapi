<?php

trait Action_GetRecommendedStream
{
    public function GetRecommendedStreamRules(): array
    {
        return [
            'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
			'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '主播用户ID'),
            'token' => array('name' => 'token', 'type' => 'string', 'default' => '', 'desc' => '用户token'),
            'limit' => array('name' => 'limit', 'type' => 'int', 'default'=>'8' ,'desc' => '每页记录数'),
            'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            'field' => array('name' => 'field', 'type' => 'string', 'default' => 'recom_sort', 'desc' => '可排序字段，支持 recom_sort, starttime'),
            'order' => array('name' => 'order', 'type' => 'string', 'default' => 'desc', 'desc' => '排序，asc, desc, random'),
            'ismatch' => array('name' => 'ismatch', 'type' => 'int', 'min' => 0, 'max' => 1, 'default'=>0 ,'desc' => '是否推荐赛事'),
        ];
    }

    /**
     * 获取推荐直播，可获取随机推荐
     * @desc 用于获取推荐直播
     * @return int code 操作码，0表示成功
     * @return array info 随机直播列表
     * @return string info[0][].uid 主播id
     * @return string info[0][].title 直播标题
     * @return string info[0][].city 主播位置
     * @return string info[0][].stream 流名
     * @return string info[0][].pull 播流地址
     * @return string info[0][].thumb 直播封面
     * @return string info[0][].isvideo 是否假视频
     * @return string info[0][].type 直播类型
     * @return string info[0][].type_val 类型值
     * @return string info[0][].goodnum 靓号
     * @return string info[0][].anyway 横竖屏，0表示竖屏，1表示横屏
     * @return string info[0][].starttime 开播时间
     * @return string info[0][].isshop 是否开启店铺
     * @return string info[0][].game_action 游戏类型
     * @return string info[0][].notice 直播公告
     * @return string info[0][].hotvotes hotvotes
     * @return string info[0][].nums 人数
     * @return string info[0][].avatar 主播头像
     * @return string info[0][].avatar_thumb 头像缩略图
     * @return string info[0][].user_nicename 直播昵称
     * @return string info[0][].sex 主播性别
     * @return string info[0][].level 等级
     * @return string info[0][].level_anchor 主播等级
     * @return string info[0][].game game
     * @return string info[0][].issubscribed 是否已订阅
     * @return string msg 提示信息
     * @return array
     */
    public function GetRecommendedStream()
    {
        $uid=checkNull($this->uid);
		$liveuid=checkNull($this->liveuid);
        $token=checkNull($this->token);
        $checkToken = checkToken($uid,$token);
        $isLoggedIn = $checkToken === 0;
        $result = (new Domain_Home())->getRecommendedStream($uid,$liveuid, $isLoggedIn, $this->p, $this->limit, $this->field, $this->order, $this->ismatch);

        return [
            'code' => !$result ? 1001 : 0,
            'msg' => '',
            'info' => $result ?? [],
        ];
    }

}