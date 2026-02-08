<?php
/**
 * 手动直播列表
 */
class Api_VarcharMatch extends PhalApi_Api {

    public function getRules() {
        return array(
            'getList' => array(
                'limit' => array('name' => 'limit', 'type' => 'int', 'default'=>'8' ,'desc' => '每页记录数'),
                'type' => array('name' => 'type', 'type' => 'int', 'min' => 0, 'require' => false, 'desc' => '1足球，2篮球'),
                'hot_status' => array('name' => 'hot_status', 'type' => 'int', 'min' => 0, 'require' => false, 'desc' => '是否热门：0否，1是'),
                'rocm_status' => array('name' => 'rocm_status', 'type' => 'int', 'min' => 0, 'require' => false, 'desc' => '是否推荐：0否，1是'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
            ),
            'getInfo' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '比赛ID'),
            ),
        );
    }

    /**
     *  直播记录
     * @desc 用于获取用户订单记录
     * @return int id 比赛ID
     * @return int type 1足球，2篮球
     * @return array info 字段详情列表
     * @return int type 比赛类型，1足球，2篮球
     * @return string home_team A战队
     * @return string home_icon A战队LOGO
     * @return string away_team B战队
     * @return string away_icon B战队LOGO
     * @return string start_time 比赛开始时间
     * @return string view_url 直播地址
     * @return string msg 提示信息
     */
    public function getList()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $where = [];
        $this->type && $where['type'] = $this->type;
        $this->hot_status && $where['hot_status'] = $this->hot_status;
        $this->rocm_status && $where['rocm_status'] = $this->rocm_status;
        $this->rocm_status;
        $p = $this->p;

        /* 推单列表 */
        $domain = new Domain_VarcharMatch();
        $info = $domain->getVarcharMatchList($where, $p, $this->limit);

        $rs['info'][0]=$info;

        return $rs;
    }

    /**
     *  直播记录
     * @desc 用于获取用户订单记录
     * @return int id 比赛ID
     * @return int type 比赛类型，1足球，2篮球
     * @return string home_team A战队
     * @return string home_icon A战队LOGO
     * @return string away_team B战队
     * @return string away_icon B战队LOGO
     * @return string start_time 比赛开始时间
     * @return string view_url 直播地址
     */
    public function getInfo()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        /* 推单列表 */
        $id = $this->id;
        $domain = new Domain_VarcharMatch();
        $info = $domain->getInfo($id);

        $rs['info'] = $info;

        return $rs;
    }
}
