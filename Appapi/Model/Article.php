<?php

class Model_Article extends PhalApi_Model_NotORM {

	/*获取详情页内的相关文章列表*/
	public function getRelatedArticles($artid,$num){
		$one = DI()->notorm->notice->select("*")->where("id='$artid'")->fetchOne();
		if($one){
			$where = "type!=0 and id!='$artid'";		
			$type = $one['type'];
			if($type){
				$where .= " and type='$type'";
			}			
			
			$list=DI()->notorm->notice->select("id,title,thumb,views,likes,comments,author,publishtime")->where($where)->order("istop desc,publishtime desc")->limit(0,$num)->fetchAll();
			if($list){
				return $list;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	/* 评论列表 */
	public function getComments($uid,$artid,$p,$num){
        if($p<1){
            $p=1;
        }

		$start=($p-1)*$num;
		$comments=DI()->notorm->article_comments
					->select("*")
					->where("artid='{$artid}' and parentid='0'")
					->order("addtime desc")
					->limit($start,$num)
					->fetchAll();
		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=getUserInfo($v['uid']);				
			$comments[$k]['datetime']=datetime($v['addtime']);	
			$comments[$k]['likes']=NumberFormat($v['likes']);	
			if($uid){
				$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);	
			}else{
				$comments[$k]['islike']='0';	
			}
			
			if($v['touid']>0){
				$touserinfo=getUserInfo($v['touid']);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			$comments[$k]['touserinfo']=$touserinfo;

			$count=DI()->notorm->article_comments
					->where("commentid='{$v['id']}'")
					->count();
			$comments[$k]['replys']=$count;
            
            /* 回复 */
            $reply=DI()->notorm->article_comments
					->select("*")
					->where("commentid='{$v['id']}'")
					->order("addtime desc")
					->limit(0,1)
					->fetchAll();
            foreach($reply as $k1=>$v1){
                
                $v1['userinfo']=getUserInfo($v1['uid']);				
                $v1['datetime']=datetime($v1['addtime']);	
                $v1['likes']=NumberFormat($v1['likes']);	
                $v1['islike']=(string)$this->ifCommentLike($uid,$v1['id']);
                if($v1['touid']>0){
                    $touserinfo=getUserInfo($v1['touid']);
                }
                if(!$touserinfo){
                    $touserinfo=(object)array();
                    $v1['touid']='0';
                }
                
                if($v1['parentid']>0 && $v1['parentid']!=$v['id']){
                    $tocommentinfo=DI()->notorm->article_comments
                        ->select("content,at_info")
                        ->where("id='{$v1['parentid']}'")
                        ->fetchOne();
                }else{
                    $tocommentinfo=(object)array();
                    $touserinfo=(object)array();
                    $v1['touid']='0';
                }
                $v1['touserinfo']=$touserinfo;
                $v1['tocommentinfo']=$tocommentinfo;


                $reply[$k1]=$v1;
            }
            
            $comments[$k]['replylist']=$reply;
		}
		
		$commentnum=DI()->notorm->article_comments
					->where("artid='{$artid}'")
					->count();
		
		$rs=array(
			"comments"=>$commentnum,
			"commentlist"=>$comments,
		);
		
		return $rs;
	}

	/* 回复列表 */
	public function getReplys($uid,$commentid,$p,$num){
        if($p<1){
            $p=1;
        }
		$start=($p-1)*$num;
		$comments=DI()->notorm->article_comments
					->select("*")
					->where("commentid='{$commentid}'")
					->order("addtime desc")
					->limit($start,$num)
					->fetchAll();


		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=getUserInfo($v['uid']);				
			$comments[$k]['datetime']=datetime($v['addtime']);	
			$comments[$k]['likes']=NumberFormat($v['likes']);	
			$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);
			if($v['touid']>0){
				$touserinfo=getUserInfo($v['touid']);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			


			if($v['parentid']>0 && $v['parentid']!=$commentid){
				$tocommentinfo=DI()->notorm->article_comments
					->select("content,at_info")
					->where("id='{$v['parentid']}'")
					->fetchOne();
			}else{

				$tocommentinfo=(object)array();
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';

			}
			$comments[$k]['touserinfo']=$touserinfo;
			$comments[$k]['tocommentinfo']=$tocommentinfo;
		}
		
		return $comments;
	}
	
	
	/* 评论/回复 点赞 */
	public function addCommentLike($uid,$commentid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);

		//根据commentid获取对应的评论信息
		$commentinfo=DI()->notorm->article_comments
			->where("id='{$commentid}'")
			->fetchOne();

		if(!$commentinfo){
			return 1001;
		}

		$like=DI()->notorm->article_comments_like
			->select("id")
			->where("uid='{$uid}' and commentid='{$commentid}'")
			->fetchOne();

		if($like){
			DI()->notorm->article_comments_like
						->where("uid='{$uid}' and commentid='{$commentid}'")
						->delete();
			
			DI()->notorm->article_comments
				->where("id = '{$commentid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';

		}else{
			DI()->notorm->article_comments_like
						->insert(array("uid"=>$uid,"commentid"=>$commentid,"addtime"=>time(),"touid"=>$commentinfo['uid'],"artid"=>$commentinfo['artid'] ));
			
			DI()->notorm->article_comments
				->where("id = '{$commentid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$notice=DI()->notorm->article_comments
				->select("likes")
				->where("id = '{$commentid}'")
				->fetchOne();

		$rs['likes']=$notice['likes'];

		return $rs; 		
	}
	
	
	/* 评论/回复 */
    public function setComment($data) {
    	$artid=$data['artid'];

		/* 更新 文章 */
		DI()->notorm->notice
            ->where("id = '{$artid}'")
		 	->update( array('comments' => new NotORM_Literal("comments + 1") ) );
		
        DI()->notorm->article_comments
            ->insert($data);
			
		$artinfo=DI()->notorm->notice
					->select("comments")
					->where('id=?',$artid)
					->fetchOne();
					
		$count=DI()->notorm->article_comments
					->where("commentid='{$data['commentid']}'")
					->count();
		$rs=array(
			'comments'=>$artinfo['comments'],
			'replys'=>$count,
		);

		return $rs;	
    }
	
	/*收藏/取消收藏资讯文章*/
	public function collectArticle($uid,$artid){

		//判断资讯文章是否存在
		$info=DI()->notorm->notice->select("*")->where("id=?",$artid)->fetchOne();
		if(!$info){
			return 1001;
		}

		//判断用户是否收藏过该文章
		$isexist=DI()->notorm->article_collection->select("*")->where("uid='{$uid}' and artid='{$artid}'")->fetchOne();
		//已经收藏过
		if($isexist){
			DI()->notorm->article_collection
						->where("uid='{$uid}' and artid='{$artid}'")
						->delete();
			return 200;			
		}else{
			//向收藏表中写入记录
			$data=array("uid"=>$uid,"artid"=>$artid,'addtime'=>time());
			$result=DI()->notorm->article_collection->insert($data);
			if($result!==false){
				return 300;
			}else{
				return 301;
			}
		}

	}
	
	/* 关注 */
	public function addSubscribe($uid,$author){
		$rs=array(
			'issubscribe'=>'0',
		);

		$subscribe=DI()->notorm->article_author_subscribe
						->select("id")
						->where("uid='{$uid}' and author='{$author}'")
						->fetchOne();
		if($subscribe){
			DI()->notorm->article_author_subscribe
						->where("uid='{$uid}' and author='{$author}'")
						->delete();
			$rs['issubscribe']='0';
		}else{
			DI()->notorm->article_author_subscribe
						->insert(array("uid"=>$uid,"author"=>$author,"addtime"=>time() ));
			$rs['issubscribe']='1';
		}	
		
		$subscribes=DI()->notorm->article_author_subscribe
				->select("*")
				->where("author = '{$author}'")
				->count();
				
		$rs['subscribes']=$subscribes;
		
		return $rs; 		
	}	
	
	/* 点赞 */
	public function addLike($uid,$artid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);
		$article = DI()->notorm->notice
				->select("*")
				->where("id = '{$artid}'")
				->fetchOne();

		if(!$article){
			return 1001;
		}

		$like=DI()->notorm->article_like
						->select("id")
						->where("uid='{$uid}' and artid='{$artid}'")
						->fetchOne();
		if($like){
			DI()->notorm->article_like
						->where("uid='{$uid}' and artid='{$artid}'")
						->delete();
			
			DI()->notorm->notice
				->where("id = '{$artid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';
		}else{
			DI()->notorm->article_like
						->insert(array("uid"=>$uid,"artid"=>$artid,"addtime"=>time() ));
			
			DI()->notorm->notice
				->where("id = '{$artid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$notice=DI()->notorm->notice
				->select("likes")
				->where("id = '{$artid}'")
				->fetchOne();
				
		$rs['likes']=$notice['likes'];
		
		return $rs; 		
	}	

	/*获取分类列表*/
	public function getArticleCat(){
        $list=DI()->notorm->article_class->select("*")->order("list_order desc")->fetchAll();

		return $list;
	}

	/*获取资讯文章列表*/
	public function getArticleList($ishot,$isrecommend,$ptype,$author,$type,$p,$nums,$uid){
        if($p<1){
            $p=1;
        }
		
		$start=($p-1)*$nums;

		$where = "type!=0";
		if($ishot){
			$where .= " and ishot>0";
		}
		if($isrecommend){
			$where .= " and isrecommend>0";
		}		
		if($ptype){
			$where .= " and ptype='$ptype'";
		}
		if($author){
			$where .= " and author='$author'";
		}		
		if($type){
			$where .= " and type='$type'";
		}		
		$list=DI()->notorm->notice->select("id,title,thumb,views,likes,comments,author,publishtime,istop")->where($where)->order("istop desc,publishtime desc")->limit($start,$nums)->fetchAll();
		foreach($list as $key=>$val){
			if($uid){
				$list[$key]['islike']=(string)$this->ifArticleLike($uid,$val['id']);
			}else{
				$list[$key]['islike']=0;
			}
			
		}
		

		if(!$list){
			return [];
		}

		return $list;
	}


	/*获取文章内容*/
	public function getArticleDetail($id,$uid){

		$one = DI()->notorm->notice->select("*")->where("id='$id'")->fetchOne();

		if(!$one){
			return 1001;
		}
		$one['content'] = htmlspecialchars_decode($one['content']);
		
		DI()->notorm->notice
				->where("id = '{$id}'")
				->update( array('views' => new NotORM_Literal("views + 1") ) );	
		//作品数量
		$author = $one['author'];	
		$artid = $one['id'];
		
		$one['articlenum'] = DI()->notorm->notice->where("author = '{$author}'")->count();
		
		//fans数量
		$one['fansnum'] = DI()->notorm->article_author_subscribe->where("author = '{$author}'")->count();

		//判断是否点赞		
		$like=DI()->notorm->article_like
			->select("id")
			->where("uid='{$uid}' and artid='{$id}'")
			->fetchOne();
		if($like){
			$one['islike'] = 1;
		}else{
			$one['islike'] = 0;
		}
		
						
		//判断是否已关注
		$isfans = DI()->notorm->article_author_subscribe->where("author = '{$author}' and uid='$uid'")->fetchOne();
		if($isfans){
			$one['isfans'] = 1;
		}else{
			$one['isfans'] = 0;
		}

		//判断是否已收藏
		$iscollection = DI()->notorm->article_collection->where("artid='{$artid}' and uid='$uid'")->fetchOne();
		if($iscollection){
			$one['iscollection'] = 1;
		}else{
			$one['iscollection'] = 0;
		}
		
		//上一篇
		$sid = DI()->notorm->notice->where("id < $artid")->max('id');
		if($sid){
			$one['uparticle'] = DI()->notorm->notice->select("id,title,thumb")->where("id='$sid'")->fetchOne();
		}else{
			$one['uparticle'] = [];
		}
		//下一篇
		$xid = DI()->notorm->notice->where("id > $artid")->min('id');
		if($xid){
			$one['downarticle'] = DI()->notorm->notice->select("id,title,thumb")->where("id='$xid'")->fetchOne();
		}else{
			$one['downarticle'] = [];
		}		
		
		return $one;

	}
	
	/* 评论/回复 是否点赞 */
	public function ifCommentLike($uid,$commentid){
		$like=DI()->notorm->article_comments_like
				->select("id")
				->where("uid='{$uid}' and commentid='{$commentid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}	

	/* 评论/回复 是否点赞 */
	public function ifArticleLike($uid,$artid){
		$like=DI()->notorm->article_like
				->select("id")
				->where("uid='{$uid}' and artid='{$artid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}	
	
}
