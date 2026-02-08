<?php
/**
 * ===赛事===
 */
class Api_Match extends PhalApi_Api {

	public function getRules() {
		return array(
            'pushMatchData' => array(
				'match_id' => array('name' => 'match_id', 'type' => 'int', 'require' => true, 'default'=>'0', 'desc' => '比赛ID'),
			),		
            'getMatchPlayer' => array(
				'match_id' => array('name' => 'match_id', 'type' => 'int', 'require' => true, 'default'=>'0', 'desc' => '比赛ID'),		
				'range_type' => array('name' => 'range_type', 'type' => 'int', 'require' => false, 'default'=>'1', 'desc' => '默认返回10场数据，0：最近5场比赛；1:最近10场比赛。统计场数不满5/10场，则有多少场统计多少场'),						
			),			
            'getMatchOutline' => array(
				'match_id' => array('name' => 'match_id', 'type' => 'int', 'require' => true, 'default'=>'0', 'desc' => '比赛ID'),		
				'range_type' => array('name' => 'range_type', 'type' => 'int', 'require' => false, 'default'=>'1', 'desc' => '默认返回10场数据，0：最近5场比赛；1:最近10场比赛。统计场数不满5/10场，则有多少场统计多少场'),						
			),		
            'getMatchAnalysis' => array(
				'match_id' => array('name' => 'match_id', 'type' => 'int', 'require' => true, 'default'=>'0', 'desc' => '比赛ID'),		
				'range_type' => array('name' => 'range_type', 'type' => 'int', 'require' => false, 'default'=>'1', 'desc' => '默认返回10场数据，0：最近5场比赛；1:最近10场比赛。统计场数不满5/10场，则有多少场统计多少场'),						
			),		
            'getMatchIndex' => array(
				'match_id' => array('name' => 'match_id', 'type' => 'int', 'require' => true, 'default'=>'0', 'desc' => '比赛ID'),
				'board_num' => array('name' => 'board_num', 'type' => 'int', 'require' => false, 'default'=>'0', 'desc' => '第几局,0为全局'),
				'handicap' => array('name' => 'handicap', 'type' => 'int', 'require' => false, 'default'=>'0', 'desc' => '0为早盘指数1为滚球指数'),				
			),			
            'getMatchData' => array(
				'match_id' => array('name' => 'match_id', 'type' => 'int', 'require' => true, 'default'=>'0', 'desc' => '比赛ID'),
				'index' => array('name' => 'index', 'type' => 'int', 'require' => false, 'default'=>'1', 'desc' => '第几局'),				
			),			
            'getMatchInfo' => array(
				'match_id' => array('name' => 'match_id', 'type' => 'int', 'require' => true, 'default'=>'0', 'desc' => '比赛ID'),
			),		
            'getLOLMatchList' => array(
				//'catid' => array('name' => 'catid', 'type' => 'int', 'default'=>'1', 'desc' => '比赛分类'),
			),
            'getCSGOMatchList' => array(
				//'catid' => array('name' => 'catid', 'type' => 'int', 'default'=>'1', 'desc' => '比赛分类'),
			),
            'getLOLMatchListByStat' => array(
				'status' => array('name' => 'status', 'type' => 'int', 'default'=>'1', 'desc' => '比赛状态 0:未开始 1:进行中 2:已结束 3:延期 4:已删除'),
				'qdate' => array('name' => 'qdate', 'type' => 'string', 'require' => false, 'desc' => '查询日期'),			
			),	
            'getCSGOMatchListByStat' => array(
				'status' => array('name' => 'status', 'type' => 'int', 'default'=>'1', 'desc' => '比赛状态 0:未开始 1:进行中 2:已结束 3:延期 4:已删除'),
				'qdate' => array('name' => 'qdate', 'type' => 'string', 'default'=>'', 'require' => false, 'desc' => '查询日期'),			
			),			
            'getPCMatchList' => array(
                'limit' => array('name' => 'limit', 'type' => 'int', 'default'=>'8' ,'desc' => '每页记录数'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),
		);
	}	


	/**
	 * LOL比赛数据推送
	 * author sukura 
	 * @desc 用于 LOL比赛数据推送
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类		 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期		 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO
     * @return int info[]['team_a_kill_count'] 主队击杀
     * @return int info[]['team_b_kill_count'] 客队击杀
     * @return double info[]['team_a_odds'] 主队赔率
     * @return double info[]['team_b_odds'] 客队赔率
     * @return int info[]['viewnum'] 观看次数	 
     * @return array info[]['index'][] 对局指数
     * @return string info[]['index']['source'] 来源网站
	 * @return int info[]['index']['options'][]['team_id'] 战队id	
     * @return double info[]['index']['options'][]['odds'] 赔率
     * @return double info[]['index']['options'][]['init_handicap'] 初始赔率	 
     * @return array info[]['data'][] 对局数据	 
     * @return int info[]['data']['duration'] 比赛时间	 
     * @return int info[]['data']['index'] 小局第几场 从1开始
     * @return array info[]['data']['economic_diff'][] 经济差
     * @return int info[]['data']['economic_diff']['time'] 游戏时间(s)
     * @return int info[]['data']['economic_diff']['diff'] 蓝色方与红色方的差值
     * @return array info[]['data']['xp_diff'][] 经验差
     * @return int info[]['data']['xp_diff']['time'] 游戏时间(s)
     * @return int info[]['data']['xp_diff']['diff'] 蓝色方与红色方的差值
     * @return array info[]['data']['creep_score_diff'][] 经济差
     * @return int info[]['data']['creep_score_diff']['time'] 游戏时间(s)
     * @return int info[]['data']['creep_score_diff']['diff'] 蓝色方与红色方的差值	
     * @return array info[]['data']['player_stats'][] 选手对局数据
     * @return string info[]['data']['player_stats'][]['player_name'] 选手姓名
     * @return string info[]['data']['player_stats'][]['player_avatar'] 选手头像	 
     * @return string info[]['data']['player_stats'][]['hero_name'] 英雄头像	 
     * @return int info[]['data']['player_stats'][]['kill_count'] 杀敌数	 
     * @return int info[]['data']['player_stats'][]['death_count'] 死亡数	 
     * @return int info[]['data']['player_stats'][]['assist_count'] 助攻数
     * @return int info[]['data']['player_stats'][]['last_hit_count'] 补刀数	
     * @return double info[]['data']['player_stats'][]['kda'] KDA	
     * @return int info[]['data']['player_stats'][]['money_count'] 金钱数	
     * @return array info[]['data']['player_stats'][]['equip_ids'][] 装备IDs	
     * @return array info[]['data']['player_stats'][]['skill_ids'][] 技能IDs	
     * @return array info[]['data']['team_stats'][] 对局战队数据
     * @return string info[]['data']['team_stats'][]['team_name'] 战队名称
     * @return string info[]['data']['team_stats'][]['team_avatar'] 战队头像	
     * @return int info[]['data']['team_stats'][]['big_dragon_count'] 大龙数	
     * @return int info[]['data']['team_stats'][]['small_dragon_count'] 小龙数	
     * @return int info[]['data']['team_stats'][]['tower_success_count'] 推塔数	
     * @return int info[]['data']['team_stats'][]['inhibitor_success_count'] 摧毁水晶数	
     * @return string info[]['data']['team_stats'][]['side'] 队伍阵营blue=蓝色方; red=红色方	
     * @return array info[]['data']['team_stats'][]['pick_list'][] 选用英雄列表
     * @return int info[]['data']['team_stats'][]['pick_list'][]['hero_id'] 英雄ID
     * @return string info[]['data']['team_stats'][]['pick_list'][]['name'] 名称
     * @return string info[]['data']['team_stats'][]['pick_list'][]['avatar'] 头像
     * @return array info[]['data']['team_stats'][]['ban_list'][] 禁用英雄列表
     * @return int info[]['data']['team_stats'][]['ban_list'][]['hero_id'] 英雄ID
     * @return string info[]['data']['team_stats'][]['ban_list'][]['name'] 名称
     * @return string info[]['data']['team_stats'][]['ban_list'][]['avatar'] 头像
     * @return boolean info[]['data']['team_stats'][]['is_win'] 是否获胜(true/false)
     * @return string msg 提示信息
	 */
	public function pushMatchData() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$match_id = checkNull($this->match_id);
		
		$domain = new Domain_Match();
		$key='pushMatchData_'.$match_id;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->pushMatchData($match_id);
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}

	
	/**
	 * LOL比赛选手查询
	 * author sukura	 
	 * @desc 用于 LOL比赛选手查询
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类	 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期	 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO
     * @return int info[]['team_a_kill_count'] 主队击杀
     * @return int info[]['team_b_kill_count'] 客队击杀
     * @return double info[]['team_a_odds'] 主队赔率
     * @return double info[]['team_b_odds'] 客队赔率
     * @return int info[]['viewnum'] 观看次数
     * @return array info[]['team_a_player_stats'][] A队选手对位数据
     * @return array info[]['team_b_player_stats'][] B队选手对位数据	 
     * @return string info[]['team_a_player_stats']['player_name'] 选手名称	
     * @return int info[]['team_a_player_stats']['team_id'] 战队ID	
     * @return string info[]['team_a_player_stats']['position'] 位置	
     * @return string info[]['team_a_player_stats']['avatar'] 头像	
     * @return int info[]['team_a_player_stats']['game_count'] 出场次数
     * @return double info[]['team_a_player_stats']['win_rate'] 胜率
     * @return double info[]['team_a_player_stats']['kda'] KDA
     * @return double info[]['team_a_player_stats']['golds_per_min'] 分均经济
     * @return double info[]['team_a_player_stats']['damage_per_min'] 分均伤害
     * @return double info[]['team_a_player_stats']['offered_rate'] 参团率	 
     * @return array info[]['team_a_player_stats']['hero_pick_stats'][] 常用英雄信息
     * @return int info[]['team_a_player_stats']['hero_pick_stats'][]['hero_id'] 英雄ID
     * @return string info[]['team_a_player_stats']['hero_pick_stats'][]['hero_name'] 英雄名称		
     * @return string info[]['team_a_player_stats']['hero_pick_stats'][]['hero_name_en'] 英雄英文名称		
     * @return string info[]['team_a_player_stats']['hero_pick_stats'][]['hero_logo'] 英雄头像		
     * @return int info[]['team_a_player_stats']['hero_pick_stats'][]['hero_game_count'] 英雄出场次数
     * @return int info[]['team_a_player_stats']['hero_pick_stats'][]['hero_win_count'] 英雄获胜场数		 
     * @return string msg 提示信息
	 */
	public function getMatchPlayer() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$match_id = checkNull($this->match_id);
		$range_type = checkNull($this->range_type);
		
		$domain = new Domain_Match();
		$key='getMatchPlayer_'.$match_id.'_'.$range_type;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getMatchPlayer($match_id,$range_type);
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}
	
	/**
	 * LOL比赛阵容查询
	 * author sukura	 
	 * @desc 用于 LOL比赛阵容查询
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类	 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期	 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO
     * @return int info[]['team_a_kill_count'] 主队击杀
     * @return int info[]['team_b_kill_count'] 客队击杀
     * @return double info[]['team_a_odds'] 主队赔率
     * @return double info[]['team_b_odds'] 客队赔率
     * @return int info[]['viewnum'] 观看次数
     * @return array info[]['data'][] 对局阵容	 
     * @return string msg 提示信息
	 */
	public function getMatchOutline() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$match_id = checkNull($this->match_id);
		$range_type = checkNull($this->range_type);
		
		$domain = new Domain_Match();
		$key='getMatchOutline_'.$match_id.'_'.$range_type;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getMatchOutline($match_id,$range_type);
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}

	
	/**
	 * LOL比赛分析查询
	 * author sukura	 
	 * @desc 用于 LOL比赛分析查询
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类	 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期	 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO
     * @return int info[]['team_a_kill_count'] 主队击杀
     * @return int info[]['team_b_kill_count'] 客队击杀
     * @return double info[]['team_a_odds'] 主队赔率
     * @return double info[]['team_b_odds'] 客队赔率
     * @return int info[]['viewnum'] 观看次数	 
     * @return int info[]['data']['team_a_id'] 战队A id
     * @return int info[]['data']['team_b_id'] 战队B id
     * @return array info[]['data']['battle_stats'] 对局数据	
     * @return int info[]['data']['battle_stats']['team_a_win_count'] A队胜利次数
     * @return int info[]['data']['battle_stats']['team_b_win_count'] B队胜利次数
     * @return int info[]['data']['battle_stats']['team_a_lose_count'] A队失败次数
     * @return int info[]['data']['battle_stats']['team_b_lose_count'] B队失败次数
     * @return int info[]['data']['battle_stats']['team_a_kills_per_game'] A队场均击杀
     * @return int info[]['data']['battle_stats']['team_b_kills_per_game'] B队场均击杀	 
     * @return int info[]['data']['battle_stats']['team_a_five_kill_count'] A队五杀次数
     * @return int info[]['data']['battle_stats']['team_b_five_kill_count'] B队五杀次数	 
     * @return int info[]['data']['battle_stats']['team_a_first_blood_count'] A队一血次数
     * @return int info[]['data']['battle_stats']['team_b_first_blood_count'] B队一血次数
     * @return int info[]['data']['battle_stats']['team_a_first_tower_count'] A队一塔次数
     * @return int info[]['data']['battle_stats']['team_b_first_tower_count'] B队一塔次数
     * @return int info[]['data']['battle_stats']['team_a_first_dragon_count'] A队首小龙次数
     * @return int info[]['data']['battle_stats']['team_b_first_dragon_count'] B队首小龙次数	
     * @return int info[]['data']['battle_stats']['team_a_first_nash_count'] A队首大龙次数	
     * @return int info[]['data']['battle_stats']['team_b_first_nash_count'] B队首大龙次数	 
     * @return array info[]['data']['battle_stats']['records'][] 双方交战记录	
     * @return string info[]['data']['battle_stats']['records'][]['match_time'] 比赛日期
     * @return string info[]['data']['battle_stats']['records'][]['team_a_name'] A战队名称	
     * @return string info[]['data']['battle_stats']['records'][]['team_b_name'] B战队名称	
     * @return string info[]['data']['battle_stats']['records'][]['team_a_logo'] A战队LOGO	
     * @return string info[]['data']['battle_stats']['records'][]['team_b_logo'] A战队LOGO	
     * @return int info[]['data']['battle_stats']['records'][]['team_a_score'] A战队得分	
     * @return int info[]['data']['battle_stats']['records'][]['team_b_score'] A战队得分	 
     * @return array info[]['data']['team_a_recent_stats']['matches'][] 	A队近期战绩	
     * @return string info[]['data']['team_a_recent_stats']['matches'][]['match_time'] 比赛日期
     * @return string info[]['data']['team_a_recent_stats']['matches'][]['team_a_name'] A战队名称	
     * @return string info[]['data']['team_a_recent_stats']['matches'][]['team_b_name'] B战队名称	
     * @return string info[]['data']['team_a_recent_stats']['matches'][]['team_a_logo'] A战队LOGO	
     * @return string info[]['data']['team_a_recent_stats']['matches'][]['team_b_logo'] A战队LOGO	
     * @return int info[]['data']['team_a_recent_stats']['matches'][]['team_a_score'] A战队得分	
     * @return int info[]['data']['team_a_recent_stats']['matches'][]['team_b_score'] A战队得分
     * @return int info[]['data']['team_a_recent_stats']['win_rate'] A战队胜场数
     * @return int info[]['data']['team_a_recent_stats']['lost_rate'] A战队失败场数		 
     * @return array info[]['data']['team_b_recent_stats']['matches'][] B队近期战绩	
     * @return string info[]['data']['team_b_recent_stats']['matches'][]['match_time'] 比赛日期
     * @return string info[]['data']['team_b_recent_stats']['matches'][]['team_a_name'] A战队名称	
     * @return string info[]['data']['team_b_recent_stats']['matches'][]['team_b_name'] B战队名称	
     * @return string info[]['data']['team_b_recent_stats']['matches'][]['team_a_logo'] A战队LOGO	
     * @return string info[]['data']['team_b_recent_stats']['matches'][]['team_b_logo'] A战队LOGO	
     * @return int info[]['data']['team_b_recent_stats']['matches'][]['team_a_score'] A战队得分	
     * @return int info[]['data']['team_b_recent_stats']['matches'][]['team_b_score'] B战队得分
     * @return int info[]['data']['team_b_recent_stats']['win_rate'] B战队胜场数
     * @return int info[]['data']['team_b_recent_stats']['lost_rate'] B战队失败场数	 
     * @return string msg 提示信息
	 */
	public function getMatchAnalysis() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$match_id = checkNull($this->match_id);
		$range_type = checkNull($this->range_type);
		
		$domain = new Domain_Match();
		$key='getMatchAnalysis_'.$match_id.'_'.$range_type;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getMatchAnalysis($match_id,$range_type);
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}
	
	/**
	 * LOL比赛指数查询
	 * author sukura	 
	 * @desc 用于 LOL比赛指数查询
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类		 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期	 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO
     * @return int info[]['team_a_kill_count'] 主队击杀
     * @return int info[]['team_b_kill_count'] 客队击杀
     * @return double info[]['team_a_odds'] 主队赔率
     * @return double info[]['team_b_odds'] 客队赔率
     * @return int info[]['viewnum'] 观看次数
     * @return array info[]['data'][] 对局指数
     * @return string info[]['data']['source'] 来源网站
	 * @return int info[]['data']['options'][]['team_id'] 战队id	
     * @return double info[]['data']['options'][]['odds'] 即时盘赔率
     * @return double info[]['data']['options'][]['init_handicap'] 初始盘赔率	 
     * @return string msg 提示信息
	 */
	public function getMatchIndex() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$match_id = checkNull($this->match_id);
		$board_num = checkNull($this->board_num);
		$handicap = checkNull($this->handicap);
		
		$domain = new Domain_Match();
		$key='getMatchIndex_'.$match_id.'_'.$board_num.'_'.$handicap;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getMatchIndex($match_id,$board_num,$handicap);
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}
	
	/**
	 * LOL比赛数据查询
	 * author sukura	 
	 * @desc 用于 LOL比赛数据查询
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类		 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期		 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO
     * @return int info[]['team_a_kill_count'] 主队击杀
     * @return int info[]['team_b_kill_count'] 客队击杀
     * @return double info[]['team_a_odds'] 主队赔率
     * @return double info[]['team_b_odds'] 客队赔率
     * @return int info[]['viewnum'] 观看次数
     * @return array info[]['data'][] 对局数据	 
     * @return int info[]['data']['duration'] 比赛时间	 
     * @return int info[]['data']['index'] 小局第几场 从1开始
     * @return array info[]['data']['economic_diff'][] 经济差
     * @return int info[]['data']['economic_diff']['time'] 游戏时间(s)
     * @return int info[]['data']['economic_diff']['diff'] 蓝色方与红色方的差值
     * @return array info[]['data']['xp_diff'][] 经验差
     * @return int info[]['data']['xp_diff']['time'] 游戏时间(s)
     * @return int info[]['data']['xp_diff']['diff'] 蓝色方与红色方的差值
     * @return array info[]['data']['creep_score_diff'][] 经济差
     * @return int info[]['data']['creep_score_diff']['time'] 游戏时间(s)
     * @return int info[]['data']['creep_score_diff']['diff'] 蓝色方与红色方的差值	
     * @return array info[]['data']['player_stats'][] 选手对局数据
     * @return string info[]['data']['player_stats'][]['player_name'] 选手姓名
     * @return string info[]['data']['player_stats'][]['player_avatar'] 选手头像	 
     * @return string info[]['data']['player_stats'][]['hero_name'] 英雄头像	 
     * @return int info[]['data']['player_stats'][]['kill_count'] 杀敌数	 
     * @return int info[]['data']['player_stats'][]['death_count'] 死亡数	 
     * @return int info[]['data']['player_stats'][]['assist_count'] 助攻数
     * @return int info[]['data']['player_stats'][]['last_hit_count'] 补刀数	
     * @return double info[]['data']['player_stats'][]['kda'] KDA	
     * @return int info[]['data']['player_stats'][]['money_count'] 金钱数	
     * @return array info[]['data']['player_stats'][]['equip_ids'][] 装备IDs	
     * @return array info[]['data']['player_stats'][]['skill_ids'][] 技能IDs	
     * @return array info[]['data']['team_stats'][] 对局战队数据
     * @return string info[]['data']['team_stats'][]['team_name'] 战队名称
     * @return string info[]['data']['team_stats'][]['team_avatar'] 战队头像	
	 * @return int info[]['data']['team_stats'][]['kill_count'] 击杀数	
     * @return int info[]['data']['team_stats'][]['big_dragon_count'] 大龙数	
     * @return int info[]['data']['team_stats'][]['small_dragon_count'] 小龙数	
     * @return int info[]['data']['team_stats'][]['tower_success_count'] 推塔数	
     * @return int info[]['data']['team_stats'][]['inhibitor_success_count'] 摧毁水晶数	
     * @return string info[]['data']['team_stats'][]['side'] 队伍阵营blue=蓝色方; red=红色方	
     * @return array info[]['data']['team_stats'][]['pick_list'][] 选用英雄列表
     * @return int info[]['data']['team_stats'][]['pick_list'][]['hero_id'] 英雄ID
     * @return string info[]['data']['team_stats'][]['pick_list'][]['name'] 名称
     * @return string info[]['data']['team_stats'][]['pick_list'][]['avatar'] 头像
     * @return array info[]['data']['team_stats'][]['ban_list'][] 禁用英雄列表
     * @return int info[]['data']['team_stats'][]['ban_list'][]['hero_id'] 英雄ID
     * @return string info[]['data']['team_stats'][]['ban_list'][]['name'] 名称
     * @return string info[]['data']['team_stats'][]['ban_list'][]['avatar'] 头像
     * @return boolean info[]['data']['team_stats'][]['is_win'] 是否获胜(true/false)
     * @return string msg 提示信息
	 */
	public function getMatchData() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$match_id = checkNull($this->match_id);
		$index = checkNull($this->index);
		
		$domain = new Domain_Match();
		$key='getMatchData_'.$match_id.'_'.$index;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getMatchData($match_id,$index);
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}
	
	/**
	 * LOL比赛详情查询
	 * author sukura	 
	 * @desc 用于 LOL比赛详情查询
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类		 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期		 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO
     * @return array info[]['line'][] 直播线路列表	 
     * @return int info[]['viewnum'] 观看次数	 
     * @return string msg 提示信息
	 */
	public function getMatchInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$match_id=checkNull($this->match_id);
		
		$domain = new Domain_Match();
		$key='getMatchInfo_'.$match_id;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getMatchInfo($match_id);
			if($info){
				setCaches($key,$info,3600); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}	

	/**
	 * LOL赛事列表查询
	 * author sukura	 
	 * @desc 用于 LOL赛事列表查询
     * @return int code 操作码，0表示成功
     * @return array info[] 赛事列表
     * @return int info[]['id'] id
     * @return string info[]['league_cat'] 联赛分类		 
     * @return int info[]['league_id'] 联赛ID
     * @return int info[]['match_id'] 比赛ID
     * @return int info[]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info[]['team_a_score'] 主队得分
     * @return int info[]['team_b_score'] 客队得分
     * @return string info[]['round_son_name'] 子轮次名次
     * @return string info[]['startdate'] 比赛开始日期
     * @return string info[]['starttime'] 比赛开始时间
     * @return string info[]['league_name'] 联赛名称
     * @return int info[]['battle_current_index'] 当前对局局数
     * @return int info[]['battle_current_id'] 当前对局id
     * @return int info[]['battle_duration'] 当前对局时长
     * @return string info[]['start_time'] 完整的开始日期		 
     * @return string info[]['team_a_name'] 主队ID
     * @return string info[]['team_a_logo'] 主队LOGO
     * @return string info[]['team_b_name'] 客队ID
     * @return string info[]['team_b_logo'] 客队LOGO	 
     * @return int info[]['team_a_kill_count'] 主队击杀
     * @return int info[]['team_b_kill_count'] 客队击杀
     * @return int info[]['viewnum'] 观看次数		 
     * @return double info[]['team_a_odds'] 主队赔率
     * @return double info[]['team_b_odds'] 客队赔率
     * @return string msg 提示信息
	 */
	public function getLOLMatchListByStat() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$qdate=checkNull($this->qdate);
		$status=checkNull($this->status);
		
		$domain = new Domain_Match();
		$key='getLOLMatchListByStat_'.$status.'_'.$qdate;
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getLOLMatchListByStat($status,$qdate);
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}		
	
	/**
	 * LOL赛事列表-全部
	 * author sukura	 
	 * @desc 用于 获取LOL赛事列表-全部
     * @return int code 操作码，0表示成功
     * @return array info[] 
     * @return array info['today'][] 今日比赛列表
     * @return array info['tomorrow'][] 明日比赛列表
     * @return array info['over'][] 已结束比赛列表	 
     * @return int info['today'][]['id'] id
     * @return string info[]['league_cat'] 联赛分类		 
     * @return int info['today'][]['league_id'] 联赛ID
     * @return int info['today'][]['match_id'] 比赛ID
     * @return int info['today'][]['status'] 比赛状态 0:未开始 1:进行中 2:已结束  3:已延期 4:已删除
     * @return int info['today'][]['team_a_score'] 主队得分
     * @return int info['today'][]['team_b_score'] 客队得分
     * @return string info['today'][]['round_son_name'] 子轮次名次
     * @return string info['today'][]['startdate'] 比赛开始日期
     * @return string info['today'][]['starttime'] 比赛开始时间
     * @return string info['today'][]['league_name'] 联赛名称
     * @return int info['today'][]['battle_current_index'] 当前对局局数
     * @return int info['today'][]['battle_current_id'] 当前对局id
     * @return int info['today'][]['battle_duration'] 当前对局时长
     * @return string info['today'][]['start_time'] 完整的开始日期	 
     * @return string info['today'][]['team_a_name'] 主队ID
     * @return string info['today'][]['team_a_logo'] 主队LOGO
     * @return string info['today'][]['team_b_name'] 客队ID
     * @return string info['today'][]['team_b_logo'] 客队LOGO	 
     * @return int info['today'][]['team_a_kill_count'] 主队击杀
     * @return int info['today'][]['team_b_kill_count'] 客队击杀
     * @return int info['today'][]['viewnum'] 观看次数	 
     * @return double info['today'][]['team_a_odds'] 主队赔率
     * @return double info['today'][]['team_b_odds'] 客队赔率
     * @return string msg 提示信息
	 */
	public function getLOLMatchList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$domain = new Domain_Match();
		$key='getLOLMatchList';
		$info=getcaches($key);
		if(!$info){
			$info = $domain->getLOLMatchList();
			if($info){
				setCaches($key,$info,2); 
			}else{
				$rs['code']=1001;
				$rs['msg']='未查到比赛信息';
				return $rs;
			}			
		}
        
		$rs['info']=$info;
		return $rs;			
	}

	/**
	 * LOL赛事列表-全部
	 * author sukura	 
	 * @desc 用于 PC端获取LOL赛事列表-全部
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[]['league_logo'] 联赛Logo
     * @return string info[]['league_name'] 联赛名称
     * @return string info[]['start_date'] 赛事开始日期
     * @return string info[]['start_time'] 赛事开始时间
     * @return string info[]['status'] 赛事状态
     * @return string info[]['team_a_name'] 主队名称
     * @return string info[]['team_a_logo'] 主队logo
     * @return int info[]['team_a_money'] 主队金钱值
     * @return int info[]['team_a_kill_count'] 主队击杀数
     * @return int info[]['team_a_tower_success_count'] 主队推塔数
     * @return int info[]['team_a_small_dragon_count'] 主队小龙数
     * @return float info[]['team_a_odds'] 主队指数
     * @return string info[]['team_b_name'] 客队名称
     * @return string info[]['team_b_logo'] 客队logo
     * @return int info[]['team_b_money'] 客队金钱值
     * @return int info[]['team_b_kill_count'] 客队击杀数
     * @return int info[]['team_b_tower_success_count'] 客队推塔数
     * @return int info[]['team_b_small_dragon_count'] 客队小龙数
     * @return float info[]['team_b_odds'] 客队指数
     * @return int info[]['battle_current_index'] 小居第几场
     */
    public function getPCMatchList()
    {
        $result = (new Domain_Match())->getPCMatchList($this->p, $this->limit);
        return [
            'code' => !$result ? 1001 : 0,
            'msg' => '',
            'info' => $result?$result:[],
        ];
	}
	
}
