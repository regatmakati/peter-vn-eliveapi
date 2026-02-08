<?php
/**
 * 直播间推单
 *
 * @author: sukura 2022-03-28
 */

class Api_Teidan extends PhalApi_Api {

	public function getRules() {
        return array(    
            'teidanList'=>array(
                'liveuid'=>array('name' => 'liveuid', 'type' => 'int','require' => true,'desc' => '主播ID'),
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true,'desc' => '用户id'),
				'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
				'cid'=>array('name' => 'cid', 'type' => 'int','default'=>0,'desc' => '推单分类ID'),
				'sid'=>array('name' => 'sid', 'type' => 'int','default'=>0,'desc' => '推单状态ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'), 
            ), 

			'delTeidan' => array(
				'liveuid'=>array('name' => 'liveuid', 'type' => 'int','require' => true,'desc' => '主播ID'),
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'tid' => array('name' => 'tid', 'type' => 'int', 'require' => true, 'desc' => '推单ID'),
			),

			'editTeidan' => array(
				'liveuid'=>array('name' => 'liveuid', 'type' => 'int','require' => true,'desc' => '主播ID'),
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'tid' => array('name' => 'tid', 'type' => 'int', 'require' => true, 'desc' => '推单ID'),
				'cid'=>array('name' => 'cid', 'type' => 'int','default'=>0,'desc' => '推单分类ID'),
				'title' => array('name' => 'title', 'type' => 'string', 'desc' => '推单标题'),
				'sid'=>array('name' => 'sid', 'type' => 'int','default'=>0,'desc' => '推单状态ID'),
			),	

			'addTeidan' => array(
				'liveuid'=>array('name' => 'liveuid', 'type' => 'int','require' => true,'desc' => '主播ID'),
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'cid'=>array('name' => 'cid', 'type' => 'int','default'=>0,'desc' => '推单分类ID'),
				'title' => array('name' => 'title', 'type' => 'string','require' => true, 'desc' => '推单标题'),
			),			
			
        );
	}

	/**
	 * 添加推单
	 * @desc 用于添加推单
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 添加成功提示信息 
	 * @return string msg 提示信息
	 */
	public function addTeidan() {
		$rs = array('code' => 0, 'msg' => '添加成功', 'info' => array());

        $liveuid = checkNull($this->liveuid);
        $uid = checkNull($this->uid);
		$token = checkNull($this->token);

		$cid = checkNull($this->cid);
		$title = checkNull($this->title);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		if($liveuid != $uid){
			$rs['code'] = 701;
			$rs['msg'] = '您不是该房间主播,无权添加推单！';
			return $rs;
		} 
        
		if(!anchorAuth($uid)){
			$rs['code'] = 1003;
			$rs['msg'] = '您不是主播,无权操作推单！';
			return $rs;
		}
		
		$domain=new Domain_Teidan();
		$info = $domain->addTeidan($liveuid,$cid,$title);
	 
		if($info==1002){
			$rs['code'] = 1002;
			$rs['msg'] = '主播未开通直播间！';
			return $rs;
		}

		//发送推单socket通知
		global $socketSecretKey;
		sendDataToChatServer([
			'secretKey' => $socketSecretKey,
			'type' => 'sendTeidanMessage',
			'msg' => [
				'action' => 'add',
				'id' => $info,
				'liveuid' => $liveuid,
				'title' =>  $title,
				'type' => getTeidanTypes($cid),
				'status' => 1,//$this->getStatus($data['status']),
				'buy' => 0,
				'oppositebuy' => 0
			]
		]);	
			
		$rs['info'][0]['msg']='添加成功';
		return $rs;
	}	
	
	/**
	 * 编辑推单
	 * @desc 用于编辑推单
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 编辑成功提示信息 
	 * @return string msg 提示信息
	 */
	public function editTeidan() {
		$rs = array('code' => 0, 'msg' => '编辑成功', 'info' => array());

        $liveuid = checkNull($this->liveuid);
        $uid = checkNull($this->uid);
		$token = checkNull($this->token);
		$tid = checkNull($this->tid);
		$cid = checkNull($this->cid);
		$title = checkNull($this->title);
		$sid = checkNull($this->sid);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		if($liveuid != $uid){
			$rs['code'] = 701;
			$rs['msg'] = '您不是该房间主播,无权编辑推单！';
			return $rs;
		}  

		if(!anchorAuth($uid)){
			$rs['code'] = 1003;
			$rs['msg'] = '您不是主播,无权操作推单！';
			return $rs;
		}
        
		$domain=new Domain_Teidan();
		$info = $domain->editTeidan($liveuid,$tid,$cid,$title,$sid);
	 
		if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = '编辑失败';
			return $rs;
		}

		//发送推单socket通知
		global $socketSecretKey;
		sendDataToChatServer([
			'secretKey' => $socketSecretKey,
			'type' => 'sendTeidanMessage',
			'msg' => [
				'action' => 'edit',
				'id' => $tid,
				'liveuid' => $liveuid,
				'title' =>  $title,
				'type' => getTeidanTypes($cid),
				'status' => $sid,//$this->getStatus($data['status']),
				//'buy' => $data['buy'],
				//'oppositebuy' => $data['oppositebuy']
			]
		]);	
		
		$rs['info'][0]['msg']='编辑成功';
		return $rs;
	}	
	
	/**
	 * 删除推单
	 * @desc 用于删除推单
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 删除成功提示信息 
	 * @return string msg 提示信息
	 */
	public function delTeidan() {
		$rs = array('code' => 0, 'msg' => '删除成功', 'info' => array());

        $liveuid = checkNull($this->liveuid);
        $uid = checkNull($this->uid);
		$token = checkNull($this->token);
		$tid = checkNull($this->tid);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		if($liveuid != $uid){
			$rs['code'] = 701;
			$rs['msg'] = '您不是该房间主播,无权删除推单！';
			return $rs;
		}  

		if(!anchorAuth($uid)){
			$rs['code'] = 1003;
			$rs['msg'] = '您不是主播,无权操作推单！';
			return $rs;
		}
        
		$domain=new Domain_Teidan();
		$info = $domain->delTeidan($liveuid,$tid);
	 
		if(!$info){
			$rs['code'] = 1001;
			$rs['msg'] = '删除失败';
			return $rs;
		}

		//发送推单socket通知
		global $socketSecretKey;
		sendDataToChatServer([
			'secretKey' => $socketSecretKey,
			'type' => 'sendTeidanMessage',
			'msg' => [
				'action' => 'delete',
				'id' => $tid,
				'liveuid' => $liveuid
			]
		]);	
		
		$rs['info'][0]['msg']='删除成功';
		return $rs;
	}	

    /**
     * 推单列表
     * @desc 用户获取推单列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 
     * @return int info.totalcount 总记录数	 
     * @return int info.num 每页显示记录数	 	 
     * @return int info[0].id 推单ID
     * @return int info[0].liveuid 主播ID
     * @return int info[0].type 推单分类
     * @return string info[0].title 推单标题
     * @return int info[0].buy 跟单数
     * @return int info[0].oppositebuy 反买数
     * @return int info[0].status 推单状态
     * @return string info[0].addtime 推单创建时间	 
     */
    public function teidanList(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());

        $liveuid = checkNull($this->liveuid);
        $uid = checkNull($this->uid);
		$token = checkNull($this->token);
		$cid = checkNull($this->cid);
		$sid = checkNull($this->sid);
        $p = checkNull($this->p);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}

		if($liveuid != $uid){
			$rs['code'] = 701;
			$rs['msg'] = '您不是该房间主播,无权查看！';
			return $rs;
		}

		if(!anchorAuth($uid)){
			$rs['code'] = 1003;
			$rs['msg'] = '您不是主播,无权操作推单！';
			return $rs;
		}
		
        $domain=new Domain_Teidan();
        $res=$domain->teidanList($liveuid,$cid,$sid,$p);

        $rs['info']=$res;

        return $rs;

    }
	
	/**
     * 推单状态列表
     * @desc 用于获取推单状态列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info
     * @return array info[0].sid 推单状态ID
     * @return array info[0].name 推单状态名称	 
     */
    
    public function teidanStatusList(){
         $rs = array('code' => 0, 'msg' => '', 'info' =>array());
         $domain=new Domain_Teidan();
         $res=$domain->teidanStatusList();

         $rs['info']=$res;

         return $rs;
    }
	
	/**
     * 推单分类列表
     * @desc 用于获取推单分类列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info
     * @return array info[0].cid 推单分类ID
     * @return array info[0].name 推单分类名称	 
     */
    
    public function teidanCatList(){
         $rs = array('code' => 0, 'msg' => '', 'info' =>array());
         $domain=new Domain_Teidan();
         $res=$domain->teidanCatList();

         $rs['info']=$res;

         return $rs;
    }
	

} 
