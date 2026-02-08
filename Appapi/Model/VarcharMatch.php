<?php

class Model_VarcharMatch extends PhalApi_Model_NotORM
{
    private function _model()
    {
        return DI()->notorm->varchar_match;
    }

    public function getInfoById($id)
    {
        $info = $this->_model()
            ->where(['id' => $id])
            ->fetchOne();
        return $info;
    }

    /*获取推单列表*/
    public function getList($where = [], $p, $limit = 32)
    {
		$res['list'] = [];
		$res['totalcount'] = $res['num'] = 0;
        if ($p < 1) {
            $p = 1;
        }
        $start = ($p-1)*$limit;
        $list = $this->_model()->select("*")
            ->where($where)
            ->order("id desc")
            ->limit($start, $limit)
            ->order('sort desc')
            ->fetchAll();
        if (! $list) {
            return null;
        }
        foreach ($list as $k => $v) {
			$list[$k]['thumb'] = get_upload_path($v['thumb']);
			$list[$k]['home_icon'] = get_upload_path($v['home_icon']);
			$list[$k]['away_icon'] = get_upload_path($v['away_icon']);
            $list[$k]['satrt_time'] = date('Y-m-d H:i:s',$v['satrt_time']);
        }
        $res['list'] = $list;
        $res['totalcount'] = $this->_model()
            ->select("*")
            ->where($where)
            ->count();
        $res['num'] = $limit;
        return $res;
    }
}
