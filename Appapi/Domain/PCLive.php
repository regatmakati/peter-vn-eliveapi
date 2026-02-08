<?php

class Domain_PCLive {
	
	public function getAuth($uid) {
		$rs = array();

		$model = new Model_PCLive();
		$rs = $model->getAuth($uid);
		return $rs;
	}

	public function getClass(){
        $model = new Model_PCLive();
        $rs = $model->getClass();
        return $rs;
    }

    public function getMatch($liveclassid){
        $model = new Model_PCLive();
        $rs = $model->getMatch($liveclassid);
        return $rs;
    }

    public function getPCMatch(){
        $model = new Model_PCLive();
        $rs = $model->getPCMatch();
        return $rs;
    }

	public function stopRoom($uid,$stream) {
		$rs = array();

		$model = new Model_Live();
		$rs = $model->stopRoom($uid,$stream);
		return $rs;
	}
	
	public function stopInfo($stream) {
		$rs = array();

		$model = new Model_Live();
		$rs = $model->stopInfo($stream);
		return $rs;
	}
	
	public function checkLive($uid,$liveuid,$stream) {
		$rs = array();

		$model = new Model_Live();
		$rs = $model->checkLive($uid,$liveuid,$stream);
		return $rs;
	}
	
	public function roomCharge($uid,$liveuid,$stream) {
		$rs = array();

		$model = new Model_Live();
		$rs = $model->roomCharge($uid,$liveuid,$stream);
		return $rs;
	}

	public function getPop($touid) {
		$rs = array();

		$model = new Model_Live();
		$rs = $model->getPop($touid);
		return $rs;
	}

	public function getGiftList() {

        $key='getGiftList';
		$list=getcaches($key);

		if(!$list){
			$model = new Model_Live();
            $list = $model->getGiftList();
            if($list){
                setcaches($key,$list);
            }
		}
        
        foreach($list as $k=>$v){
			$list[$k]['gifticon']=get_upload_path($v['gifticon']);
		}	
        
		return $list;
	}
	public function getPropgiftList() {

        $key='getPropgiftList';
		$list=getcaches($key);

		if(!$list){
			$model = new Model_Live();
            $list = $model->getPropgiftList();
            if($list){
                setcaches($key,$list);
            }
		}
        
        foreach($list as $k=>$v){
			$list[$k]['gifticon']=get_upload_path($v['gifticon']);
		}	
        
		return $list;
    }
}
