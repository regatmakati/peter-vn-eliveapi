<?php

class Domain_Match {
	
	public function pushMatchData($match_id) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->pushMatchData($match_id);

		return $rs;
	}
	
	public function getMatchPlayer($match_id,$range_type) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getMatchPlayer($match_id,$range_type);

		return $rs;
	}
	
	public function getMatchOutline($match_id,$range_type) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getMatchOutline($match_id,$range_type);

		return $rs;
	}
	
	public function getMatchAnalysis($match_id,$range_type) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getMatchAnalysis($match_id,$range_type);

		return $rs;
	}
	
	public function getMatchIndex($match_id,$board_num,$handicap) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getMatchIndex($match_id,$board_num,$handicap);

		return $rs;
	}
	
	public function getMatchData($match_id,$index) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getMatchData($match_id,$index);

		return $rs;
	}
	
	public function getMatchInfo($match_id) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getMatchInfo($match_id);

		return $rs;
	}
	

	public function getLOLMatchListByStat($status,$qdate) {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getLOLMatchListByStat($status,$qdate);

		return $rs;
	}
	
	
	public function getLOLMatchList() {
		$rs = array();

		$model = new Model_Match();
		$rs = $model->getLOLMatchList();

		return $rs;
	}

    public function getPCMatchList($page = 1, $limit = 15)
    {
        return (new Model_Match())->getPCMatchList($page, $limit);
	}

    public function getPCLOLMatchList() {
        $model = new Model_Match();
        $rs = $model->getPCLOLMatchList();

        return $rs;
    }

}
