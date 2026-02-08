<?php

class Domain_VarcharMatch {

    public function getVarcharMatchList($where, $p, $limit)
    {
        $model = new Model_VarcharMatch();
        $rs = $model->getList($where, $p, $limit);

        return $rs;
    }

    public function getInfo($id)
    {
        $model = new Model_VarcharMatch();
        $rs = $model->getInfoById($id);

        return $rs;
    }

}
