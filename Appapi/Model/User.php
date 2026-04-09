<?php

class Model_User extends PhalApi_Model_NotORM {

	/* 用户订单记录 */
	public function getOrderrecord($uid,$p){
		if($p<1){
            $p=1;
        }
		$pnum=10;
		$start=($p-1)*$pnum;

		$rs=array();
		$rs=DI()->notorm->shop_order
				->select("id,orderno,goods_name,coin,express_name,express_number,platform_remark,addtime,status")
				->where('uid=?',$uid)
				->order("addtime desc")
				->limit($start,$pnum)
				->fetchAll();
				
		foreach($rs as $k=>$v){
			$rs[$k]['status'] = $this->getStatus($v['status']);
			$rs[$k]['addtime'] = date('Y/m/d H:i',$v['addtime']); 
		}		
		
		return $rs;
	}
	
	/* 用户金币记录 */
	public function getCoinrecord($uid,$p){
		if($p<1){
            $p=1;
        }
		$pnum=10;
		$start=($p-1)*$pnum;

		$rs=array();
		$rs=DI()->notorm->user_coinrecord
				->select("*")
				->where('uid=?',$uid)
				->order("addtime desc")
				->limit($start,$pnum)
				->fetchAll();
				
		foreach($rs as $k=>$v){
			if($v['type']==0){
				$rs[$k]['type'] = '消费';
			}else{
				$rs[$k]['type'] = '获取';
			}
			$rs[$k]['action'] = '已通过'.$this->getAction($v['action']).$rs[$k]['type'].$v['totalcoin'].'个金币';
			$rs[$k]['addtime'] = date('Y/m/d H:i',$v['addtime']); 
		}		
		
		return $rs;
	}
	
	/* 用户全部信息 */
	public function getBaseInfo($uid) {
		$info=DI()->notorm->user
				->select("id,user_login,user_nicename,avatar,avatar_thumb,sex,signature,coin,votes,consumption,votestotal,province,city,birthday,location")
				->where('id=?  and user_type="2"',$uid)
				->fetchOne();
        if($info){
            $info['avatar']=get_upload_path($info['avatar']);
            $info['avatar_thumb']=get_upload_path($info['avatar_thumb']);						
            $info['level']=getLevel($info['consumption']);
            $info['level_anchor']=getLevelAnchor($info['votestotal']);
            $info['lives']=getLives($uid);
            $info['follows']=getFollows($uid);
            $info['fans']=getFans($uid);
            $info['subscribeNum']=subscribeNum($uid);

            $info['vip']=getUserVip($uid);
            $info['liang']=getUserLiang($uid);       
            
            if($info['birthday']){
                $info['birthday']=date('Y-m-d',$info['birthday']);   
            }else{
                $info['birthday']='';
            }
        }

					
		return $info;
	}

    public function getUser($uid) {
        return DI()->notorm->user
            ->select("*")
            ->where('id=?',$uid)
            ->fetchOne();
    }
			
	/* 判断昵称是否重复 */
	public function checkName($uid,$name){
		$isexist=DI()->notorm->user
					->select('id')
					->where('id!=? and user_nicename=?',$uid,$name)
					->fetchOne();
		if($isexist){
			return 0;
		}else{
			return 1;
		}
	}
	
	/* 修改信息 */
	public function userUpdate($uid,$fields){
		/* 清除缓存 */
		delCache("userinfo_".$uid);
        
        if(!$fields){
            return false;
        }

		return DI()->notorm->user
					->where('id=?',$uid)
					->update($fields);
	}

	/* 修改密码 */
	public function updatePass($uid,$oldpass,$pass){
		$userinfo=DI()->notorm->user
					->select("user_pass")
					->where('id=?',$uid)
					->fetchOne();
		$oldpass=setPass($oldpass);							
		if($userinfo['user_pass']!=$oldpass){
			return 1003;
		}
		$newpass=setPass($pass);
        if($oldpass == $newpass){
            return 1004;
        }
            return DI()->notorm->user
					->where('id=?',$uid)
					->update( array( "user_pass"=>$newpass ) );
	}
	
	/* 我的钻石 */
	public function getBalance($uid){
		$profit = DI()->notorm->agent_profit
				->select("*")
				->where('uid=?',$uid)
				->fetchOne();
		$user_profit = 	isset($profit['one_profit'])?$profit['one_profit']:0;
		$userinfo =  DI()->notorm->user
				->select("coin,score")
				->where('id=?',$uid)
				->fetchOne();
		$userinfo['invite_profit'] = $user_profit;
		
		return $userinfo;
	}
	
	/* 充值规则 */
	public function getChargeRules(){

		$rules= DI()->notorm->charge_rules
				->select('id,coin,coin_ios,money,product_id,give')
				->order('list_order asc')
				->fetchAll();

		return 	$rules;
	}
    
	/* 我的收益 */
	public function getProfit($uid){
		$info= DI()->notorm->user
				->select("votes,votestotal")
				->where('id=?',$uid)
				->fetchOne();

		$config=getConfigPri();
		
		//提现比例
		$cash_rate=$config['cash_rate'];
        $cash_start=$config['cash_start'];
		$cash_end=$config['cash_end'];
		$cash_max_times=$config['cash_max_times'];
		//剩余票数
		$votes=$info['votes'];
        
		//总可提现数
		$total=(string)floor($votes/$cash_rate);

        if($cash_max_times){
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放，每月只可提现'.$cash_max_times.'次';
            $tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，每月只可提现'.$cash_max_times.'次';
        }else{
            //$tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请，收益将在'.($cash_end+1).'-'.($cash_end+5).'号统一发放';
            $tips='每月'.$cash_start.'-'.$cash_end.'号可进行提现申请';
        }
        
		$rs=array(
			"votes"=>$votes,
			"votestotal"=>$info['votestotal'],
			"total"=>$total,
			"cash_rate"=>$cash_rate,
			"tips"=>$tips,
		);
		return $rs;
	}	
	/* 提现  */
	public function setCash($data){
        
        $nowtime=time();
        
        $uid=$data['uid'];
        $accountid=$data['accountid'];
        $cashvote=$data['cashvote'];
        
        $config=getConfigPri();
        $cash_start=$config['cash_start'];
        $cash_end=$config['cash_end'];
        $cash_max_times=$config['cash_max_times'];
        
        $day=(int)date("d",$nowtime);
        
        if($day < $cash_start || $day > $cash_end){
            return 1005;
        }
        
        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");
        
        if($cash_max_times){
            $isexist=DI()->notorm->cash_record
                    ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                    ->count();
            if($isexist >= $cash_max_times){
                return 1006;
            }
        }
        
		$isrz=DI()->notorm->user_auth
				->select("status")
				->where('uid=?',$uid)
				->fetchOne();
		if(!$isrz || $isrz['status']!=1){
			return 1003;
		}
        
        /* 钱包信息 */
		$accountinfo=DI()->notorm->cash_account
				->select("*")
				->where('id=? and uid=?',$accountid,$uid)
				->fetchOne();

        if(!$accountinfo){

            return 1007;
        }
        

		//提现比例
		$cash_rate=$config['cash_rate'];
		/* 最低额度 */
		$cash_min=$config['cash_min'];
		
		//提现钱数
        $money=floor($cashvote/$cash_rate);
		
		if($money < $cash_min){
			return 1004;
		}
		
		$cashvotes=$money*$cash_rate;
        
        
        $ifok=DI()->notorm->user
            ->where('id = ? and votes>=?', $uid,$cashvotes)
            ->update(array('votes' => new NotORM_Literal("votes - {$cashvotes}")) );
        if(!$ifok){
            return 1001;
        }
		
		
		
		$data=array(
			"uid"=>$uid,
			"money"=>$money,
			"votes"=>$cashvotes,
			"orderno"=>$uid.'_'.$nowtime.rand(100,999),
			"status"=>0,
			"addtime"=>$nowtime,
			"uptime"=>$nowtime,
			"type"=>$accountinfo['type'],
			"account_bank"=>$accountinfo['account_bank'],
			"account"=>$accountinfo['account'],
			"name"=>$accountinfo['name'],
		);
		
		$rs=DI()->notorm->cash_record->insert($data);
		if(!$rs){
            return 1002;
		}	        
            
        
						
		
		return $rs;
	}
	
	/* 关注 */
	public function setAttent($uid,$touid){
		$isexist=DI()->notorm->user_attention
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			DI()->notorm->user_attention
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			return 0;
		}else{
			DI()->notorm->user_black
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->notorm->user_attention
				->insert(array("uid"=>$uid,"touid"=>$touid));
			return 1;
		}			 
	}	
	
	/* 拉黑 */
	public function setBlack($uid,$touid){
		$isexist=DI()->notorm->user_black
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			DI()->notorm->user_black
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			return 0;
		}else{
			DI()->notorm->user_attention
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->notorm->user_black
				->insert(array("uid"=>$uid,"touid"=>$touid));

			return 1;
		}			 
	}
	
	/* 关注列表 */
	public function getFollowsList($uid,$touid,$p){
        if($p<1){
            $p=1;
        }

        $attentionNum = $this->attentionNum();

		$pnum=50;
		$start=($p-1)*$pnum;
		$touids=DI()->notorm->user_attention
					->select("touid")
					->where('uid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();
		foreach($touids as $k=>$v){
			$userinfo=getUserInfo($v['touid']);
			if($userinfo){
				if($uid==$touid){
					$isattent='1';
				}else{
					$isattent=isAttention($uid,$v['touid']);
				}
				$userinfo['isattention']=$isattent;
				// 粉丝量 、 关注量
                $fans = $attentionNum['fans'];
                $attention = $attentionNum['attention'];
                $userinfo['fans'] = $fans[$v['touid']] ?? 0;
                $userinfo['attention'] = $attention[$v['touid']] ?? 0;
                $touids[$k]=$userinfo;
			}else{
				DI()->notorm->user_attention->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
				unset($touids[$k]);
			}
		}		
		$touids=array_values($touids);
		return $touids;
	}

	/* 关注量 、 粉丝量 */
	public function attentionNum()
    {
        // 关注量
        $attention = DI()->notorm->user_attention
            ->select("count(uid) as counts, uid")
            ->group('uid')
            ->fetchAll();
        $attentionArr = [];
        foreach ($attention as $k => $v) {
            $attentionArr[$v['uid']] = $v['counts'];
        }
        // 粉丝量
        $fans = DI()->notorm->user_attention
            ->select("count(touid) as counts, touid")
            ->group('touid')
            ->fetchAll();
        $fansArr = [];
        foreach ($fans as $k => $v) {
            $fansArr[$v['touid']] = $v['counts'];
        }

        return ['attention' => $attentionArr, 'fans' => $fansArr];
    }


	/* 粉丝列表 */
	public function getFansList($uid,$touid,$p){
        if($p<1){
            $p=1;
        }
		$pnum=50;
		$start=($p-1)*$pnum;
		$touids=DI()->notorm->user_attention
					->select("uid")
					->where('touid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();
		foreach($touids as $k=>$v){
			$userinfo=getUserInfo($v['uid']);
			if($userinfo){
				$userinfo['isattention']=isAttention($uid,$v['uid']);
				$touids[$k]=$userinfo;
			}else{
				DI()->notorm->user_attention->where('uid=? or touid=?',$v['uid'],$v['uid'])->delete();
				unset($touids[$k]);
			}
			
		}		
		$touids=array_values($touids);
		return $touids;
	}


    /* PC关注列表 */
    public function getPCFollowsList($uid,$p,$limit){
        if($p<1){
            $p=1;
        }

        // 粉丝量 、 关注量
        $attentionNum = $this->attentionNum();
        $fans = $attentionNum['fans'];
        $attention = $attentionNum['attention'];

        $pnum=$limit?$limit:50;
        $start=($p-1)*$pnum;
        $touids=DI()->notorm->user_attention
            ->select("touid")
            ->where('uid=?',$uid)
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($touids as $k=>$v){
            $userinfo=getUserInfo($v['touid']);
            if($userinfo){
                if($uid == $v['touid']){
                    $isattent='1';
                }else{
                    $isattent=isAttention($uid,$v['touid']);
                }
                $userinfo['isattention']=$isattent;
                $userinfo['fans'] = $fans[$v['touid']] ?? 0;
                $userinfo['attention'] = $attention[$v['touid']] ?? 0;
                $touids[$k]=$userinfo;
            }else{
                DI()->notorm->user_attention->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
                unset($touids[$k]);
            }
        }
        $touids=array_values($touids);
        return $touids;
    }

    /* PC关注数量 */
    public function getPCFollowsCount($uid){
        $count=DI()->notorm->user_attention
            ->where('uid=?',$uid)
            ->count();

        return $count;
    }

    /* PC粉丝列表 */
    public function getPCFansList($uid,$p,$limit){
        if($p<1){
            $p=1;
        }
        $pnum=$limit?$limit:50;
        $start=($p-1)*$pnum;

        // 粉丝量 、 关注量
        $attentionNum = $this->attentionNum();
        $fans = $attentionNum['fans'];
        $attention = $attentionNum['attention'];

        $touids=DI()->notorm->user_attention
            ->select("uid")
            ->where('touid=?',$uid)
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($touids as $k=>$v){
            $userinfo=getUserInfo($v['uid']);
            if($userinfo){
                $userinfo['isattention']=isAttention($uid,$v['uid']);
                $userinfo['is_subscribe'] = isSubscribe($uid,$v['uid']);
                $userinfo['fans'] = $fans[$v['uid']] ?? 0;
                $userinfo['attention'] = $attention[$v['uid']] ?? 0;
                $touids[$k]=$userinfo;
            }else{
                DI()->notorm->user_attention->where('uid=? or touid=?',$v['uid'],$v['uid'])->delete();
                unset($touids[$k]);
            }

        }
        $touids=array_values($touids);
        return $touids;
    }
    /* PC粉丝数量 */
    public function getPCFansCount($uid){
        $count=DI()->notorm->user_attention
            ->where('touid=?',$uid)
            ->count();
        return $count;
    }

    /* 黑名单列表 */
	public function getBlackList($uid,$touid,$p){
        if($p<1){
            $p=1;
        }
		$pnum=50;
		$start=($p-1)*$pnum;
		$touids=DI()->notorm->user_black
					->select("touid")
					->where('uid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();
		foreach($touids as $k=>$v){
			$userinfo=getUserInfo($v['touid']);
			if($userinfo){
				$touids[$k]=$userinfo;
			}else{
				DI()->notorm->user_black->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
				unset($touids[$k]);
			}
		}
		$touids=array_values($touids);
		return $touids;
	}
	
	/* 直播记录 */
	public function getLiverecord($touid,$p){
        if($p<1){
            $p=1;
        }
		$pnum=50;
		$start=($p-1)*$pnum;
		$record=DI()->notorm->live_record
					->select("id,uid,nums,starttime,endtime,title,city")
					->where('uid=?',$touid)
					->order("id desc")
					->limit($start,$pnum)
					->fetchAll();
		foreach($record as $k=>$v){
			$record[$k]['datestarttime']=date("Y.m.d",$v['starttime']);
			$record[$k]['dateendtime']=date("Y.m.d",$v['endtime']);
            $cha=$v['endtime']-$v['starttime'];
			$record[$k]['length']=getSeconds($cha);
		}						
		return $record;						
	}

    /* 直播记录 */
    public function getPCLiverecord($param, $uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;

        $starttime = $param['starttime'] ?? strtotime(date('Y-m-d 00:00:00'));
        $endtime   = $param['endtime'] ?? time();
        $where = 'uid=? ';
        $where .= 'and starttime>=? and starttime<=? ';

        $record=DI()->notorm->live_record
            ->select("id,uid,nums,starttime,endtime,title,city")
            ->where($where,$uid,$starttime,$endtime)
            ->order("id desc")
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($record as $k=>$v){
            $record[$k]['datestarttime']=date("Y.m.d",$v['starttime']);
            $record[$k]['dateendtime']=date("Y.m.d",$v['endtime']);
            $cha=$v['endtime']-$v['starttime'];
            $record[$k]['length']=getSeconds($cha);
        }
        return $record;
    }

    /* 个人主页 */
	public function getUserHome($uid,$touid){
		$info=getUserInfo($touid);

		$user_status=$info['user_status'];


		$info['follows']=(string)getFollows($touid);
		$info['fans']=(string)getFans($touid);
		$info['isattention']=(string)isAttention($uid,$touid);
		$info['isblack']=(string)isBlack($uid,$touid);
		$info['isblack2']=(string)isBlack($touid,$uid);
        
        /* 直播状态 */
        $islive='0';
        $isexist=DI()->notorm->live
                    ->select('uid')
					->where('uid=? and islive=1',$touid)
					->fetchOne();
        if($isexist){
            $islive='1';
        }
		$info['islive']=$islive;	        
		
		/* 贡献榜前三 */
		$rs=array();
		$rs=DI()->notorm->user_coinrecord
				->select("uid,sum(totalcoin) as total")
				->where('action=1 and touid=?',$touid)
				->group("uid")
				->order("total desc")
				->limit(0,3)
				->fetchAll();
		foreach($rs as $k=>$v){
			$userinfo=getUserInfo($v['uid']);
			$rs[$k]['avatar']=$userinfo['avatar'];
		}		
		$info['contribute']=$rs;	
		
        /* 视频数 */

		if($uid==$touid){  //自己的视频（需要返回视频的状态前台显示）
			$where=" uid={$uid} and isdel='0' and status=1  and is_ad=0";
		}else{  //访问其他人的主页视频
//            $videoids_s=getVideoBlack($uid);
			$where=" uid={$touid} and isdel='0' and status=1  and is_ad=0";
		}
        
		$videonums=DI()->notorm->video
				->where($where)
				->count();
        if(!$videonums){
            $videonums=0;
        }

        $info['videonums']=(string)$videonums;
		  /* 动态数 */

		if($uid==$touid){  //自己的动态（需要返回动态的状态前台显示）
			$whered=" uid={$uid} and isdel='0' and status=1";
		}else{  //访问其他人的主页动态
			$whered=" uid={$touid} and isdel='0' and status=1  ";
		}
        
		$dynamicnums=DI()->notorm->dynamic
				->where($whered)
				->count();
        if(!$dynamicnums){
            $dynamicnums=0;
        }

        $info['dynamicnums']=(string)$dynamicnums;
        /* 直播数 */
        $livenums=DI()->notorm->live_record
					->where('uid=?',$touid)
					->count();
                    
        $info['livenums']=$livenums;        
		/* 直播记录 */
		$record=array();
		$record=DI()->notorm->live_record
					->select("id,uid,nums,starttime,endtime,title,city")
					->where('uid=?',$touid)
					->order("id desc")
					->limit(0,50)
					->fetchAll();
		foreach($record as $k=>$v){
			$record[$k]['datestarttime']=date("Y.m.d",$v['starttime']);
			$record[$k]['dateendtime']=date("Y.m.d",$v['endtime']);
            $cha=$v['endtime']-$v['starttime'];
            $record[$k]['length']=getSeconds($cha);
		}		
		$info['liverecord']=$record;	
		return $info;
	}
	
	/* 贡献榜 */
	public function getContributeList($touid,$p){
		if($p<1){
            $p=1;
        }
		$pnum=50;
		$start=($p-1)*$pnum;

		$rs=array();
		$rs=DI()->notorm->user_coinrecord
				->select("uid,sum(totalcoin) as total")
				->where('touid=?',$touid)
				->group("uid")
				->order("total desc")
				->limit($start,$pnum)
				->fetchAll();
				
		foreach($rs as $k=>$v){
			$rs[$k]['userinfo']=getUserInfo($v['uid']);
		}		
		
		return $rs;
	}
	
	/* 设置分销 */
	public function setDistribut($uid,$code){
        
        $isexist=DI()->notorm->agent
				->select("*")
				->where('uid=?',$uid)
				->fetchOne();
        if($isexist){
            return 1004;
        }
        
        //获取邀请码用户信息
		$oneinfo=DI()->notorm->agent_code
				->select("uid")
				->where('code=? and uid!=?',$code,$uid)
				->fetchOne();
		if(!$oneinfo){
			return 1002;
		}
		
		//获取邀请码用户的邀请信息
		$agentinfo=DI()->notorm->agent
				->select("*")
				->where('uid=?',$oneinfo['uid'])
				->fetchOne();
		if(!$agentinfo){
			$agentinfo=array(
				'uid'=>$oneinfo['uid'],
				'one_uid'=>0,
			);
		}
        // 判断对方是否自己下级
        if($agentinfo['one_uid']==$uid ){
            return 1003;
        }
		
		$data=array(
			'uid'=>$uid,
			'one_uid'=>$agentinfo['uid'],
			'addtime'=>time(),
		);
		DI()->notorm->agent->insert($data);
		return 0;
	}
    
    
    /* 印象标签 */
    public function getImpressionLabel(){
        
        $key="getImpressionLabel";
		$list=getcaches($key);
		if(!$list){
            $list=DI()->notorm->label
				->select("*")
				->order("list_order asc,id desc")
				->fetchAll();
            if($list){
                setcaches($key,$list); 
            }
			
		}

        return $list;
    }       
    /* 用户标签 */
    public function getUserLabel($uid,$touid){
        $list=DI()->notorm->label_user
				->select("label")
                ->where('uid=? and touid=?',$uid,$touid)
				->fetchOne();
                
        return $list;
        
    }    

    /* 设置用户标签 */
    public function setUserLabel($uid,$touid,$labels){
        $nowtime=time();
        $isexist=DI()->notorm->label_user
				->select("*")
                ->where('uid=? and touid=?',$uid,$touid)
				->fetchOne();
        if($isexist){
            $rs=DI()->notorm->label_user
                ->where('uid=? and touid=?',$uid,$touid)
				->update(array( 'label'=>$labels,'uptime'=>$nowtime ) );
            
        }else{
            $data=array(
                'uid'=>$uid,
                'touid'=>$touid,
                'label'=>$labels,
                'addtime'=>$nowtime,
                'uptime'=>$nowtime,
            );
            $rs=DI()->notorm->label_user->insert($data);
        }
                
        return $rs;
        
    }    
    
    /* 获取我的标签 */
    public function getMyLabel($uid){
        $rs=array();
        $list=DI()->notorm->label_user
				->select("label")
                ->where('touid=?',$uid)
				->fetchAll();
        $label=array();
        foreach($list as $k=>$v){
            $v_a=preg_split('/,|，/',$v['label']);
            $v_a=array_filter($v_a);
            if($v_a){
                $label=array_merge($label,$v_a);
            }
            
        }

        if(!$label){
            return $rs;
        }
        
        
        $label_nums=array_count_values($label);
        
        $label_key=array_keys($label_nums);
        
        $labels=$this->getImpressionLabel();
        
        $order_nums=array();
        foreach($labels as $k=>$v){
            if(in_array($v['id'],$label_key)){
                $v['nums']=(string)$label_nums[$v['id']];
                $order_nums[]=$v['nums'];
                $rs[]=$v;
            }
        }
        
        array_multisort($order_nums,SORT_DESC,$rs);
        
        return $rs;
        
    }   
    
    /* 获取关于我们列表 */
    public function getPerSetting(){
        $rs=array();
        
        $list=DI()->notorm->portal_post
				->select("id,post_title")
                ->where("type='2'")
                ->order('list_order asc')
				->fetchAll();
        foreach($list as $k=>$v){
            
            $rs[]=array('id'=>'0','name'=>$v['post_title'],'thumb'=>'' ,'href'=>get_upload_path("/portal/page/index?id={$v['id']}"));
        }
        
        return $rs;
    }
    
    /* 提现账号列表 */
    public function getUserAccountList($uid){
        
        $list=DI()->notorm->cash_account
                ->select("*")
                ->where('uid=?',$uid)
                ->order("addtime desc")
                ->fetchAll();
                
        return $list;
    }

    /* 账号信息 */
    public function getUserAccount($where){
        
        $list=DI()->notorm->cash_account
                ->select("*")
                ->where($where)
                ->order("addtime desc")
                ->fetchAll();
                
        return $list;
    }
    /* 设置提账号 */
    public function setUserAccount($data){
        
        $rs=DI()->notorm->cash_account
                ->insert($data);
                
        return $rs;
    }

    /* 删除提账号 */
    public function delUserAccount($data){
        
        $rs=DI()->notorm->cash_account
                ->where($data)
                ->delete();
                
        return $rs;
    }
    
	/* 登录奖励信息 */
	public function LoginBonus($uid){
		$rs=array(
			'bonus_switch'=>'0',
			'bonus_day'=>'0',
			'count_day'=>'0',
			'bonus_list'=>array(),
		);
        
        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
		$configpri=getConfigPri();
		if(!$configpri['bonus_switch']){
			return $rs;
		}
		$rs['bonus_switch']=$configpri['bonus_switch'];

		//file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 bonus_switch:'."\r\n",FILE_APPEND);
		/* 获取登录设置 */
        $key='loginbonus';
		//$list=getcaches($key);
		if(!$list){
            $list=DI()->notorm->loginbonus
					->select("day,coin")
					->fetchAll();
			if($list){
				setcaches($key,$list);
			}
		}
        
        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 list:'."\r\n",FILE_APPEND);
		$rs['bonus_list']=$list;
		$bonus_coin=array();
		foreach($list as $k=>$v){
			$bonus_coin[$v['day']]=$v['coin'];
		}

		/* 登录奖励 */
		$signinfo=DI()->notorm->user_sign
					->select("bonus_day,bonus_time,count_day")
					->where('uid=?',$uid)
					->fetchOne();
        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 signinfo:'."\r\n",FILE_APPEND);
		if(!$signinfo){
			$signinfo=array(
				'bonus_day'=>'0',
				'bonus_time'=>'0',
				'count_day'=>'0',
			);
        }
        $nowtime=time();
        if($nowtime - $signinfo['bonus_time'] > 60*60*24){
            $signinfo['count_day']=0;
        }
        $rs['count_day']=(string)$signinfo['count_day'];
		
		if($nowtime>$signinfo['bonus_time']){
			//更新
			$bonus_time=strtotime(date("Ymd",$nowtime))+60*60*24;
			$bonus_day=$signinfo['bonus_day'];
			if($bonus_day>6){
				$bonus_day=0;
			}
			$bonus_day++;
            $coin=$bonus_coin[$bonus_day];
            
			if($coin){
                $rs['bonus_day']=(string)$bonus_day;
            }
			
		}
        //file_put_contents(API_ROOT.'/Runtime/LoginBonus_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 rs:'."\r\n",FILE_APPEND);
		return $rs;
	}
    
	/* 获取登录奖励 */
	public function getLoginBonus($uid){
		$rs = array('code' => 0, 'msg' => '领取成功', 'coin' => 0,'count_day' => 0 ,'signed' => 0);
		$configpri=getConfigPri();
		if(!$configpri['bonus_switch']){
			$rs['code'] = 1001;
			$rs['msg'] = '后台签到开关未打开';
			return $rs;
		}
		
		/* 获取登录设置 */
        $key='loginbonus';
		$list=getcaches($key);
		if(!$list){
            $list=DI()->notorm->loginbonus
					->select("day,coin")
					->fetchAll();
			if($list){
				setcaches($key,$list);
			}
		}

		$bonus_coin=array();
		foreach($list as $k=>$v){
			$bonus_coin[$v['day']]=$v['coin'];
		}
		
		$isadd=0;
		/* 登录奖励 */
		$signinfo=DI()->notorm->user_sign
					->select("bonus_day,bonus_time,count_day")
					->where('uid=?',$uid)
					->fetchOne();
		if(!$signinfo){
			$isadd=1;
			$signinfo=array(
				'bonus_day'=>'0',
				'bonus_time'=>'0',
				'count_day'=>'0',
			);
        }
		$nowtime=time();
		if($nowtime>$signinfo['bonus_time']){
			//更新
			$bonus_time=strtotime(date("Ymd",$nowtime))+60*60*24;
			$bonus_day=$signinfo['bonus_day'];
			$count_day=$signinfo['count_day'];
			if($bonus_day>6){
				$bonus_day=0;
				$count_day=0;
			}
            if($nowtime - $signinfo['bonus_time'] > 60*60*24){
                $count_day=0;
				$bonus_day=0;
            }
			
			$bonus_day++;
			$count_day++;
            
 
            if($isadd){
                DI()->notorm->user_sign
                    ->insert(array("uid"=>$uid,"bonus_time"=>$bonus_time,"bonus_day"=>$bonus_day,"count_day"=>$count_day ));
            }else{
                DI()->notorm->user_sign
                    ->where('uid=?',$uid)
                    ->update(array("bonus_time"=>$bonus_time,"bonus_day"=>$bonus_day,"count_day"=>$count_day ));
            }
            
            $coin=$bonus_coin[$bonus_day];
            
			if($coin){
                DI()->notorm->user
                    ->where('id=?',$uid)
                    ->update(array( "coin"=>new NotORM_Literal("coin + {$coin}") ));
				

                /* 记录 */
                $insert=array("type"=>'1',"action"=>'3',"uid"=>$uid,"touid"=>$uid,"giftid"=>$bonus_day,"giftcount"=>'0',"totalcoin"=>$coin,"showid"=>'0',"addtime"=>$nowtime );
                DI()->notorm->user_coinrecord->insert($insert);
            }

			$rs['code'] = 0;
			$rs['msg'] = '签到成功';
			$rs['signed'] = 0;
			$rs['coin'] = $coin;
			$rs['count_day'] = $count_day;
			
			return $rs;
		}else{
			$rs['code'] = 1002;
			$rs['msg'] = '今日已签到';
			$rs['signed'] = 1;
			return $rs;
		}
		
		return $rs;
		
	}

	//检测用户是否填写了邀请码
	public function checkIsAgent($uid){
		$info=DI()->notorm->agent->where("uid=?",$uid)->fetchOne();
		if(!$info){
			return 0;
		}

		return 1;
	}

	//用户商城提现
    public function setShopCash($data){
        
        $nowtime=time();
        
        $uid=$data['uid'];
        $accountid=$data['accountid'];
        $money=$data['money'];
        
        $configpri=getConfigPri();
        $balance_cash_start=$configpri['balance_cash_start'];
        $balance_cash_end=$configpri['balance_cash_end'];
        $balance_cash_max_times=$configpri['balance_cash_max_times'];
        
        $day=(int)date("d",$nowtime);
        
        if($day < $balance_cash_start || $day > $balance_cash_end){
            return 1005;
        }
        
        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");
        
        if($balance_cash_max_times){
            $count=DI()->notorm->user_balance_cashrecord
                    ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                    ->count();
            if($count >= $balance_cash_max_times){
                return 1006;
            }
        }
        
        
        /* 钱包信息 */
        $accountinfo=DI()->notorm->cash_account
                ->select("*")
                ->where('id=? and uid=?',$accountid,$uid)
                ->fetchOne();

        if(!$accountinfo){
            return 1007;
        }
        

        /* 最低额度 */
        $balance_cash_min=$configpri['balance_cash_min'];
        
        if($money < $balance_cash_min){
            return 1004;
        }
        

        $ifok=DI()->notorm->user
            ->where('id = ? and balance>=?', $uid,$money)
            ->update(array('balance' => new NotORM_Literal("balance - {$money}")) );

        if(!$ifok){
            return 1001;
        }
        
        
        
        $data=array(
            "uid"=>$uid,
            "money"=>$money,
            "orderno"=>$uid.'_'.$nowtime.rand(100,999),
            "status"=>0,
            "addtime"=>$nowtime,
            "type"=>$accountinfo['type'],
            "account_bank"=>$accountinfo['account_bank'],
            "account"=>$accountinfo['account'],
            "name"=>$accountinfo['name'],
        );
        
        $rs=DI()->notorm->user_balance_cashrecord->insert($data);
        if(!$rs){
            return 1002;
        }           
            
        return $rs;
    }

    //获取认证信息
    public function getAuthInfo($uid){
    	$info=DI()->notorm->user_auth
    			->where("uid=? and status=1",$uid)
    			->select("real_name,cer_no")
    			->fetchOne();
    	return $info;
    }
    //获取订阅主播信息
    public function getSubscribes($uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;

        $touids=DI()->notorm->user_subscribe
            ->select("touid")
            ->where('uid=?',$uid)
            ->limit($start,$pnum)
            ->fetchAll();
        foreach($touids as $k=>$v){
            $userinfo=getUserInfo($v['touid']);
            if($userinfo){
                $islive='0';
                $isexist=DI()->notorm->live
                    ->where('uid=? and islive=1',$v['touid'])
                    ->fetchOne();
                if($isexist){
                    $islive='1';
                }
                $userinfo['islive'] = $islive;
                $userinfo['title'] = $isexist['title'];
                $userinfo['pull'] = $isexist['pull'];
				
				$userinfo['pull_h5'] = str_replace('.flv', '.m3u8', $userinfo['pull']);
				
                if(stripos($isexist['pull'],"mp4") !== false){
                    $userinfo['isvideo'] = 1;
                }else{
                    $userinfo['isvideo'] = 0;
                }
                $touids[$k] = $userinfo;
            }else{
                DI()->notorm->user_subscribe->where('uid=? or touid=?',$uid,$v['touid'])->delete();
                unset($touids[$k]);
            }

        }
        $touids=array_values($touids);
        return $touids;
    }

    //获取有哪些人订阅了我
    public function getSubscribeList($uid,$p,$limit=0){
        if($p<1){
            $p=1;
        }
        $pnum=$limit?$limit:20;
        $start=($p-1)*$pnum;
        $touids=DI()->notorm->user_subscribe
            ->select("uid")
            ->where('touid=?',$uid)
            ->limit($start,$pnum)
            ->fetchAll();

        foreach($touids as $k=>$v){
            $userinfo=getUserInfo($v['uid']);
            if($userinfo){
                $islive='0';
                $isexist=DI()->notorm->live
                    ->where('uid=? and islive=1',$v['uid'])
                    ->fetchOne();
                if($isexist){
                    $islive='1';
                }
                $userinfo['islive'] = $islive;
                $userinfo['title'] = $isexist['title'];
                $userinfo['pull'] = $isexist['pull'];
                if(stripos($isexist['pull'],"mp4") !== false){
                    $userinfo['isvideo'] = 1;
                }else{
                    $userinfo['isvideo'] = 0;
                }
                $touids[$k] = $userinfo;
            }else{
                DI()->notorm->user_subscribe->where('uid=? or touid=?',$v['uid'],$uid)->delete();
                unset($touids[$k]);
            }

        }
        $touids=array_values($touids);
        return $touids;
    }

    public function getSubscribeCount($uid){
        $count=DI()->notorm->user_subscribe
            ->where('touid=?',$uid)
            ->count();
        return $count;
    }

    //PC获取订阅主播信息未分页直接展示
    public function PCgetSubscribes($uid){

        $touids=DI()->notorm->user_subscribe
            ->select("touid")
            ->where('uid=?',$uid)
            ->limit(30)
            ->fetchAll();
        foreach($touids as $k=>$v){
            $userinfo=getUserInfo($v['touid']);
            if($userinfo){
                $islive='0';
                $isexist=DI()->notorm->live
                    ->select('uid,thumb,title,stream,pic_full_url')
                    ->where('uid=? and islive=1',$v['touid'])
                    ->fetchOne();
                if($isexist){
                    $islive='1';
                }
                $userinfo['islive'] = $islive;
                if($isexist['thumb']){
                    $userinfo['thumb'] = get_upload_path($isexist['thumb']);
                }else{
                    $userinfo['thumb'] = get_pic_path($isexist['pic_full_url']);
                }
                $userinfo['title'] = $isexist['title'];
                $userinfo['stream'] = $isexist['stream'];
                $touids[$k] = $userinfo;
            }else{
                DI()->notorm->user_subscribe->where('uid=? or touid=?',$uid,$v['touid'])->delete();
                unset($touids[$k]);
            }

        }
        $touids=array_values($touids);
        return $touids;
    }

    protected function getAction($k=''){
        $action=array(
            '1'=>'赠送礼物',
            '2'=>'弹幕',
            '3'=>'签到',
            '4'=>'购买VIP',
            '5'=>'兑换礼物',
            '6'=>'房间扣费',
            '7'=>'计时扣费',
            '8'=>'发送红包',
            '9'=>'抢红包',
            '10'=>'开通守护',
            '11'=>'邀请好友',
            '12'=>'礼物中奖',
            '13'=>'奖池中奖',
            '14'=>'缴纳保证金',
            '15'=>'退还保证金',
            '16'=>'转盘游戏',
            '17'=>'转盘中奖',
            '18'=>'购买靓号',
            '19'=>'游戏下注',
            '20'=>'游戏退还',
        );
        if($k===''){
            return $action;
        }
        
        return isset($action[$k]) ? $action[$k]: '未知';
    }

    protected function getStatus($k=''){
        $status=[
            '0'=>'待发货',
            '1'=>'已发货',
        ];
        if($k==''){
            return $status;
        }
        return isset($status[$k]) ? $status[$k]: '';
    }

    public function getUserList($uids) {
        $uids_str = implode(',', $uids);
        $list = DI()->notorm->user
                ->select("id,avatar,avatar_thumb,user_nicename")
                ->where("id in ({$uids_str})")
                ->fetchAll();

        return $list;
    }
    
}
