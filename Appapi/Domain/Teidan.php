<?php

class Domain_Teidan {

    public function addTeidan($liveuid,$cid,$title){
        $rs = array();

        $model = new Model_Teidan();
        $rs = $model->addTeidan($liveuid,$cid,$title);

        return $rs;
    }
	
    public function editTeidan($liveuid,$tid,$cid,$title,$sid) {
        $rs = array();

        $model = new Model_Teidan();
        $rs = $model->editTeidan($liveuid,$tid,$cid,$title,$sid);

        return $rs;
    }
	
    public function delTeidan($liveuid,$tid) {
        $rs = array();

        $model = new Model_Teidan();
        $rs = $model->delTeidan($liveuid,$tid);

        return $rs;
    }
	
    public function teidanList($liveuid,$cid,$sid,$p) {
        $rs = array();

        $model = new Model_Teidan();
        $rs = $model->teidanList($liveuid,$cid,$sid,$p);

        return $rs;
    }
	
    public function teidanStatusList() {
        $rs = array();

        $model = new Model_Teidan();
        $rs = $model->teidanStatusList();

        return $rs;
    }
	
    public function teidanCatList() {
        $rs = array();

        $model = new Model_Teidan();
        $rs = $model->teidanCatList();

        return $rs;
    }

}
