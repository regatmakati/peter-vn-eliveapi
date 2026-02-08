<?php

class Model_PCLive extends PhalApi_Model_NotORM {
    /* 查看用户是否授权直播 */
    public function getAuth($uid){
        $info = DI()->notorm->anchor_auth
            ->select("*")
            ->where('uid=? and status=1',$uid)
            ->fetchOne();
        if($info){
            return 1;
        }
        return 2;
    }
	/* 创建房间 */
	public function createRoom($uid,$data) {
        
        /* 获取主播 推荐、热门 */
        $data['ishot']='0';
        $data['isrecommend']='0';
        $userinfo=DI()->notorm->user
					->select("ishot,isrecommend")
					->where('id=?',$uid)
					->fetchOne();
        if($userinfo){
            $data['ishot']=$userinfo['ishot'];
            $data['isrecommend']=$userinfo['isrecommend'];
        }
		$isexist=DI()->notorm->live
					->select("uid")
					->where('uid=?',$uid)
					->fetchOne();
		if($isexist){
            /* 判断存在的记录是否为直播状态 */
            if($isexist['isvideo']==0 && $isexist['islive']==1){
                /* 若存在未关闭的直播 关闭直播 */
                $this->stopRoom($uid,$isexist['stream']);
                
                /* 加入 */
                $rs=DI()->notorm->live->insert($data);
            }else{
                /* 更新 */
                $rs=DI()->notorm->live->where('uid = ?', $uid)->update($data);
            }
		}else{
			/* 加入 */
			$rs=DI()->notorm->live->insert($data);
		}
		if(!$rs){
			return $rs;
		}
		return 1;
	}

    /* 直播分类 */
	public function getClass(){
        $list=array();
        $classes=DI()->notorm->live_class
            ->select("id,name")
            ->order('list_order asc')
            ->fetchAll();
        return $classes;
    }

    /* 分类赛事 */
    public function getMatch($liveclassid){
        if($liveclassid==3){
            $starttime = strtotime(date('Y-m-d'));
            $endtime = $starttime + 2*24*3600;
            $where = " start_time>$starttime  and start_time<$endtime and (status=0 or status=1) ";
            $info=DI()->notorm->lol_match
                ->queryAll("select id,start_time,league_id,match_id,status,team_a_score,team_b_score,league,team_a,team_b,round_son_name,viewnum from cmf_lol_match where $where order by start_time asc limit 4");
            $res = $this->domatchlistf($info);
        }

//        elseif($liveclassid == 4){
//            $starttime = strtotime(date('Y-m-d'));
//            $endtime = $starttime + 2*24*3600;
//            $where = " matchStartTime>$starttime  and matchStartTime<$endtime and state != 120 ";
//            $sql = "select a.id,a.matchStartTime as start_time,a.matchId as match_id,a.leagueId as league_id,b.leagueNameCnShort as league_name,if(state in (10,20,30,40,50),1,0) as status,a.viewnum,a.homeScore as team_a_score,a.awayScore as team_b_score,c.nameCn as team_a_name,c.logo as team_a_logo ,d.nameCn as team_b_name,d.logo as team_b_logo,'足球' as league_cat from cmf_sports_football_match a left join cmf_sports_football_league b on a.leagueId=b.leagueId left join cmf_sports_football_team c on a.homeId=c.teamId left join cmf_sports_football_team d on a.awayId=d.teamId where  $where  order by a.matchStartTime asc limit 4";
//            $info=DI()->notorm->lol_match
//                ->queryAll($sql);
//            $res = $this->getFootballMatchBaseInfo($info,1);
//        }elseif($liveclassid == 2){
//            $starttime = strtotime(date('Y-m-d'));
//            $endtime = $starttime + 2*24*3600;
//            $where = " matchStartTime>$starttime  and matchStartTime<$endtime and state != 150 ";
//            $sql = "select a.id,a.matchStartTime as start_time,a.matchId as match_id,a.leagueId as league_id,b.leagueNameCnShort as league_name,if(state in (10,20,30,40,50,60,70,80),1,0) as status,a.viewnum,a.homeScore as team_a_score,a.awayScore as team_b_score,c.nameCn as team_a_name,c.logo as team_a_logo ,d.nameCn as team_b_name,d.logo as team_b_logo,'篮球' as league_cat from cmf_sports_basketball_match a left join cmf_sports_basketball_league b on a.leagueId=b.leagueId left join cmf_sports_basketball_team c on a.homeId=c.teamId left join cmf_sports_basketball_team d on a.awayId=d.teamId where $where  order by a.matchStartTime asc limit 4";
//            $info=DI()->notorm->lol_match
//                ->queryAll($sql);
//            $res = $this->getFootballMatchBaseInfo($info,2);
//        }
        else{
            $res = [];
        }

        return $res;
    }

    /* 首页PC赛事 现在默认LOL 后期增加其它赛事 */
    public function getPCMatch(){

        $starttime = strtotime(date('Y-m-d'));
        $endtime = $starttime + 2*24*3600;
        $where = " start_time>$starttime  and start_time<$endtime and (status=0 or status=1) ";
        $info=DI()->notorm->lol_match
            ->queryAll("select id,start_time,league_id,match_id,status,viewnum,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_list from cmf_lol_match where $where order by start_time asc");
        $res = $this->domatchlistf($info);
        $starttime = strtotime(date("Y-m-d 08:00:00"));
        $endtime = strtotime("+15 day");
        $where = " where a.matchStartTime>=$starttime  and a.matchStartTime<=$endtime and IF(a.state IN(10,20,30,40,50), a.live_url!='', 1=1) ";
        //获取足球赛事信息
        $sql = "select a.id,a.matchStartTime as start_time,a.matchId as match_id,b.leagueNameCnShort as league_name,if(a.state in (10,20,30,40,50),1,0) as status,a.viewnum,a.homeScore as team_a_score,a.awayScore as team_b_score,c.nameCn as team_a_name,c.logo as team_a_logo ,d.nameCn as team_b_name,d.logo as team_b_logo from cmf_sports_football_match a left join cmf_sports_football_league b on a.leagueId=b.leagueId left join cmf_sports_football_team c on a.homeId=c.teamId left join cmf_sports_football_team d on a.awayId=d.teamId  $where  order by a.state asc, a.matchStartTime asc limit 8";
        $fbinfo = DI()->notorm->sports_football_match
            ->queryAll($sql);
        $fbres = $this->getFootballMatchBaseInfo($fbinfo,1);
        $where = " where a.matchStartTime>=$starttime  and a.matchStartTime<=$endtime  and IF(state IN(10,20,30,40,50,60,70,80), live_url!='', 1=1) ";
        //获取蓝球赛事信息
        $sql = "select a.id,a.matchStartTime as start_time,a.matchId as match_id,b.leagueNameCnShort as league_name,if(a.state in (10,20,30,40,50,60,70,80),1,0) as status,a.viewnum,a.homeScore as team_a_score,a.awayScore as team_b_score,c.nameCn as team_a_name,c.logo as team_a_logo ,d.nameCn as team_b_name,d.logo as team_b_logo from cmf_sports_basketball_match a left join cmf_sports_basketball_league b on a.leagueId=b.leagueId left join cmf_sports_basketball_team c on a.homeId=c.teamId left join cmf_sports_basketball_team d on a.awayId=d.teamId  $where  order by a.state asc , a.matchStartTime asc limit 8";
        $bkinfo = DI()->notorm->sports_football_match
            ->queryAll($sql);
        $bkres = $this->getFootballMatchBaseInfo($bkinfo,2);

        $res = array_merge($fbres,$bkres,$res);
        return $res;
    }

    /* 处理足球比赛基本信息 */
    public function getFootballMatchBaseInfo($info,$catid=1){
        $res = [];
        foreach($info as $key=>$val){
            $starttime = $val['start_time'];
            $val['start_time'] =  date('Y-m-d',$starttime);
            $val['startdate'] = date('m-d',$starttime);
            unset($val['start_time']);

            $tommorow=date("Y-m-d",strtotime("+1 day"));
            if($tommorow == date("Y-m-d",$starttime)){
                $val['starttime'] = '明天 '.date('H:i',$starttime);
            }else{
                $val['starttime'] = date('H:i',$starttime);
            }

            if($val['viewnum']<100){
                $val['viewnum'] = 2000 + $val['viewnum'];
            }elseif($val['viewnum']>=100 && $val['viewnum']<1000){
                $val['viewnum'] = 100000+$val['viewnum'];
            }else{
                $val['viewnum'] = 1000000+$val['viewnum'];
            }

            $val['battle_current_index'] = 1;//小局第几场
            $val['battle_current_id']=0;
            $val['battle_duration']=0;//战役时长
            if($catid==1){
                $val['league_cat']="足球";
                $val['match_type']=3;
            }else{
                $val['league_cat']="篮球";
                $val['match_type']=2;
            }
            $res[]=$val;
        }
        return $res;
    }





    public function domatchlistf($info){
        $res = [];
        foreach($info as $key=>$val){
            $starttime = $val['start_time'];
            unset($val['start_time']);
            $val['startdate'] = date('m-d',$starttime);
            $val['starttime'] = date('H:i',$starttime);

            $league = json_decode($val['league'],true);
            $val['league_name'] = $league['name'];
            unset($val['league']);

            $team_a = json_decode($val['team_a'],true);
            $val['team_a_name'] = $team_a['short_name'];
            $val['team_a_logo'] = $team_a['logo'];
            unset($val['team_a']);

            $team_b = json_decode($val['team_b'],true);
            $val['team_b_name'] = $team_b['short_name'];
            $val['team_b_logo'] = $team_b['logo'];
            unset($val['team_b']);
            $val['league_cat']="英雄联盟";

            $res[]=$val;
        }
        return $res;
    }
	
	/* 主播粉丝 */
    public function getFansIds($touid) {
        
        $list=array();
		$fansids=DI()->notorm->user_attention
					->select("uid")
					->where('touid=?',$touid)
					->fetchAll();
                    
        if($fansids){
            $uids=array_column($fansids,'uid');
            
            $pushids=DI()->notorm->user_pushid
					->select("pushid")
					->where('uid',$uids)
					->fetchAll();
            $list=array_column($pushids,'pushid');
            $list=array_filter($list);
        }
        return $list;
    }

}
