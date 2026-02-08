<?php

class Model_Match extends PhalApi_Model_NotORM {

	/* LOL推送比赛数据 */
	public function pushMatchData($match_id) {
	
        $info=DI()->notorm->lol_match
					->select("id,start_time,league_id,match_id,status,viewnum,team_a_score,team_b_score,league,team_a_id,team_b_id,team_a,team_b,round_son_name,battle_ids,battle_list")
					->where('match_id=?',$match_id)
					->fetchOne();
		if(!$info) return false;			
		$list[0] = $info;
		$res= $this->getmatchbaseinfo($list);		
		$matchinfo = $res['data'][0];		

		$battle_list = json_decode($matchinfo['battle_list'],true);
		unset($matchinfo['battle_list']);
		$round = count($battle_list) - 1;
		
		if($battle_list[$round]){
			$battle_id = $battle_list[$round]['battle_id'];

			if($battle_id){
				$data = DI()->notorm->lol_match_live_battle
						->select("*")
						->where("match_id='$match_id' and battle_id='$battle_id'")
						->fetchOne();
										
			}else{
				$data = [];
			}
			
			$data['economic_diff'] = json_decode($data['economic_diff'],true);
			$data['xp_diff'] = json_decode($data['xp_diff'],true);
			$data['creep_score_diff'] = json_decode($data['creep_score_diff'],true);
			$data['duration'] = gmstrftime('%M:%S',$data['duration']);
			unset($data['team_stats']);
			unset($data['player_stats']);
			unset($data['id']);
			unset($data['start_time']);
			unset($data['battle_id']);
			unset($data['match_id']);
			unset($data['addtime']);				
		}else{
			$data = [];
		}
		$matchinfo['data'] = $data;
		
		//获取指数		
		$indexes=DI()->notorm->lol_match_bet_info_rolling
				->select("*")
				->where("match_id='$match_id' and board_num='0' and bet_type=1")
				->fetchAll();		

		if($indexes){
			foreach($indexes as $key=>$val){
				$options = json_decode($val['options'],true);
				foreach($options as $k=>$v){
					unset($options[$k]['bet_item_id']);
					//unset($options[$k]['init_handicap']);
					unset($options[$k]['name']);
				}
				
				unset($indexes[$key]['bet_id']);
				unset($indexes[$key]['end_time']);
				unset($indexes[$key]['match_id']);
				unset($indexes[$key]['status']);
				unset($indexes[$key]['result_id']);
				unset($indexes[$key]['bet_type']);
				unset($indexes[$key]['type_desc']);
				unset($indexes[$key]['handicap']);
				unset($indexes[$key]['addtime']);
				unset($indexes[$key]['board_num']);
				unset($indexes[$key]['id']);
				unset($indexes[$key]['title']);
				
				$indexes[$key]['options'] = $options;
			}			
			$index = $indexes;
							
		}else{
			$index = [];
		}		
		
		$matchinfo['index'] = $index;
		unset($matchinfo['team_a_name']);
		unset($matchinfo['team_a_logo']);
		unset($matchinfo['team_b_name']);
		unset($matchinfo['team_b_logo']);
		unset($matchinfo['league_cat']);
		unset($matchinfo['round_son_name']);
		unset($matchinfo['league_name']);
		unset($matchinfo['battle_ids']);
		unset($matchinfo['id']);
		unset($matchinfo['league_id']);
		return $matchinfo;
	}
	
	/* LOL比赛阵容 */
	public function getMatchOutline($match_id,$range_type) {
	
        $info=DI()->notorm->lol_match
					->select("id,start_time,league_id,match_id,status,viewnum,team_a_id,team_b_id,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_ids,battle_list")
					->where('match_id=?',$match_id)
					->fetchOne();
		if(!$info) return false;			
		$list[0] = $info;
		$res= $this->getmatchbaseinfo($list);		
		$matchinfo = $res['data'][0];
		
		$outline=DI()->notorm->lol_match_analysis
				->select("team_a_id,team_b_id,battle_stats,team_a_recent_stats,team_b_recent_stats")
				->where("match_id='$match_id' and range_type in (0,1)")
				->fetchOne();	
		if($outline){
			$analysis['team_a_recent_stats'] = json_decode($analysis['team_a_recent_stats'],true);
			$analysis['team_b_recent_stats'] = json_decode($analysis['team_b_recent_stats'],true);
			$analysis['battle_stats'] = json_decode($analysis['battle_stats'],true);
			unset($analysis['battle_stats']['records']);
			unset($analysis['team_a_recent_stats']['matches']);
			unset($analysis['team_b_recent_stats']['matches']);
			$data = $analysis;
							
		}else{
			$data = [];
		}	
		
		$matchinfo['data'] = $data;
		return $matchinfo;
	}
	
	
	/* LOL比赛分析 */
	public function getMatchAnalysis($match_id,$range_type) {
	
        $info=DI()->notorm->lol_match
					->select("id,start_time,league_id,match_id,status,viewnum,team_a_id,team_b_id,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_ids,battle_list")
					->where('match_id=?',$match_id)
					->fetchOne();
		if(!$info) return false;			
		$list[0] = $info;
		$res= $this->getmatchbaseinfo($list);		
		$info = $res['data'][0];
		unset($info['battle_list']);
		$analysis=DI()->notorm->lol_match_analysis
				->select("team_a_id,team_b_id,battle_stats,team_a_recent_stats,team_b_recent_stats")
				->where("match_id='$match_id' and range_type in (0,1)")
				->fetchOne();	
				
		if($analysis){
			$analysis['team_a_recent_stats'] = json_decode($analysis['team_a_recent_stats'],true);
			$analysis['team_b_recent_stats'] = json_decode($analysis['team_b_recent_stats'],true);
			$analysis['battle_stats'] = json_decode($analysis['battle_stats'],true);

			//获取历史参与战队的信息	
			$teamids[] = $analysis['team_a_id'];
			$teamids[] = $analysis['team_b_id'];
			$matches = $analysis['team_a_recent_stats']['matches'];			
			foreach($matches as $k=>$v){
				$teamids[] = $v['team_a_id'];
				$teamids[] = $v['team_b_id'];
			}
			$matches = $analysis['team_b_recent_stats']['matches'];
			foreach($matches as $k=>$v){
				$teamids[] = $v['team_a_id'];
				$teamids[] = $v['team_b_id'];				
			}
			$teamids = array_unique($teamids);
			$teamarr = [];
			if($teamids){
				$teamlist=DI()->notorm->lol_team
					->select("*")
					->where("team_id in (".implode(',',$teamids).")")
					->fetchAll();				
			}
			foreach($teamlist as $key=>$val){
				$teamarr[$val['team_id']]['name'] = $val['short_name'];
				$teamarr[$val['team_id']]['logo'] = $val['logo'];
			}
			
			//处理对战数据
			$arr = [];
			$score[$info['team_a_id']]=$score[$info['team_b_id']]=0;
			$records = $analysis['battle_stats']['records'];			
			$matchtime = array_column($records,'match_time');
			array_multisort($matchtime,SORT_ASC,$records);			
			foreach($records as $k=>$v){
				if(!$v['team_a_id']){
					continue;
				}	
				$records[$k]['team_a_name'] = $teamarr[$v['team_a_id']]['name'];
				$records[$k]['team_b_name'] = $teamarr[$v['team_b_id']]['name'];
				$records[$k]['team_a_logo'] = $teamarr[$v['team_a_id']]['logo'];
				$records[$k]['team_b_logo'] = $teamarr[$v['team_b_id']]['logo'];
				$score[$v['win_team_id']] +=1;
				$records[$k]['team_a_score'] = $score[$v['team_a_id']]?$score[$v['team_a_id']]:0;
				$records[$k]['team_b_score'] = $score[$v['team_b_id']]?$score[$v['team_b_id']]:0;				
				$records[$k]['match_time'] = date('m-d',substr($records[$k]['match_time'],0,strlen($records[$k]['match_time'])-3));
				unset($records[$k]['match_id']);
				unset($records[$k]['league_id']);
				unset($records[$k]['league_name']);
				unset($records[$k]['league_name_en']);
				unset($records[$k]['battle_id']);
				unset($records[$k]['win_team_id']);
				unset($records[$k]['duration']);
				unset($records[$k]['five_kill_team_id']);
				unset($records[$k]['first_tower_team_id']);
				unset($records[$k]['first_dragon_team_id']);
				unset($records[$k]['first_nash_team_id']);
				unset($records[$k]['first_blood_team_id']);
				unset($records[$k]['ten_kill_team_id']);
				unset($records[$k]['team_a_kill_count']);
				unset($records[$k]['team_b_kill_count']);
				unset($records[$k]['team_a_id']);
				unset($records[$k]['team_b_id']);
				array_push($arr,$records[$k]);
			}
			//unset($analysis['battle_stats']['team_a_lose_count']);
			//unset($analysis['battle_stats']['team_b_lose_count']);
			unset($analysis['battle_stats']['team_a_deaths_per_game']);
			unset($analysis['battle_stats']['team_b_deaths_per_game']);
			unset($analysis['battle_stats']['team_a_towers_per_game']);
			unset($analysis['battle_stats']['team_b_towers_per_game']);
			unset($analysis['battle_stats']['team_a_golds_per_game']);
			unset($analysis['battle_stats']['team_b_golds_per_game']);
			unset($analysis['battle_stats']['team_a_ten_kill_count']);
			unset($analysis['battle_stats']['team_b_ten_kill_count']);
			unset($analysis['battle_stats']['team_a_kda_per_game']);
			unset($analysis['battle_stats']['team_b_kda_per_game']);
			unset($analysis['team_a_recent_stats']['first_blood_rate']);
			unset($analysis['team_a_recent_stats']['ten_kill_rate']);
			unset($analysis['team_b_recent_stats']['first_blood_rate']);
			unset($analysis['team_b_recent_stats']['ten_kill_rate']);			
			$analysis['battle_stats']['records'] = $arr;

			//整理A队数据
			$matches = $analysis['team_a_recent_stats']['matches'];
			$matchtime = array_column($matches,'match_time');
			array_multisort($matchtime,SORT_ASC,$matches);			
			$matchmerge = [];
			foreach($matches as $k=>$v){
				/*if($analysis['team_a_id'] == $v['win_team_id']){
					$wins++;
				}*/
				if($v['win_team_id'] == $v['team_a_id']){
					$teamarr['score'][$v['match_id']][$v['win_team_id']]++;	
				}else{
					$teamarr['score'][$v['match_id']][$v['win_team_id']]++;	
				}					
				$matches[$k]['team_a_score']=$teamarr['score'][$v['match_id']][$v['team_a_id']]?$teamarr['score'][$v['match_id']][$v['team_a_id']]:0;
				$matches[$k]['team_b_score']=$teamarr['score'][$v['match_id']][$v['team_b_id']]?$teamarr['score'][$v['match_id']][$v['team_b_id']]:0;
				$matches[$k]['team_a_name']=$teamarr[$v['team_a_id']]['name'];
				$matches[$k]['team_a_logo']=$teamarr[$v['team_a_id']]['logo'];
				$matches[$k]['team_b_name']=$teamarr[$v['team_b_id']]['name'];
				$matches[$k]['team_b_logo']=$teamarr[$v['team_b_id']]['logo'];
				$matches[$k]['match_time'] = date('m-d', substr($v['match_time'],0,strlen($v['match_time'])-3));			
				unset($matches[$k]['league_id']);
				unset($matches[$k]['league_name']);
				unset($matches[$k]['league_name_en']);				
				//unset($matches[$k]['match_id']);
				//unset($matches[$k]['battle_id']);
				//unset($matches[$k]['win_team_id']);
				unset($matches[$k]['first_blood_team_id']);
				unset($matches[$k]['ten_kill_team_id']);				
				unset($matches[$k]['duration']);
				//unset($matches[$k]['team_a_id']);
				//unset($matches[$k]['team_b_id']);	
				unset($matches[$k]['team_a_kill_count']);
				unset($matches[$k]['team_b_kill_count']);
				$matchmerge[$matches[$k]['match_id']] = $matches[$k];					
			}			
			$teamares = [];
			$wins=$lost=$tie=0;
			foreach($matchmerge as $k=>$v){
				$teamares[] = $v;
				if($v['team_a_id'] == $analysis['team_a_id']){//team_a是主队
					if($v['team_a_score'] > $v['team_b_score']){
						$wins++;
					}elseif($v['team_a_score'] == $v['team_b_score']){
						$tie++;
					}else{
						$lost++;
					}
				}else{//team_b是主队
					if($v['team_b_score'] > $v['team_a_score']){
						$wins++;
					}elseif($v['team_b_score'] == $v['team_a_score']){
						$tie++;
					}else{
						$lost++;
					}					
				}
			}			
			unset($teamarr['score']);
			$analysis['team_a_recent_stats']['matches']=$teamares;
			$analysis['team_a_recent_stats']['win_rate']=$wins;	
			$analysis['team_a_recent_stats']['lost_rate']= $lost;
			$analysis['team_a_recent_stats']['tie_rate']= $tie;
			
			//整理B队数据
			$matches = $analysis['team_b_recent_stats']['matches'];
			$matchtime = array_column($matches,'match_time');
			array_multisort($matchtime,SORT_ASC,$matches);	
			$matchmerge = [];
			foreach($matches as $k=>$v){
				/*if($analysis['team_b_id'] == $v['win_team_id']){
					$wins++;
				}*/				
				if($v['win_team_id'] == $v['team_a_id']){
					$teamarr['score'][$v['match_id']][$v['win_team_id']]++;	
				}else{
					$teamarr['score'][$v['match_id']][$v['win_team_id']]++;	
				}					
				$matches[$k]['team_a_score']=$teamarr['score'][$v['match_id']][$v['team_a_id']]?$teamarr['score'][$v['match_id']][$v['team_a_id']]:0;
				$matches[$k]['team_b_score']=$teamarr['score'][$v['match_id']][$v['team_b_id']]?$teamarr['score'][$v['match_id']][$v['team_b_id']]:0;
				$matches[$k]['team_a_name']=$teamarr[$v['team_a_id']]['name'];
				$matches[$k]['team_a_logo']=$teamarr[$v['team_a_id']]['logo'];
				$matches[$k]['team_b_name']=$teamarr[$v['team_b_id']]['name'];
				$matches[$k]['team_b_logo']=$teamarr[$v['team_b_id']]['logo'];
				$matches[$k]['match_time'] = date('m-d', substr($v['match_time'],0,strlen($v['match_time'])-3));			
				unset($matches[$k]['league_id']);
				unset($matches[$k]['league_name']);
				unset($matches[$k]['league_name_en']);				
				//unset($matches[$k]['match_id']);
				//unset($matches[$k]['battle_id']);
				//unset($matches[$k]['win_team_id']);
				unset($matches[$k]['first_blood_team_id']);
				unset($matches[$k]['ten_kill_team_id']);				
				unset($matches[$k]['duration']);
				//unset($matches[$k]['team_a_id']);
				//unset($matches[$k]['team_b_id']);	
				unset($matches[$k]['team_a_kill_count']);
				unset($matches[$k]['team_b_kill_count']);
				$matchmerge[$matches[$k]['match_id']] = $matches[$k];	
			}
			$teambres = [];
			$wins=$lost=$tie=0;
			foreach($matchmerge as $k=>$v){
				$teambres[] = $v;
				if($v['team_a_id'] == $analysis['team_b_id']){//team_a是主队
					if($v['team_a_score'] > $v['team_b_score']){
						$wins++;
					}elseif($v['team_a_score'] == $v['team_b_score']){
						$tie++;
					}else{
						$lost++;
					}
				}else{//team_b是主队
					if($v['team_b_score'] > $v['team_a_score']){
						$wins++;
					}elseif($v['team_b_score'] == $v['team_a_score']){
						$tie++;
					}else{
						$lost++;
					}					
				}
			}			
			unset($teamarr['score']);
			$analysis['team_b_recent_stats']['matches']=$teambres;		
			$analysis['team_b_recent_stats']['win_rate']=$wins;
			$analysis['team_b_recent_stats']['lost_rate']=$lost;
			$analysis['team_b_recent_stats']['tie_rate']=$tie;
			$data = $analysis;
							
		}else{
			$data = [];
		}	
		
		$info['data'] = $data;
		return $info;
	}
	
	/* LOL比赛选手 */
	public function getMatchPlayer($match_id,$range_type) {
	
        $info=DI()->notorm->lol_match
					->select("id,start_time,league_id,match_id,status,viewnum,team_a_id,team_b_id,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_ids,battle_list")
					->where('match_id=?',$match_id)
					->fetchOne();
		if(!$info) return false;			
		$list[0] = $info;
		$res= $this->getmatchbaseinfo($list);		
		$matchinfo = $res['data'][0];
		
		$outline=DI()->notorm->lol_match_analysis
				->select("team_a_player_stats,team_b_player_stats")
				->where("match_id='$match_id' and range_type in (0,1)")
				->fetchOne();		

		if($outline['team_a_player_stats']){
			$team_a_player_stats=json_decode($outline['team_a_player_stats'],true);
			foreach($team_a_player_stats as $key=>$val){
				unset($team_a_player_stats[$key]['player_id']);
				$hero_pick_stats = $team_a_player_stats[$key]['hero_pick_stats'];
				$hero_game_count = array_column($hero_pick_stats,'hero_game_count');
				array_multisort($hero_game_count,SORT_DESC,$hero_pick_stats);
				$hero_pick_stats =array_slice($hero_pick_stats, 0, 3);
				$team_a_player_stats[$key]['hero_pick_stats'] = $hero_pick_stats;				
			}
			$matchinfo['team_a_player_stats'] = $team_a_player_stats;	
		}else{
			$matchinfo['team_a_player_stats'] = [];
		}
		
		if($outline['team_b_player_stats']){
			$team_b_player_stats=json_decode($outline['team_b_player_stats'],true);
			foreach($team_b_player_stats as $key=>$val){
				unset($team_b_player_stats[$key]['player_id']);
				$hero_pick_stats = $team_b_player_stats[$key]['hero_pick_stats'];
				$hero_game_count = array_column($hero_pick_stats,'hero_game_count');
				array_multisort($hero_game_count,SORT_DESC,$hero_pick_stats);
				$hero_pick_stats =array_slice($hero_pick_stats, 0, 3);
				$team_b_player_stats[$key]['hero_pick_stats'] = $hero_pick_stats;				
			}
			$matchinfo['team_b_player_stats'] = $team_b_player_stats;	
		}else{
			$matchinfo['team_b_player_stats'] = [];
		}
		
		return $matchinfo;
	}
	

	/* LOL比赛指数 */
	public function getMatchIndex($match_id,$board_num,$handicap) {
	
        $info=DI()->notorm->lol_match
					->select("id,start_time,league_id,match_id,status,viewnum,team_a_score,team_b_score,league,team_a_id,team_b_id,team_a,team_b,round_son_name,battle_ids,battle_list")
					->where('match_id=?',$match_id)
					->fetchOne();
		if(!$info) return false;			
		$list[0] = $info;
		$res= $this->getmatchbaseinfo($list);		
		$matchinfo = $res['data'][0];
		
		$rindexes=DI()->notorm->lol_match_bet_info_rolling
				->select("*")
				->where("match_id='$match_id' and board_num='$board_num' and bet_type=1")
				->fetchAll();	
		
		$indexes=DI()->notorm->lol_match_bet_info
				->select("*")
				->where("match_id='$match_id' and board_num='$board_num' and bet_type=1")
				->fetchAll();
		
		$rollindex = [];	
		foreach($rindexes as $key=>$val){
			$options = json_decode($val['options'],true);
			foreach($options as $k=>$v){
				$rollindex[$val['source']][$v['team_id']] = $v['odds'];
			}
		}
		unset($rindexes);	
			
		if($indexes){
			foreach($indexes as $key=>$val){
				$options = json_decode($val['options'],true);
				foreach($options as $k=>$v){
					unset($options[$k]['bet_item_id']);
					//unset($options[$k]['init_handicap']);
					unset($options[$k]['name']);
					$options[$k]['init_handicap'] = $v['odds'];
					$options[$k]['odds'] = $rollindex[$val['source']][$v['team_id']]?$rollindex[$val['source']][$v['team_id']]:0.00;
				}
				
				unset($indexes[$key]['bet_id']);
				unset($indexes[$key]['end_time']);
				unset($indexes[$key]['match_id']);
				unset($indexes[$key]['status']);
				unset($indexes[$key]['result_id']);
				unset($indexes[$key]['bet_type']);
				unset($indexes[$key]['type_desc']);
				unset($indexes[$key]['handicap']);
				unset($indexes[$key]['addtime']);
				unset($indexes[$key]['board_num']);
				unset($indexes[$key]['id']);
				unset($indexes[$key]['title']);				
				$indexes[$key]['options'] = $options;
				$indexes[$key]['jumpurl'] = 'https://www.obao10.com/entry/register/?i_code=0630334';
			}			
			$data = $indexes;
							
		}else{
			$data = [];
		}	
		
		$matchinfo['data'] = $data;
		return $matchinfo;
	}
	
	
	/* LOL比赛数据 */
	public function getMatchData($match_id,$index) {
	
        $info=DI()->notorm->lol_match
					->select("id,start_time,league_id,match_id,status,viewnum,team_a_score,team_b_score,league,team_a_id,team_b_id,team_a,team_b,round_son_name,battle_ids,battle_list")
					->where('match_id=?',$match_id)
					->fetchOne();
		if(!$info) return false;			
		$list[0] = $info;
		$res= $this->getmatchbaseinfo($list);		
		$matchinfo = $res['data'][0];		
		unset($matchinfo['battle_list']);
		

		$maxindex = DI()->notorm->lol_match_live_battle
			->select("max(`index`) as mindex")
			->where("match_id='$match_id'")
			->fetchOne();
		
		if(!$index) $index=1;
		$matchinfo['battle_current_index'] = $maxindex['mindex'];

		$data = DI()->notorm->lol_match_live_battle
			->select("*")
			->where("match_id='$match_id' and `index`='$index'")
			->fetchOne();
		
		if($data){
			$player_stats = json_decode($data['player_stats'],true);
			$team_stats = json_decode($data['team_stats'],true);
			$data['economic_diff'] = json_decode($data['economic_diff'],true);
			$data['xp_diff'] = json_decode($data['xp_diff'],true);
			$data['creep_score_diff'] = json_decode($data['creep_score_diff'],true);
			$equip_ids = [];
			$skill_ids = [];
			foreach($player_stats as $key=>$val){
				$equip_ids = array_merge($equip_ids,$val['equip_ids']);				
				$skill_ids = array_merge($equip_ids,$val['skill_ids']);	
			}
			$equip_ids = array_unique($equip_ids);
			$skill_ids = array_unique($skill_ids);
			
			if($equip_ids){
				$equips = DI()->notorm->lol_item
						->select("*")
						->where("item_id in (".implode(',',$equip_ids).")")
						->fetchAll();				
			}

			if($skill_ids){
				$skills = DI()->notorm->lol_skill
						->select("*")
						->where("skill_id in (".implode(',',$skill_ids).")")
						->fetchAll();				
			}			


			$equip_ids = [];
			$skill_ids = [];						
			foreach($equips as $k => $v){
				$equip_ids[$v['item_id']] = $v['image'];
			}					
			foreach($skills as $k => $v){
				$skill_ids[$v['skill_id']] = $v['image'];
			}
	
			foreach($team_stats as $key=>$val){
				unset($team_stats[$key]['battle_id']);
				//unset($team_stats[$key]['team_id']);
				unset($team_stats[$key]['money']);
				//unset($team_stats[$key]['kill_count']);
				unset($team_stats[$key]['death_count']);
				unset($team_stats[$key]['assist_count']);
				unset($team_stats[$key]['pick_ids']);
				unset($team_stats[$key]['ban_ids']);
				//unset($team_stats[$key]['is_win']);
				unset($team_stats[$key]['is_first_big_dragon']);
				unset($team_stats[$key]['is_first_small_dragon']);
				unset($team_stats[$key]['is_first_blood']);
				unset($team_stats[$key]['is_five_kills']);
				unset($team_stats[$key]['is_ten_kills']);
				unset($team_stats[$key]['is_first_tower']);
				//$team_stats[$key]['ban_list'] =$team_stats[$key]['pick_list'];//temp
			}
			$data['team_stats']=$team_stats;
			foreach($player_stats as $key=>$val){
				$tmp = [];
				foreach($val['equip_ids'] as $v){
					$tmp[] = $equip_ids[$v];
				}
				$player_stats[$key]['equip_ids'] = $tmp;
				
				$tmp = [];				
				foreach($val['skill_ids'] as $v){
					if($skill_ids[$v]) $tmp[] = $skill_ids[$v];
				}
				$player_stats[$key]['skill_ids'] = $tmp;
				unset($player_stats[$key]['battle_id']);
				unset($player_stats[$key]['player_id']);
				//unset($player_stats[$key]['team_id']);
				unset($player_stats[$key]['hero_id']);
				unset($player_stats[$key]['hero_level']);
				unset($player_stats[$key]['damage_count']);
				unset($player_stats[$key]['damage_taken_count']);
				unset($player_stats[$key]['wards_placed_count']);
				
			}	
			$data['player_stats'] = $player_stats;
			$data['duration'] = gmstrftime('%M:%S',$data['duration']);
			unset($data['id']);
			unset($data['start_time']);
			unset($data['battle_id']);
			unset($data['match_id']);
			unset($data['addtime']);				
		}else{
			$data = [];
		}	
		
		$matchinfo['data'] = $data;
		return $matchinfo;
	}
	
	/* LOL比赛详情 */
	public function getMatchInfo($match_id) {
	
        $info=DI()->notorm->lol_match
					->select("id,start_time,league_id,match_id,status,viewnum,team_a_id,team_b_id,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_ids,battle_list")
					->where('match_id=?',$match_id)
					->fetchOne();
		if(!$info) return false;			
		$list[0] = $info;
		$res= $this->getmatchbaseinfo($list);
		
		//更新观看次数
		DI()->notorm->lol_match
				->where("match_id = '{$match_id}'")
				->update( array('viewnum' => new NotORM_Literal("viewnum + 1") ) );
				
		$matchinfo = $res['data'][0];		
		
        $lines=DI()->notorm->lol_match_live
					->select("*")
					->where('match_id=?',$match_id)
					->fetchAll();
		if(!$lines)	$lines=[];		
		$matchinfo['line'] = $lines;		
		return $matchinfo;
	}
	
	/* LOL赛事查询 */
	public function getLOLMatchListByStat($status,$qdate) {
		$nowTime = strtotime(date('Y-m-d'));
		$starttime = $nowTime - 15*24*3600;
		$endtime = $nowTime + 15*24*3600;
		$where = " start_time>$starttime and start_time<$endtime and status='$status' ";
		$qtime = strtotime($qdate);
		if($qtime < time() && $status!=2){
			$qdate = date('Y-m-d');
		}
		if($status==0 || $status==2){
			$where .= " and FROM_UNIXTIME(start_time, '%Y-%m-%d')='$qdate'";
		}

        $info=DI()->notorm->lol_match
            ->queryAll("select id,start_time,league_id,match_id,status,viewnum,team_a_id,team_b_id,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_ids,battle_list from cmf_lol_match where $where order by start_time asc");

		$list = $this->getmatchbaseinfo($info);
		
		$matchlist = $list['data'];
		$matchids = $list['matchids'];
		$battleids = $list['battleids'];
		
		//获取早盘指数
		$indexarr = [];
		if($matchids){
			$indexes = DI()->notorm->lol_match_bet_info
				->select("*")
				->where("match_id in (".implode(',',$matchids).") and bet_type='1' and board_num=0 and source='uwinmax'")
				->fetchAll();				
		}
	    if(!$indexes) $indexes=[];
		foreach($indexes as $key=>$val){
			$options = json_decode($val['options'],true);
			$indexarr[$val['match_id']]['team_a_odds'] = $options[0]['odds'];
			$indexarr[$val['match_id']]['team_b_odds'] = $options[1]['odds'];			
		}
		
		//获取比赛双方的击杀数
		$battlearr = [];
		if($battleids){
			$battles = DI()->notorm->lol_match_live_battle
				->select("*")
				->where("battle_id in (".implode(',',$battleids).")")
				->fetchAll();			
		}else{
			$battles = [];
		}

		foreach($battles as $key=>$val){
			$team_stats = json_decode($val['team_stats'],true); 
			foreach($team_stats as $k=>$v){
				$battlearr[$val['match_id']][$v['team_id']] = $v['kill_count'];
			}		
		}
		

		
		//组装数据
		foreach($matchlist as $key=>$val){
			$matchlist[$key]['team_a_odds']=$indexarr[$val['match_id']]['team_a_odds']?$indexarr[$val['match_id']]['team_a_odds']:0;
			$matchlist[$key]['team_b_odds']=$indexarr[$val['match_id']]['team_b_odds']?$indexarr[$val['match_id']]['team_b_odds']:0;
			$matchlist[$key]['team_a_kill_count']=$battlearr[$val['match_id']][$val['team_a_id']]?$battlearr[$val['match_id']][$val['team_a_id']]:0;
			$matchlist[$key]['team_b_kill_count']=$battlearr[$val['match_id']][$val['team_b_id']]?$battlearr[$val['match_id']][$val['team_b_id']]:0;
		}
		
		return $matchlist;			
	}

    /* PC LOL赛事 */
    public function getPCLOLMatchList()
    {
        $starttime = strtotime(date('Y-m-d')) + 48*3600;
        $info=DI()->notorm->lol_match
            ->queryAll("select id,start_time,league_id,match_id,status,viewnum,team_a_id,team_b_id,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_ids,battle_list from cmf_lol_match where start_time<$starttime order by start_time asc");
        $list = $this->getmatchbaseinfo($info);

        return $list;

    }

	/* LOL赛事 */
	public function getLOLMatchList() {
		$endtime = strtotime(date('Y-m-d')) + 24*3600*15;
		$starttime = strtotime(date('Y-m-d'));
        $info=DI()->notorm->lol_match
            ->queryAll("select id,start_time,league_id,match_id,status,viewnum,team_a_id,team_b_id,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_ids,battle_list from cmf_lol_match where start_time>$starttime and start_time<$endtime and status in (0,1,2) order by start_time asc");
		if(!$info) return [];
		
		$list = $this->getmatchbaseinfo($info);

		$matchlist = $list['data'];
		$matchids = $list['matchids'];
		$battleids = $list['battleids'];
		
		//获取早盘指数
		$indexarr = [];
		$indexes = DI()->notorm->lol_match_bet_info
			->select("*")
			->where("match_id in (".implode(',',$matchids).") and bet_type='1' and board_num=0 and source='uwinmax'")
			->fetchAll();		
		foreach($indexes as $key=>$val){
			$options = json_decode($val['options'],true);
			$indexarr[$val['match_id']]['team_a_odds'] = $options[0]['odds'];
			$indexarr[$val['match_id']]['team_b_odds'] = $options[1]['odds'];			
		}
		
		//获取比赛双方的击杀数
		$battlearr = [];
		if($battleids){
			$battles = DI()->notorm->lol_match_live_battle
				->select("*")
				->where("battle_id in (".implode(',',$battleids).")")
				->fetchAll();			
		}else{
			$battles = [];
		}

		foreach($battles as $key=>$val){
			$team_stats = json_decode($val['team_stats'],true); 
			foreach($team_stats as $k=>$v){
				$battlearr[$val['match_id']][$v['team_id']] = $v['kill_count'];
			}		
		}
		
		//组装数据
		foreach($matchlist as $key=>$val){
			$matchlist[$key]['team_a_odds']=$indexarr[$val['match_id']]['team_a_odds']?$indexarr[$val['match_id']]['team_a_odds']:0;
			$matchlist[$key]['team_b_odds']=$indexarr[$val['match_id']]['team_b_odds']?$indexarr[$val['match_id']]['team_b_odds']:0;
			$matchlist[$key]['team_a_kill_count']=$battlearr[$val['match_id']][$val['team_a_id']]?$battlearr[$val['match_id']][$val['team_a_id']]:0;
			$matchlist[$key]['team_b_kill_count']=$battlearr[$val['match_id']][$val['team_b_id']]?$battlearr[$val['match_id']][$val['team_b_id']]:0;
		}
		
        $result = $this->getmatchlistbygroup($matchlist);    
		return $result;
	}


	/* 按今日 明日 过去对比赛进行分组 */	
	public function getmatchlistbygroup($info){
		$res = [];	
		$today1=[];//正在进行的
		$today0=[];//今天还未开始的
		$today2=[];//今天已经结束的		
		foreach($info as $key=>$val){
			$starttime = $val['start_time'];		
			if($starttime == date('Y-m-d') && $val['status']==1){		
				$today1[]=$val;
			}elseif($starttime == date('Y-m-d') && $val['status']==0){
				$today0[]=$val;
			}elseif($starttime == date('Y-m-d') && $val['status']==2){
				$today2[]=$val;
			}elseif($val['status']==0 && $starttime == date("Y-m-d",strtotime("+1 day"))){
				$res['tomorrow'][]=$val;
			}elseif($val['status']==0){
				$res['over'][]=$val;
			}
		}
		$res['today'] = array_merge_recursive($today1,$today0,$today2);
		return $res;
	}
	
	/* 处理比赛基本信息 */		
	public function getmatchbaseinfo($info){
		$res = [];
		$matchids = [];
		$battleids = [];
		foreach($info as $key=>$val){
			$starttime = $val['start_time'];
			$val['start_time'] =  date('Y-m-d',$starttime);;
			$val['startdate'] = date('m-d',$starttime);
			$val['starttime'] = date('H:i',$starttime);

            if($val['viewnum']<100){
                $val['viewnum'] = 2000 + $val['viewnum'];
            }elseif($val['viewnum']>=100 && $val['viewnum']<1000){
                $val['viewnum'] = 100000+$val['viewnum'];
            }else{
                $val['viewnum'] = 1000000+$val['viewnum'];
            }				
			
			$matchids[] =  $val['match_id'];
			
			$league = json_decode($val['league'],true);
			$val['league_name'] = $league['name'];
			unset($val['league']);
			
			$battle_list = json_decode($val['battle_list'],true);
			if($battle_list){
				$val['battle_current_index'] = count($battle_list);				
				$val['battle_current_id'] = $battle_list[count($battle_list)-1]['battle_id'];
				$val['battle_duration']=$battle_list[count($battle_list)-1]['duration'];
				$battleids[] = $val['battle_current_id'];
			}else{
				$battle_list = json_decode($val['battle_ids'],true);
				
				$val['battle_current_index'] = count($battle_list);//小局第几场
				$val['battle_current_id']=$battle_list[count($battle_list)-1];
				$val['battle_duration']=0;//战役时长
				if($battle_list[count($battle_list)-1]) $battleids[] = $battle_list[count($battle_list)-1];
			}

			$team_a = json_decode($val['team_a'],true);
			$val['team_a_name'] = $team_a['short_name']?$team_a['short_name']:$team_a['name'];
			$val['team_a_logo'] = $team_a['logo'];
			unset($val['team_a']);

			$team_b = json_decode($val['team_b'],true);
			$val['team_b_name'] = $team_b['short_name']?$team_b['short_name']:$team_b['name'];
			$val['team_b_logo'] = $team_b['logo'];
			unset($val['team_b']);			
			$val['league_cat']="英雄联盟";
			$val['match_type']=1;
			
			$res[]=$val;
		}
		$info['data'] = $res;
		$info['matchids'] = $matchids;
		$info['battleids'] = $battleids;

		return $info;
	}

    public function getPCMatchList($page = 1, $limit = 15)
    {
        if($page<1){
            $page=1;
        }
		$start=($page-1)*$limit;
        $today = strtotime('today');
        $tomorrow = strtotime('tomorrow');

        $extra = " AND start_time >= $today AND start_time <= $tomorrow";
        $matches = DI()->notorm->lol_match
					->select("id,battle_list,start_time,match_id,status,league,team_a_id,team_b_id,team_a,team_b,round_son_name")
//                    ->where("status < 3 $extra")  //@todo 测试阶段，为了对接数据，先把这给注释掉了
                    ->order('FIELD(status,1,0,3,2,4), start_time ASC') //排序执行中，未开赛，已结束
                    ->limit($start,$limit)
					->fetchAll();
		if(!$matches) return false;

		$matchID = implode(',', array_column($matches, 'match_id'));
		$battles = DI()->notorm->lol_match_live_battle
					->select('match_id,battle_id,team_stats')
                    ->where('match_id IN('.$matchID.')')
					->fetchAll();

		$bets = DI()->notorm->lol_match_bet_info
            ->select('match_id,options')
            ->where('match_id IN('.$matchID.') AND bet_type = 1') //就获取全胜
            ->fetchAll();

        foreach ($bets as $k => $bet) {
            $options = json_decode($bet['options'], true);
            foreach ($options as $option) {
                if (!isset($option['team_id'])) continue;
                $bets['b:'.$bet['match_id'].'t:'.$option['team_id']] = $option;
                unset($bets[$k]);
            }
        }
        $response = [];

        foreach ($battles as $k => $battle) {
            $battle['team_stats'] = isset($battle['team_stats']) ? json_decode($battle['team_stats'], true) : [];
            foreach ($battle['team_stats'] as $key => $stats) {
                $battle['team_stats']['t:'.$stats['team_id']] = $stats;
                unset($battle['team_stats'][$key]);
            }
            $battles['m:'.$battle['match_id']] = $battle;
            unset($battles[$k]);
        }

        foreach ($matches as $match) {
            $match['league'] = json_decode($match['league'], true);
            $match['team_a'] = json_decode($match['team_a'], true);
            $match['team_b'] = json_decode($match['team_b'], true);
            $match['battle_list'] = json_decode($match['battle_list'], true);

            list($startDate, $startTime) = explode(" ", date('m-d H:i',$match['start_time']));

            $battle = $battles['m:'.$match['match_id']];
            $teamStats = $battle['team_stats'] ?? null;
            $response[] = [
                'league_logo' => $match['league']['logo'],
                'league_name' => $match['league']['name'],
                'start_date' => $startDate,
                'start_time' => $startTime,
                'status' => $match['status'],
                'team_a_id' => $match['team_a_id'],
                'team_a_name' => $match['team_a']['name'],
                'team_a_logo' => $match['team_a']['logo'],
                'team_a_money' => $teamStats['t:'.$match['team_a_id']]['money'] ?? '0',
                'team_a_kill_count' => $teamStats['t:'.$match['team_a_id']]['kill_count'] ?? '0',
                'team_a_tower_success_count' => $teamStats['t:'.$match['team_a_id']]['tower_success_count'] ?? '0',
                'team_a_small_dragon_count' => $teamStats['t:'.$match['team_a_id']]['small_dragon_count'] ?? '0',
                'team_a_odds' => $bets['b:'.$battle['match_id'].'t:'.$match['team_a_id']]['odds'] ?? '0',
                'team_b_id' => $match['team_b_id'],
                'team_b_name' => $match['team_b']['name'],
                'team_b_logo' => $match['team_b']['logo'],
                'team_b_money' => $teamStats['t:'.$match['team_b_id']]['money'] ?? '0',
                'team_b_kill_count' => $teamStats['t:'.$match['team_b_id']]['kill_count'] ?? '0',
                'team_b_tower_success_count' => $teamStats['t:'.$match['team_b_id']]['tower_success_count'] ?? '0',
                'team_b_small_dragon_count' => $teamStats['t:'.$match['team_b_id']]['small_dragon_count'] ?? '0',
                'team_b_odds' => $bets['b:'.$battle['match_id'].'t:'.$match['team_b_id']]['odds'] ?? '0',
                'battle_current_index' => count($match['battle_list'])?count($match['battle_list']):1,
            ];
        }

        return $response;
	}
}
