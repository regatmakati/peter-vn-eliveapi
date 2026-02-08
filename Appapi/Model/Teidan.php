<?php

class Model_Teidan extends PhalApi_Model_NotORM {

	/* 添加推单 */
	public function addTeidan($liveuid,$cid,$title){

		$liveinfo = DI()->notorm->live->where("uid=?",$liveuid)->fetchOne();
		if(!$liveinfo) return 1002;
		
		$data['type'] = $cid;
		$data['title'] = $title;
		$data['liveuid'] = $liveuid;
		$data['status'] = 1;
		$data['addtime'] = time();
		return DI()->notorm->live_teidan->insert($data);					
	}
	
	/* 编辑推单 */
	public function editTeidan($liveuid,$tid,$cid,$title,$sid){

		$data = [];
		if($cid){
			$data['type'] = $cid;
		}

		if($sid){
			$data['status'] = $sid;
		}

		if($title){
			$data['title'] = $title;
		}
		
		return DI()->notorm->live_teidan
					->where("liveuid=? and id=? ",$liveuid,$tid)
					->update($data);
					
	}
	
	/* 删除推单 */
	public function delTeidan($liveuid,$tid){

		return DI()->notorm->live_teidan
					->where("liveuid=? and id=? ",$liveuid,$tid)
					->delete();
					
	}
	
	/*获取推单列表*/
	public function teidanList($liveuid,$cid,$sid,$p){
        if($p<1){
            $p=1;
        }
		$nums=8;
		$start=($p-1)*$nums;

		$where=" liveuid='$liveuid' ";

		if($cid>0){
			$where.=" and type={$cid}";
		}

		if($sid>0){
			$where.=" and status={$sid}";
		}

		$list=DI()->notorm->live_teidan->select("*")->where($where)->order("addtime desc")->limit($start,$nums)->fetchAll();

		if(!$list){
			return [];
		}

		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
		}
		$res['list'] = $list;
		$res['totalcount'] = DI()->notorm->live_teidan->select("*")->where($where)->count();
		$res['num'] = 8;
		return $res;
	}
	
	/*获取推单状态列表*/
	public function teidanStatusList(){
        $status = [
            '1' => 'GO',
            '2' => '红',
            '3' => '黑',
        ];
		
		$tdStatus[] =  array('sid'=>1,'name'=>'GO');
		$tdStatus[] =  array('sid'=>2,'name'=>'红');
		$tdStatus[] =  array('sid'=>3,'name'=>'黑');

		return $tdStatus;
	}
	
	/*获取推单分类列表*/
	public function teidanCatList(){
		
		$tdtype[] =  array('cid'=>1,'name'=>'大小球');
		$tdtype[] =  array('cid'=>2,'name'=>'让分胜负');
		$tdtype[] =  array('cid'=>3,'name'=>'足球角球');
		$tdtype[] =  array('cid'=>4,'name'=>'足球比分');
		$tdtype[] =  array('cid'=>5,'name'=>'篮球单节');
		$tdtype[] =  array('cid'=>6,'name'=>'篮球单队大小');		
		$tdtype[] =  array('cid'=>7,'name'=>'篮球大小分');
		return $tdtype;
	}

	
}
