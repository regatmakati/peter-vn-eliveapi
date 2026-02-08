<?php
/**
 * 资讯类
 *
 * @author: sukura <zjl340823@gmail.com> 2022-06-21
 */

class Api_Article extends PhalApi_Api {

	public function getRules() {
        return array(
            'getArticleCat'=>array(),
			
            'getArticleList'=>array(
                'ishot'=>array('name'=>'ishot','type' => 'int','default'=>0,'desc' => '是否热门'),
				'isrecommend'=>array('name'=>'isrecommend','type' => 'int','default'=>0,'desc' => '是否推荐'),
				'ptype'=>array('name'=>'ptype','type' => 'int','default'=>0,'desc' => '父类ID'),
				'author'=>array('name'=>'author','type' => 'string','default'=>'','desc' => '文章作者'),
				'type'=>array('name'=>'type','type' => 'int','default'=>0,'desc' => '子类ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
				'nums' => array('name' => 'nums', 'type' => 'int', 'min' => 4, 'default'=>10,'desc' => '每页记录数'),
				'uid'=>array('name'=>'uid','type' => 'int','desc' => '用户ID'),
            ),
			
            'getArticleDetail'=>array(
                'id'=>array('name'=>'id','type' => 'int','require' => true,'desc' => '文章id'),
				'uid'=>array('name'=>'uid','type' => 'int','desc' => '用户ID'),
            ),
			
            'addLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'artid' => array('name' => 'artid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '文章ID'),
            ), 

            'addSubscribe' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'author' => array('name' => 'author', 'type' => 'string', 'require' => true, 'desc' => '文章作者'),
            ),	

            'collectArticle'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'artid'=>array('name'=>'artid','type' => 'int','require' => true,'desc' => '文章id'),
            ),	

            'setComment' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'artid' => array('name' => 'artid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '文章ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'default'=>0, 'desc' => '回复的评论UID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论commentid'),
                'parentid' => array('name' => 'parentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论ID'),
                'content' => array('name' => 'content', 'type' => 'string',  'default'=>'', 'desc' => '内容'),
                'at_info'=>array('name'=>'at_info','type'=>'string','desc'=>'被@的用户json信息'),
            ),	

			'addCommentLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论/回复 ID'),
            ),	

            'getComments' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'artid' => array('name' => 'artid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '文章ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
				'num' => array('name' => 'num', 'type' => 'int', 'min' => 1, 'default'=>5, 'desc' => '返回的记录数'),
            ),
			
			'getReplys' => array(
				'uid' => array('name' => 'uid', 'type' => 'int',  'require' => true, 'desc' => '用户ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
				'num' => array('name' => 'num', 'type' => 'int', 'min' => 1, 'default'=>5, 'desc' => '返回的记录数'),
            ),	

            'getRelatedArticles' => array(
                'artid' => array('name' => 'artid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '文章ID'),
                'num' => array('name' => 'num', 'type' => 'int', 'min' => 1, 'default'=>5, 'desc' => '返回的记录数'),
            ),			
            
        );
	}
	
    /**
     * 获取详情页内的相关文章列表
     * @desc 用于获取详情页内的相关文章列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     */
    public function getRelatedArticles(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $artid=checkNull($this->artid);
		$num=checkNull($this->num);
		
        $domain=new Domain_Article();
        $res=$domain->getRelatedArticles($artid,$num);
        if($res==0){
            $rs['code']=0;
            $rs['msg']="暂无相关文章";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;

    }	
	
	/**
     * 资讯文章评论列表
     * @desc 用于获取资讯文章评论列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].comments 评论总数
     * @return array info[0].commentlist 评论列表
     * @return object info[0].commentlist[].userinfo 用户信息
	 * @return string info[0].commentlist[].datetime 格式后的时间差
	 * @return string info[0].commentlist[].replys 回复总数
	 * @return string info[0].commentlist[].likes 点赞数
	 * @return string info[0].commentlist[].islike 是否点赞
	 * @return array info[0].commentlist[].replylist 回复列表
     * @return string msg 提示信息
     */
	public function getComments() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBan=isBan($this->uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Article();
        $rs['info'][0] = $domain->getComments($this->uid,$this->artid,$this->p,$this->num);

        return $rs;
    }	
	
	/**
     * 回复列表
     * @desc 用于获取资讯文章评论回复列表
     * @return int code 操作码，0表示成功
     * @return array info 评论列表
     * @return object info[].userinfo 用户信息
	 * @return string info[].datetime 格式后的时间差
	 * @return object info[].tocommentinfo 回复的评论的信息
	 * @return object info[].tocommentinfo.content 评论内容
	 * @return string info[].likes 点赞数
	 * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getReplys() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBan=isBan($this->uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Article();
        $rs['info'] = $domain->getReplys($this->uid,$this->commentid,$this->p,$this->num);

        return $rs;
    }		

   	/**
     * 评论/回复 点赞
     * @desc 用于评论/回复 点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addCommentLike() {
        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());

        $uid=$this->uid;
        $token=checkNull($this->token);
        $commentid=$this->commentid;

        $isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}

        $domain = new Domain_Article();
         $res= $domain->addCommentLike($uid,$commentid);
         if($res==1001){
         	$rs['code']=1001;
         	$rs['msg']='评论信息不存在';
         	return $rs;
         }
         $rs['info'][0]=$res;

        return $rs;
    }	
	
   	/**
     * 评论/回复
     * @desc 用于用户评论/回复 别人文章
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return int info[0].isattent 对方是否关注我
     * @return int info[0].u2t 我是否拉黑对方
     * @return int info[0].t2u 对方是否拉黑我
     * @return int info[0].comments 评论总数
     * @return int info[0].replys 回复总数
     * @return string msg 提示信息
     */
	public function setComment() {
        $rs = array('code' => 0, 'msg' => '评论成功', 'info' => array());
		
		$uid=$this->uid;
		$token=checkNull($this->token);
		$touid=$this->touid;
		$artid=$this->artid;
		$commentid=$this->commentid;
		$parentid=$this->parentid;
		$content=checkNull($this->content);
		$at_info=$this->at_info;

		//$arr = json_decode($at_info,true);
		if(!$at_info){
			$at_info='';
		}
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		$sensitivewords=sensitiveField($content);
		if($sensitivewords==1001){
			$rs['code'] = 10011;
			$rs['msg'] = '输入非法，请重新输入';
			return $rs;
        }
		
		if($commentid==0 && $commentid!=$parentid){
			$commentid=$parentid;
		}
		
		$data=array(
			'uid'=>$uid,
			'touid'=>$touid,
			'artid'=>$artid,
			'commentid'=>$commentid,
			'parentid'=>$parentid,
			'content'=>$content,
			'addtime'=>time(),
			'at_info'=>$at_info
		);

		/*var_dump($data);
		die;*/

        $domain = new Domain_Article();
        $result = $domain->setComment($data);
		

		
		$info=array(
			'isattent'=>'0',
			'u2t'=>'0',
			't2u'=>'0',
			'comments'=>$result['comments'],
			'replys'=>$result['replys'],
		);
		if($touid>0){
			$isattent=isAttention($touid,$uid);
			$u2t = isBlack($uid,$touid);
			$t2u = isBlack($touid,$uid);
			
			$info['isattent']=(string)$isattent;
			$info['u2t']=(string)$u2t;
			$info['t2u']=(string)$t2u;
		}
		
		$rs['info'][0]=$info;
		
		if($parentid!=0){
			 $rs['msg']='回复成功';			
		}
        return $rs;
    }	

    /**
     * 收藏资讯文章/取消收藏
     * @desc 用于收藏资讯文章/取消收藏
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function collectArticle(){
       $rs = array('code' => 0, 'msg' => '', 'info' =>array());
       $uid=checkNull($this->uid);
       $token=checkNull($this->token);
       $artid=checkNull($this->artid);

       if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 10020;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }



        $domain=new Domain_Article();
        $res=$domain->collectArticle($uid,$artid);

        if($res==1001){
            $rs['code']=1001;
            $rs['msg']='该资讯文章已下架';
            return $rs;
        }

        if($res==200){
            $rs['msg']="取消收藏成功";
            $rs['info'][0]['iscollect']=0;
            return $rs;
        }

        if($res==300){
            $rs['msg']="收藏成功";
            $rs['info'][0]['iscollect']=1;
            return $rs;
        }

        if($res==301){
            $rs['code']=1002;
            $rs['msg']="收藏失败";
            return $rs;
        }


    }	

  	/**
     * 关注
     * @desc 用于关注文章作者
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].issubscribe 是否关注 
     * @return string msg 提示信息
     */
	public function addSubscribe() {
        $rs = array('code' => 0, 'msg' => '关注成功', 'info' => array());
        $uid=$this->uid;
        $token=checkNull($this->token);
        $author=$this->author;
		
		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
        $domain = new Domain_Article();
        $result = $domain->addSubscribe($uid,$author);
		
		$rs['info'][0]=$result;
        return $rs;
    }	
	
	
   	/**
     * 点赞
     * @desc 用于文章点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addLike() {
        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());
        $uid=$this->uid;
        $token=checkNull($this->token);
        $artid=$this->artid;
		$isBan=isBan($uid);
		 if($isBan=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
        $domain = new Domain_Article();
        $result = $domain->addLike($uid,$artid);
		if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = "文章已删除";
			return $rs;
		}
		
		$rs['info'][0]=$result;
        return $rs;
    }	
	
	
	/**
     * 资讯分类列表
     * @desc 用于获取资讯分类列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info
	 * @return string info[0].ptype 一级分类ID
	 * @return string info[0].type 二级分类ID	 
     * @return string info[0].name 分类名称
     * @return string info[0].thumb 分类图标地址
     */
    
    public function getArticleCat(){
         $rs = array('code' => 0, 'msg' => '', 'info' =>array());
         $domain=new Domain_Article();
         $res=$domain->getArticleCat();

         $rs['info']=$res;

         return $rs;
    }

    /**
     * 获取资讯文章列表
     * @desc 用于获取资讯文章列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     */
    public function getArticleList(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $ishot=checkNull($this->ishot);
		$isrecommend=checkNull($this->isrecommend);
		$ptype=checkNull($this->ptype);
		$author=checkNull($this->author);
		$type=checkNull($this->type);
        $p=checkNull($this->p);
		$nums=checkNull($this->nums);
		$uid=checkNull($this->uid);
		
        $domain=new Domain_Article();
        $res=$domain->getArticleList($ishot,$isrecommend,$ptype,$author,$type,$p,$nums,$uid);
        if($res==0){
            $rs['code']=0;
            $rs['msg']="暂无资讯文章";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;

    }
	
    /**
     * 获取文章内容
     * @desc 用于获取文章内容
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     * @return string info[0].id 文章ID	 
     * @return string info[0].title 文章标题
     * @return string info[0].author 作者
     * @return string info[0].thumb 缩略图
     * @return string info[0].content 文章内容
     * @return string info[0].publishtime 发布日期
     */
    public function getArticleDetail(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());

        $id=checkNull($this->id);
		$uid=checkNull($this->uid);
		
        $domain=new Domain_Article();
        $res=$domain->getArticleDetail($id,$uid);

        if($res==1001){
            $rs['code']=0;
            $rs['msg']="查无文章";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;
    }

	
} 
