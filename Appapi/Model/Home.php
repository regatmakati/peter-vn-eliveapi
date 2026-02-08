<?php
if (!session_id()) session_start();

class Model_Home extends PhalApi_Model_NotORM
{
    protected $live_fields = 'uid,title,city,stream,pull,thumb,isvideo,type,type_val,goodnum,anyway,starttime,isshop,game_action,notice,match_id,pic_full_url';

    CONST TYPE_LOL = 1;             //电竞
    CONST TYPE_BASKETBALL = 2;      //篮球
    CONST TYPE_FOOTBALL = 3;        //足球

    CONST FOOTBALL_SELF_STATE_FIRST_HALF = 10;
    CONST FOOTBALL_SELF_STATE_MIDFIELD = 20;
    CONST FOOTBALL_SELF_STATE_SECOND_HALF = 30;
    CONST FOOTBALL_SELF_STATE_OVERTIME = 40;
    CONST FOOTBALL_SELF_STATE_KICK = 50;
    CONST FOOTBALL_SELF_STATE_NOT_START = 60;
    CONST FOOTBALL_SELF_STATE_DETERMINE = 70;
    CONST FOOTBALL_SELF_STATE_DELAY = 80;
    CONST FOOTBALL_SELF_STATE_INTERRUPT = 90;
    CONST FOOTBALL_SELF_STATE_HALF_CUT = 100;
    CONST FOOTBALL_SELF_STATE_CANCEL = 110;
    CONST FOOTBALL_SELF_STATE_FINISH = 120;

    CONST BASKETBALL_SELF_STATE_MATCH = 0;
    CONST BASKETBALL_SELF_STATE_FIRST = 10;
    CONST BASKETBALL_SELF_STATE_FIRST_FINISH = 20;
    CONST BASKETBALL_SELF_STATE_SECOND = 30;
    CONST BASKETBALL_SELF_STATE_SECOND_FINISH = 40;
    CONST BASKETBALL_SELF_STATE_THREE = 50;
    CONST BASKETBALL_SELF_STATE_THREE_FINISH = 60;
    CONST BASKETBALL_SELF_STATE_FOUR = 70;
    CONST BASKETBALL_SELF_STATE_OVERTIME = 80;
    CONST BASKETBALL_SELF_STATE_NOT_START = 90;
    CONST BASKETBALL_SELF_STATE_DETERMINE = 100;
    CONST BASKETBALL_SELF_STATE_DELAY = 110;
    CONST BASKETBALL_SELF_STATE_INTERRUPT = 120;
    CONST BASKETBALL_SELF_STATE_HALF_CUT = 130;
    CONST BASKETBALL_SELF_STATE_CANCEL = 140;
    CONST BASKETBALL_SELF_STATE_FINISH = 150;

    private function aesEncrypt($data)
    {

        $key = hash('MD5', "123123", true);
        $iv = hash('MD5', "123123", true);

        $data = openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($data);
    }

    private function getLiveUrl($urls)
    {
        if (empty($urls)) return [];
        $prefix = "http://gameplay.hruui.com";
        $suffix = ".flv";
        foreach ($urls as $key => $liveUrl) {
            $urls[$key] = $this->aesEncrypt($prefix . $liveUrl . $suffix);
        }
        return $urls;
    }

    private function getFootballPlayingSelfStates()
    {
        return [
            FOOTBALL_SELF_STATE_FIRST_HALF,
            FOOTBALL_SELF_STATE_MIDFIELD,
            FOOTBALL_SELF_STATE_SECOND_HALF,
            FOOTBALL_SELF_STATE_OVERTIME,
            FOOTBALL_SELF_STATE_KICK,
        ];
    }

    private function getFootballStateMap()
    {
        return [
            FOOTBALL_SELF_STATE_DELAY => '推迟',
            FOOTBALL_SELF_STATE_INTERRUPT => '中断',
            FOOTBALL_SELF_STATE_HALF_CUT => '腰斩',
            FOOTBALL_SELF_STATE_DETERMINE => '待定',
            FOOTBALL_SELF_STATE_CANCEL => '取消',
            FOOTBALL_SELF_STATE_FINISH => '完场',
            FOOTBALL_SELF_STATE_NOT_START => '未开赛',
            FOOTBALL_SELF_STATE_FIRST_HALF => '上半场',
            FOOTBALL_SELF_STATE_MIDFIELD => '中场',
            FOOTBALL_SELF_STATE_SECOND_HALF => '下半场',
            FOOTBALL_SELF_STATE_OVERTIME => '加时',
            FOOTBALL_SELF_STATE_KICK => '点球',
        ];
    }

    private function getFootballIsPlaying($state)
    {
        //进行中
        if (in_array($state, $this->getFootballPlayingSelfStates())) return 1;
        //已完场
        if (FOOTBALL_SELF_STATE_FINISH == $state) return 2;
        //未开赛
        return 0;
    }

    private function getBasketballPlayingSelfStates()
    {
        return [
            BASKETBALL_SELF_STATE_FIRST,
            BASKETBALL_SELF_STATE_FIRST_FINISH,
            BASKETBALL_SELF_STATE_SECOND,
            BASKETBALL_SELF_STATE_SECOND_FINISH,
            BASKETBALL_SELF_STATE_THREE,
            BASKETBALL_SELF_STATE_THREE_FINISH,
            BASKETBALL_SELF_STATE_FOUR,
            BASKETBALL_SELF_STATE_OVERTIME,
        ];
    }

    private function getBasketballStateMap()
    {
        return [
            BASKETBALL_SELF_STATE_MATCH => '未开赛',
            BASKETBALL_SELF_STATE_NOT_START => '未开赛',
            BASKETBALL_SELF_STATE_FIRST => '第一节',
            BASKETBALL_SELF_STATE_FIRST_FINISH => '第一节完',
            BASKETBALL_SELF_STATE_SECOND => '第二节',
            BASKETBALL_SELF_STATE_SECOND_FINISH => '第二节完',
            BASKETBALL_SELF_STATE_THREE => '第三节',
            BASKETBALL_SELF_STATE_THREE_FINISH => '第三节完',
            BASKETBALL_SELF_STATE_FOUR => '第四节',
            BASKETBALL_SELF_STATE_OVERTIME => '加时',
            BASKETBALL_SELF_STATE_FINISH => '完场',
            BASKETBALL_SELF_STATE_INTERRUPT => '中断',
            BASKETBALL_SELF_STATE_CANCEL => '取消',
            BASKETBALL_SELF_STATE_DELAY => '延期',
            BASKETBALL_SELF_STATE_HALF_CUT => '腰斩',
            BASKETBALL_SELF_STATE_DETERMINE => '待定',
        ];
    }

    private function getBasketballIsPlaying($state)
    {
        //进行中
        if (in_array($state, $this->getBasketballPlayingSelfStates())) return 1;
        //已完场
        if (BASKETBALL_SELF_STATE_FINISH == $state) return 2;
        //未开赛
        return 0;
    }

    /* 首页赛事列表 */
    public function getSlideEvents()
    {
        $starttime = strtotime(date('Y-m-d'));
        $endtime = $starttime + 2 * 24 * 3600;
        $where = " start_time>$starttime  and start_time<$endtime and (status=0 or status=1) ";
        $info = DI()->notorm->lol_match
            ->queryAll("select id,start_time,league_id,match_id,status,viewnum,team_a_score,team_b_score,league,team_a,team_b,round_son_name,battle_list from cmf_lol_match where $where order by start_time asc");
        $res = $this->getmatchbaseinfo($info);
        $starttime = strtotime(date("Y-m-d 08:00:00"));
        $endtime = strtotime("+15 day");
        $where = " where a.matchStartTime>=$starttime  and a.matchStartTime<=$endtime and IF(a.state IN(10,20,30,40,50), a.live_url!='', 1=1)  ";
        //获取足球赛事信息
        $sql = "select a.id,a.matchStartTime as start_time,a.matchId as match_id,b.leagueNameCnShort as league_name,if(a.state in (10,20,30,40,50),1,0) as status,a.viewnum,a.homeScore as team_a_score,a.awayScore as team_b_score,a.live_url,c.nameCn as team_a_name,c.logo as team_a_logo ,d.nameCn as team_b_name,d.logo as team_b_logo from cmf_sports_football_match a left join cmf_sports_football_league b on a.leagueId=b.leagueId left join cmf_sports_football_team c on a.homeId=c.teamId left join cmf_sports_football_team d on a.awayId=d.teamId  $where    order by a.state asc, a.matchStartTime asc limit 8";
        $fbinfo = DI()->notorm->sports_football_match
            ->queryAll($sql);

        $fbres = $this->getFootballMatchBaseInfo($fbinfo, 1);

        $where = " where a.matchStartTime>$starttime  and a.matchStartTime<$endtime and IF(state IN(10,20,30,40,50,60,70,80), live_url!='', 1=1) ";
        //获取蓝球赛事信息
        $sql = "select a.id,a.matchStartTime as start_time,a.matchId as match_id,b.leagueNameCnShort as league_name,if(a.state in (10,20,30,40,50,60,70,80),1,0) as status,a.viewnum,a.homeScore as team_a_score,a.awayScore as team_b_score,a.live_url,c.nameCn as team_a_name,c.logo as team_a_logo ,d.nameCn as team_b_name,d.logo as team_b_logo from cmf_sports_basketball_match a left join cmf_sports_basketball_league b on a.leagueId=b.leagueId left join cmf_sports_basketball_team c on a.homeId=c.teamId left join cmf_sports_basketball_team d on a.awayId=d.teamId  $where  order by a.state asc , a.matchStartTime asc limit 8";
        $bkinfo = DI()->notorm->sports_football_match
            ->queryAll($sql);
        $bkres = $this->getFootballMatchBaseInfo($bkinfo, 2);

        $res = array_merge($res, $fbres, $bkres);
        return $res;
    }

    /* 处理足球比赛基本信息 */
    public function getFootballMatchBaseInfo($info, $catid = 1)
    {
        $res = [];
        foreach ($info as $key => $val) {
            $starttime = $val['start_time'];
            $val['start_time'] = date('Y-m-d', $starttime);
            $val['startdate'] = date('m-d', $starttime);
            unset($val['start_time']);

            $tommorow = date("Y-m-d", strtotime("+1 day"));
            if ($tommorow == date("Y-m-d", $starttime)) {
                $val['starttime'] = '明天 ' . date('H:i', $starttime);
            } else {
                $val['starttime'] = date('H:i', $starttime);
            }

            if ($val['viewnum'] < 100) {
                $val['viewnum'] = 2000 + $val['viewnum'];
            } elseif ($val['viewnum'] >= 100 && $val['viewnum'] < 1000) {
                $val['viewnum'] = 100000 + $val['viewnum'];
            } else {
                $val['viewnum'] = 1000000 + $val['viewnum'];
            }

            $val['battle_current_index'] = 1;//小局第几场
            $val['battle_current_id'] = 0;
            $val['battle_duration'] = 0;//战役时长
            if ($catid == 1) {
                $val['status'] = $this->getFootballIsPlaying($val['status']);
                $val['state_str'] = $this->getFootballStateMap()[$val['status']];
                $val['live_url'] = $this->getLiveUrl([$val['live_url']]);
                $val['league_cat'] = "足球";
                $val['match_type'] = 3;
            } else {
                $val['status'] = $this->getBasketballIsPlaying($val['status']);
                $val['state_str'] = $this->getBasketballStateMap()[$val['status']];
                $val['live_url'] = $this->getLiveUrl([$val['live_url']]);
                $val['league_cat'] = "篮球";
                $val['match_type'] = 2;
            }

            $res[] = $val;
        }
        return $res;
    }

    public function getDownload()
    {
        $info = DI()->notorm->option
            ->where("option_name='site_info'")
            ->fetchOne();
        $res = json_decode($info['option_value'], true);
        return $res;
    }


    /* 轮播 */
    public function getSlide()
    {

        $rs = DI()->notorm->slide_item
            ->select("image as slide_pic,url as slide_url")
            ->where("status='1' and slide_id='2' ")
            ->order("list_order asc")
            ->fetchAll();
        foreach ($rs as $k => $v) {
            $rs[$k]['slide_pic'] = get_upload_path($v['slide_pic']);
        }

        return $rs;
    }

    /* PC首页广告 */
    public function getAdv()
    {

        $rs = DI()->notorm->slide_item
            ->select("image as slide_pic,url as slide_url")
            ->where("status='1' and slide_id='6' ")
            ->order("list_order asc")
            ->fetchAll();
        foreach ($rs as $k => $v) {
            $rs[$k]['slide_pic'] = get_upload_path($v['slide_pic']);
        }

        return $rs;
    }

    /* 热门 */
    public function getHot($userID, bool $isLoggedIn, $p, $limit)
    {
        return $this->getRecommendedStream($userID, $liveuid = 0, $isLoggedIn, $p, $limit, 'starttime', 'asc');
    }

    /* 关注列表 */
    public function getFollow($uid, $p)
    {
        $rs = array(
            'title' => '你关注的主播没有开播',
            'des' => '赶快去看看其他主播的直播吧',
            'list' => array(),
        );
        if ($p < 1) {
            $p = 1;
        }
        $result = array();
        $pnum = 50;
        $start = ($p - 1) * $pnum;

        $touid = DI()->notorm->user_attention
            ->select("touid")
            ->where('uid=?', $uid)
            ->fetchAll();

        if (!$touid) {
            return $rs;
        }

        $rs['title'] = '你关注的主播没有开播';
        $rs['des'] = '赶快去看看其他主播的直播吧';
        $where = " islive='1' ";
        if ($p != 1) {
            $endtime = $_SESSION['follow_starttime'];
            if ($endtime) {
                $start = 0;
                $where .= " and starttime < {$endtime}";
            }

        }

        $touids = array_column($touid, "touid");
        $touidss = implode(",", $touids);
        $where .= " and uid in ({$touidss})";
        $result = DI()->notorm->live
            ->select($this->live_fields)
            ->where($where)
            ->order("starttime desc")
            ->limit(0, $pnum)
            ->fetchAll();

        foreach ($result as $k => $v) {

            $v = handleLive($v);

            $result[$k] = $v;
        }

        if ($result) {
            $last = end($result);
            $_SESSION['follow_starttime'] = $last['starttime'];
        }

        $rs['list'] = $result;

        return $rs;
    }

    /* 最新 */
    public function getNew($lng, $lat, $p)
    {
        if ($p < 1) {
            $p = 1;
        }
        $pnum = 50;
        $start = ($p - 1) * $pnum;
        $where = " islive='1' ";

        if ($p != 1) {
            $endtime = $_SESSION['new_starttime'];
            if ($endtime) {
                $where .= " and starttime < {$endtime}";
            }
        }

        $result = DI()->notorm->live
            ->select($this->live_fields . ',lng,lat')
            ->where($where)
            ->order("starttime desc")
            ->limit(0, $pnum)
            ->fetchAll();
        foreach ($result as $k => $v) {

            $v = handleLive($v);

            $distance = '好像在火星';
            if ($lng != '' && $lat != '' && $v['lat'] != '' && $v['lng'] != '') {
                $distance = getDistance($lat, $lng, $v['lat'], $v['lng']);
            } else if ($v['city']) {
                $distance = $v['city'];
            }

            $v['distance'] = $distance;
            unset($v['lng']);
            unset($v['lat']);

            $result[$k] = $v;

        }
        if ($result) {
            $last = end($result);
            $_SESSION['new_starttime'] = $last['starttime'];
        }

        return $result;
    }

    /* 搜索 */
    public function search($uid, $key, $p)
    {
        if ($p < 1) {
            $p = 1;
        }
        $pnum = 50;
        $start = ($p - 1) * $pnum;
        $where = ' user_type="2" and ( id=? or user_nicename like ?  or goodnum like ? ) and id!=?';
        if ($p != 1) {
            $id = $_SESSION['search'];
            if ($id) {
                $where .= " and id < {$id}";
            }
        }

        $result = DI()->notorm->user
            ->select("id,user_nicename,avatar,sex,signature,consumption,votestotal")
            ->where($where, $key, '%' . $key . '%', '%' . $key . '%', $uid)
            ->order("id desc")
            ->limit($start, $pnum)
            ->fetchAll();
        foreach ($result as $k => $v) {
            $v['level'] = (string)getLevel($v['consumption']);
            $v['level_anchor'] = (string)getLevelAnchor($v['votestotal']);
            $v['isattention'] = (string)isAttention($uid, $v['id']);
            $v['avatar'] = get_upload_path($v['avatar']);
            unset($v['consumption']);

            $result[$k] = $v;
        }

        if ($result) {
            $last = end($result);
            $_SESSION['search'] = $last['id'];
        }

        return $result;
    }

    /* 附近 */
    public function getNearby($lng, $lat, $p)
    {
        if ($p < 1) {
            $p = 1;
        }
        $pnum = 50;
        $start = ($p - 1) * $pnum;
        $where = " islive='1' and lng!='' and lat!='' ";

        $result = DI()->notorm->live
            ->select($this->live_fields . ",getDistance('{$lat}','{$lng}',lat,lng) as distance,province")
            ->where($where)
            ->order("distance asc")
            ->limit($start, $pnum)
            ->fetchAll();
        foreach ($result as $k => $v) {

            $v = handleLive($v);

            if ($v['distance'] > 1000) {
                $v['distance'] = 1000;
            }
            $v['distance'] = $v['distance'] . 'km';

            $result[$k] = $v;
        }

        return $result;
    }


    /* 推荐 */
    public function getRecommend()
    {

        $result = DI()->notorm->user
            ->select("id,user_nicename,avatar,avatar_thumb")
            ->where("isrecommend='1'")
            ->order("rand()")
            ->limit(4)
            ->fetchAll();
        foreach ($result as $k => $v) {
            $v['avatar'] = get_upload_path($v['avatar']);
            $v['avatar_thumb'] = get_upload_path($v['avatar_thumb']);
            $fans = getFans($v['id']);
            $v['fans'] = '粉丝 · ' . $fans;

            $result[$k] = $v;
        }
        return $result;
    }

    /* 关注推荐 */
    public function attentRecommend($uid, $touids)
    {
        //$users=$this->getRecommend();
        //$users=explode(',',$touids);
        //file_put_contents('./attentRecommend.txt',date('Y-m-d H:i:s').' 提交参数信息 touids:'.$touids."\r\n",FILE_APPEND);
        $users = preg_split('/,|，/', $touids);
        foreach ($users as $k => $v) {
            $touid = $v;
            //file_put_contents('./attentRecommend.txt',date('Y-m-d H:i:s').' 提交参数信息 touid:'.$touid."\r\n",FILE_APPEND);
            if ($touid && !isAttention($uid, $touid)) {
                DI()->notorm->user_black
                    ->where('uid=? and touid=?', $uid, $touid)
                    ->delete();
                DI()->notorm->user_attention
                    ->insert(array("uid" => $uid, "touid" => $touid));
            }

        }
        return 1;
    }

    /*获取收益排行榜*/
    public function profitList($uid, $type, $p)
    {
        if ($p < 1) {
            $p = 1;
        }
        $pnum = 50;
        $start = ($p - 1) * $pnum;

        switch ($type) {
            case 'day':
                //获取今天开始结束时间
                $dayStart = strtotime(date("Y-m-d"));
                $dayEnd = strtotime(date("Y-m-d 23:59:59"));
                $where = " addtime >={$dayStart} and addtime<={$dayEnd} and ";

                break;

            case 'week':
                $w = date('w');
                //获取本周开始日期，如果$w是0，则表示周日，减去 6 天 
                $first = 1;
                //周一
                $week = date('Y-m-d H:i:s', strtotime(date("Ymd") . "-" . ($w ? $w - $first : 6) . ' days'));
                $week_start = strtotime(date("Ymd") . "-" . ($w ? $w - $first : 6) . ' days');

                //本周结束日期 
                //周天
                $week_end = strtotime("{$week} +1 week") - 1;

                $where = " addtime >={$week_start} and addtime<={$week_end} and ";

                break;

            case 'month':
                //本月第一天
                $month = date('Y-m-d', strtotime(date("Ym") . '01'));
                $month_start = strtotime(date("Ym") . '01');

                //本月最后一天
                $month_end = strtotime("{$month} +1 month") - 1;

                $where = " addtime >={$month_start} and addtime<={$month_end} and ";

                break;

            case 'total':
                $where = " ";
                break;

            default:
                //获取今天开始结束时间
                $dayStart = strtotime(date("Y-m-d"));
                $dayEnd = strtotime(date("Y-m-d 23:59:59"));
                $where = " addtime >={$dayStart} and addtime<={$dayEnd} and ";
                break;
        }


        $where .= " action in (1,2)";

        $result = DI()->notorm->user_voterecord
            ->select('sum(total) as totalcoin, uid')
            ->where($where)
            ->group('uid')
            ->order('totalcoin desc')
            ->limit($start, $pnum)
            ->fetchAll();

        foreach ($result as $k => $v) {
            $userinfo = getUserInfo($v['uid']);
            $v['avatar'] = $userinfo['avatar'];
            $v['avatar_thumb'] = $userinfo['avatar_thumb'];
            $v['user_nicename'] = $userinfo['user_nicename'];
            $v['sex'] = $userinfo['sex'];
            $v['level'] = $userinfo['level'];
            $v['level_anchor'] = $userinfo['level_anchor'];
            $v['totalcoin'] = (string)intval($v['totalcoin']);

            $v['isAttention'] = isAttention($uid, $v['uid']);//判断当前用户是否关注了该主播

            $result[$k] = $v;
        }

        return $result;
    }


    /*获取消费排行榜*/
    public function consumeList($uid, $type, $p)
    {
        if ($p < 1) {
            $p = 1;
        }
        $pnum = 50;
        $start = ($p - 1) * $pnum;

        switch ($type) {
            case 'day':
                //获取今天开始结束时间
                $dayStart = strtotime(date("Y-m-d"));
                $dayEnd = strtotime(date("Y-m-d 23:59:59"));
                $where = " addtime >={$dayStart} and addtime<={$dayEnd} and ";

                break;

            case 'week':
                $w = date('w');
                //获取本周开始日期，如果$w是0，则表示周日，减去 6 天 
                $first = 1;
                //周一
                $week = date('Y-m-d H:i:s', strtotime(date("Ymd") . "-" . ($w ? $w - $first : 6) . ' days'));
                $week_start = strtotime(date("Ymd") . "-" . ($w ? $w - $first : 6) . ' days');

                //本周结束日期 
                //周天
                $week_end = strtotime("{$week} +1 week") - 1;

                $where = " addtime >={$week_start} and addtime<={$week_end} and ";

                break;

            case 'month':
                //本月第一天
                $month = date('Y-m-d', strtotime(date("Ym") . '01'));
                $month_start = strtotime(date("Ym") . '01');

                //本月最后一天
                $month_end = strtotime("{$month} +1 month") - 1;

                $where = " addtime >={$month_start} and addtime<={$month_end} and ";

                break;

            case 'total':
                $where = " ";
                break;

            default:
                //获取今天开始结束时间
                $dayStart = strtotime(date("Y-m-d"));
                $dayEnd = strtotime(date("Y-m-d 23:59:59"));
                $where = " addtime >={$dayStart} and addtime<={$dayEnd} and ";
                break;
        }

        $where .= " type=0 and action in ('1','2')";

        $result = DI()->notorm->user_coinrecord
            ->select('sum(totalcoin) as totalcoin, uid')
            ->where($where)
            ->group('uid')
            ->order('totalcoin desc')
            ->limit($start, $pnum)
            ->fetchAll();

        foreach ($result as $k => $v) {
            $userinfo = getUserInfo($v['uid']);
            $v['avatar'] = $userinfo['avatar'];
            $v['avatar_thumb'] = $userinfo['avatar_thumb'];
            $v['user_nicename'] = $userinfo['user_nicename'];
            $v['sex'] = $userinfo['sex'];
            $v['level'] = $userinfo['level'];
            $v['level_anchor'] = $userinfo['level_anchor'];

            $v['isAttention'] = isAttention($uid, $v['uid']);//判断当前用户是否关注了该主播

            $result[$k] = $v;
        }

        return $result;
    }

    /* 分类下直播 */
    public function getClassLive($liveclassid, $p)
    {
        if ($p < 1) {
            $p = 1;
        }
        $pnum = 50;
        //$start=($p-1)*$pnum;
        $start = 0;
        $where = " islive='1' and liveclassid={$liveclassid} ";

        if ($p != 1) {
            $endtime = $_SESSION['getClassLive_starttime'];
            if ($endtime) {
                $where .= " and starttime < {$endtime}";
            }

        }
        $last_starttime = 0;
        $result = DI()->notorm->live
            ->select($this->live_fields)
            ->where($where)
            ->order("starttime desc")
            ->limit(0, $pnum)
            ->fetchAll();
        foreach ($result as $k => $v) {
            $v = handleLive($v);
            $result[$k] = $v;
        }
        if ($result) {
            $last = end($result);
            $_SESSION['getClassLive_starttime'] = $last['starttime'];
        }

        return $result;
    }

    /* 推荐直播 */
    public function getRecommendLive($limit, $p)
    {
        if ($p < 1) {
            $p = 1;
        }
        $start = ($p - 1) * $limit;
        //$start=0;
        $where = " islive='1' and isrecommend=1 and isoff='0' ";

        if ($p != 1) {
            $endtime = $_SESSION['getClassLive_starttime'];
            if ($endtime) {
                $where .= " and starttime < {$endtime}";
            }

        }
        $result = DI()->notorm->live
            ->select($this->live_fields)
            ->where($where)
            ->order("txzb_order desc,recom_sort desc")
            ->limit($start, $limit)
            ->fetchAll();
        foreach ($result as $k => $v) {
            $v = handleLive($v);
            $result[$k] = $v;
        }
        if ($result) {
            $last = end($result);
            $_SESSION['getClassLive_starttime'] = $last['starttime'];
        }

        return $result;
    }

    /* 处理比赛基本信息 */
    public function getmatchbaseinfo($info)
    {
        $res = [];
        foreach ($info as $key => $val) {
            $starttime = $val['start_time'];
            $val['start_time'] = date('Y-m-d', $starttime);
            $val['startdate'] = date('m-d', $starttime);
            unset($val['start_time']);

            $tommorow = date("Y-m-d", strtotime("+1 day"));
            if ($tommorow == date("Y-m-d", $starttime)) {
                $val['starttime'] = '明天 ' . date('H:i', $starttime);
            } else {
                $val['starttime'] = date('H:i', $starttime);
            }

            if ($val['viewnum'] < 100) {
                $val['viewnum'] = 2000 + $val['viewnum'];
            } elseif ($val['viewnum'] >= 100 && $val['viewnum'] < 1000) {
                $val['viewnum'] = 100000 + $val['viewnum'];
            } else {
                $val['viewnum'] = 1000000 + $val['viewnum'];
            }

            $league = json_decode($val['league'], true);
            $val['league_name'] = $league['name'];
            unset($val['league']);

            $battle_list = json_decode($val['battle_list'], true);
            if ($battle_list) {
                $val['battle_current_index'] = count($battle_list);
                $val['battle_current_id'] = $battle_list[count($battle_list) - 1]['battle_id'];
                $val['battle_duration'] = $battle_list[count($battle_list) - 1]['duration'];
            } else {
                $val['battle_current_index'] = 1;//小局第几场
                $val['battle_current_id'] = 0;
                $val['battle_duration'] = 0;//战役时长
            }
            unset($val['battle_list']);

            $team_a = json_decode($val['team_a'], true);
            $val['team_a_name'] = $team_a['short_name'];
            $val['team_a_logo'] = $team_a['logo'];
            unset($val['team_a']);

            $team_b = json_decode($val['team_b'], true);
            $val['team_b_name'] = $team_b['short_name'];
            $val['team_b_logo'] = $team_b['logo'];
            unset($val['team_b']);

            $val['league_cat'] = "英雄联盟";
            $val['match_type'] = 1;

            $res[] = $val;
        }
        return $res;
    }


    /* 订阅或取消主播 */
    public function subscribeAnchor($uid, $touid, $type)
    {
        if ($type == 1) {
            $user = DI()->notorm->user
                ->select('id')
                ->where('id=? and user_type="2"', $touid)
                ->fetchOne();
            if (!$user) {
                return 1003;//订阅得用户不存在
            }

            $isexist = DI()->notorm->user_subscribe
                ->select("*")
                ->where('uid=? and touid=?', $uid, $touid)
                ->fetchOne();
            if ($isexist) return 1004;//已订阅
            DI()->notorm->user_subscribe->insert(array("uid" => $uid, "touid" => $touid, 'addtime' => time()));
            return 0;
        } else {
            DI()->notorm->user_subscribe
                ->where('uid=? and touid=?', $uid, $touid)
                ->delete();
            return 0;
        }
    }

    /* 订阅的主播用户ID */
    public function getSubscribe($uid)
    {
        return DI()->notorm->user_subscribe
            ->select('touid')
            ->where('uid=?', $uid)
            ->fetchPairs('id', 'touid');
    }

    /* 通过关键词搜索获取主播列表 */
    public function getAnchorByKeyword($keyword)
    {

        $sql = "select id,user_nicename,avatar,avatar_thumb,viewnum from cmf_user where user_type = 2 and user_nicename like '%$keyword%'";
        $data = DI()->notorm->example->queryAll($sql);

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['avatar'] = get_upload_path($v['avatar']);
                $data[$k]['avatar_thumb'] = get_upload_path($v['avatar_thumb']);
                $data[$k]['stream'] = getLiveStream($v['id']);
            }
        }

        return $data;
    }
	
    /* 随机获取推荐的主播 */
    public function getRecommendAnchor($limit = 4)
    {

        $sql = "select id,user_nicename,avatar,avatar_thumb,viewnum from cmf_user where isrecommend=1 and user_type = 2 order by RAND() limit $limit";
        $data = DI()->notorm->example->queryAll($sql);

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['avatar'] = get_upload_path($v['avatar']);
                $data[$k]['avatar_thumb'] = get_upload_path($v['avatar_thumb']);
                $data[$k]['stream'] = getLiveStream($v['id']);
            }
        }

        return $data;
    }

    /* 按照人气获取推荐的主播 */
    public function getRecommendAnchorOrder($limit)
    {
        $sql = "select id,user_nicename,avatar,avatar_thumb,viewnum from cmf_user where isrecommend=1 and user_type = 2 order by viewnum desc limit $limit";
        $data = DI()->notorm->example->queryAll($sql);

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['avatar'] = get_upload_path($v['avatar']);
                $data[$k]['avatar_thumb'] = get_upload_path($v['avatar_thumb']);
                $data[$k]['stream'] = getLiveStream($v['id']);
            }
        }

        return $data;
    }

    /**
     * @param array $result
     * @param string $item
     * @param string $key
     * @param array $exclude
     * @param string $prefix
     */
    private function modifyResult(&$result, $item, $key, $exclude, $prefix)
    {
        $array = json_decode($item, true);
        $array = array_except($array, $exclude);
        array_walk($array, function ($item, $k, $prefix) use (&$result, $key) {
            $result[$key]["{$prefix}_{$k}"] = $item;
        }, $prefix);
    }

    /**
     * 获取推荐直播，主播直播或租赛事直播
     * @todo 赛事直播尚未完善，是否需要关联赛事，展示进行中的？ 得问问sukura了
     * @param string $userID
     * @param bool $isLoggedIn 是否已登陆
     * @param int $page 页数
     * @param int $limit 每页记录数
     * @param string $field 可排序字段
     * @param string $order 排序 asc,desc,random
     * @param bool $isMatch
     * @return array|false
     */
    public function getRecommendedStream($userID = '', $liveuid = '', bool $isLoggedIn = false, $page = 1, $limit = 15, $field = 'recom_sort', $order = 'asc', bool $isMatch = false)
    {
        $where = " islive= '1' and isoff='0'  and ishot='1' ";

        if ($isMatch) {
            $where .= " and match_id > 0 ";
            if ($liveuid) {
                $where .= " and match_id!='$liveuid' ";
            }
        } else {
            if ($liveuid) {
                $where .= " and uid!='$liveuid' ";
            }
        }

        $order = strtolower($order);
        $field = strtolower($field);
        /**
         * @var array $allowedFields 可排序的字段白名单
         */
        $allowedFields = [
            'recom_sort', //推荐排序
            'starttime', //开播时间
        ];

        /*if (!in_array($field, $allowedFields) && $field !== 'default') {
            $field = 'recom_sort'; //配置默认值
        }

        if ($field === 'default') {
            $field = 'recom_sort desc,hotvotes desc,starttime desc';
            $order = '';
        }*/

        if (strtolower($order) === 'random') {
            $field = '';
            $order = 'RAND()';
        } else {
            $field = 'txzb_order desc,recom_sort desc,isvideo asc';
            $order = '';
        }

        $page = $page < 1 ? 1 : $page;
        $start = ($page - 1) * $limit;

        if ($page == 1) {
            $_SESSION['hot_starttime'] = time();
        }

        //if($page!=0){
//			$endtime=$_SESSION['hot_starttime'];
//            if($endtime){
//                $where.=" and starttime < {$endtime}";
//            }
        //}

        //if($page!=1){
//			$hotvotes=$_SESSION['hot_hotvotes'];
//            if($hotvotes){
//                $where.=" and hotvotes < {$hotvotes}";
//            }else{
//                $where.=" and hotvotes < 0";
//            }
        //}


        $result = DI()->notorm->live
            ->select($this->live_fields . ',hotvotes')
            ->where($where)
            ->order("$field $order")
            ->limit($start, $limit)
            ->fetchAll();

        $count = DI()->notorm->live
            ->where($where)
            ->count();

        $total = ceil($count / $limit);

        if (!$result) return [];

        $result = array_map('handleLive', $result);

        $subscribed = [];
        if ($isLoggedIn) {
            $subscribed = array_flip($this->getSubscribe($userID));
        }

        $configpri = getConfigPri();
        $pull = $configpri['tx_pull'];

        foreach ($result as $k => $item) {
            if ($item['thumb']) {
                $item['thumb'] = get_upload_path($item['thumb']);
            } else {
                $item['thumb'] = get_pic_path($item['pic_full_url']);
            }
			if($item['isvideo']==0){
				//$item['pull'] = "{$pull}/live/{$item['stream']}.flv";
				//$item['pull_h5'] = "{$pull}/live/{$item['stream']}.m3u8";
				$item['pull'] = PrivateKeyA('rtmp', $item['stream'], 0);
				$item['pull_h5'] = str_replace('.flv', '.m3u8', $item['pull']);
			}


            $item['issubscribed'] = ($isLoggedIn && isset($subscribed[$item['uid']])) ? "1" : "0";
            $item['total'] = $total;
            unset($item['pic_full_url']);
            $result[$k] = $item;
        }

//		if($result){
//			$last=end($result);
//			//$_SESSION['hot_starttime']=$last['starttime'];
//			$_SESSION['hot_hotvotes']=$last['hotvotes'];
//		}
//		$result['total'] = $total;
        return $result;
    }

	
    /**
     * 通过关键词搜索获取直播列表
     * @param string $userID
     * @param bool $isLoggedIn 是否已登陆
     * @return array|false
     */
    public function getLiveListBySearch($userID, $isLoggedIn,$keyword)
    {
        $where = " islive= '1' and isoff='0' and title like '%$keyword%'";

        $result = DI()->notorm->live
            ->select($this->live_fields . ',hotvotes')
            ->where($where)
            ->order("starttime desc")
            ->fetchAll();

        $count = DI()->notorm->live
            ->where($where)
            ->count();

        if (!$result) return [];

        $result = array_map('handleLive', $result);

        $subscribed = [];
        if ($isLoggedIn) {
            $subscribed = array_flip($this->getSubscribe($userID));
        }

        $configpri = getConfigPri();
        $pull = $configpri['tx_pull'];

        foreach ($result as $k => $item) {
            if ($item['thumb']) {
                $item['thumb'] = get_upload_path($item['thumb']);
            } else {
                $item['thumb'] = get_pic_path($item['pic_full_url']);
            }
			if($item['isvideo']==0){
				$item['pull'] = "{$pull}/live/{$item['stream']}.flv";
				$item['pull_h5'] = "{$pull}/live/{$item['stream']}.m3u8";				
			}


            $item['issubscribed'] = ($isLoggedIn && isset($subscribed[$item['uid']])) ? "1" : "0";
            unset($item['pic_full_url']);
            $result[$k] = $item;
        }
        return $result;
    }
	

// =============================== ** PC 首页数据 开始 ** =====================//

    /* PC banner轮播 */
    public function getPCBenner($num = 6)
    {
        $rs = DI()->notorm->slide_item
            ->select("image as slide_pic,url as slide_url")
            ->where("status='1' and slide_id='1' ")
            ->order("list_order asc")
            ->fetchAll();
        foreach ($rs as $k => $v) {
            $rs[$k]['slide_pic'] = get_upload_path($v['slide_pic']);
        }

        return $rs;

    }


    /* 热门直播 */
    public function getPCHot($num = 5)
    {
        $todayE = strtotime(date('Y-m-d 23:59:59'));
        $todayS = strtotime(date('Y-m-d 00:00:00'));
        $where = " islive='1' and ishot=1 and starttime >= $todayS and starttime <= {$todayE}";

        $result = DI()->notorm->live
            ->select($this->live_fields)
            ->where($where)
            ->order("starttime desc")
            ->limit(0, $num)
            ->fetchAll();
        foreach ($result as $k => $v) {
            $v = handleLive($v);
            $result[$k] = $v;
        }

        return $result;
    }

    /* 公告推荐 */
    public function getPCBulletin($num = 5)
    {
        $result = DI()->notorm->notice
            ->select('id,title,content,addtime')
            ->where("status=1 and type=0")
            ->order('addtime desc')
            ->limit(0, $num)
            ->fetchAll();
        return $result;
    }

    public function getPCNav($num = 5)
    {
        $result = DI()->notorm->notice
            ->select('id,title,content,addtime')
            ->where("status=1 and type=17")
            ->order('addtime desc')
            ->limit(0, $num)
            ->fetchAll();
        return $result;
    }

    /* 主播排行榜 */
    public function getPCAnchorOrder($num = 10)
    {
        // 获取所有的主播
        $user = DI()->notorm->live
            ->select('uid')
            ->where('islive=1')
            ->fetchAll();

        $userId = '';
        foreach ($user as $key => $value) {
            $userId .= $value['uid'] . ',';
        }
        $userId = substr($userId, 0, strlen($userId) - 1);


        $sql = <<<TAG
 select count(UB.id) as attention, UB.touid as uid, U.user_nicename, U.avatar_thumb, U.avatar
from cmf_user_subscribe as UB left join cmf_user as U on U.id = UB.uid where UB.touid in ($userId)
group by UB.touid
order by attention desc
limit $num
TAG;

        $result = DI()->notorm->example->queryAll($sql);

        return $result;

    }

    public function getSlideBackground()
    {
        $data = DI()->notorm->slide_item
            ->select('image')
            ->where("slide_id=5")// 定义ID 5 是幻灯片之背景图
            ->fetchOne();

        if (!empty($data)) {
            $data['image'] = get_upload_path($data['image']);
        }
        return $data;
    }

    // =============================== ** PC 首页数据 结束 ** =====================//

}
