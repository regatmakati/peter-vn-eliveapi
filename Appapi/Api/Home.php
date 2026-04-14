<?php
/**
 * 首页
 */
class Api_Home extends PhalApi_Api
{


	public function getRules() {
		$rules = array(
			'getLiveListBySearch' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'default' => '', 'desc' => '用户token'),
                'keyword' => array('name' => 'keyword', 'type' => 'string', 'require' => true,'desc' => '搜索关键词'),		
			),
			
			'getHot' => array(
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
                'limit' => array('name' => 'limit', 'type' => 'int', 'default'=>'8' ,'desc' => '每页记录数'),
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'default' => '', 'desc' => '用户token'),
			),
			
			'getFollow' => array(
				'uid' => array('name' => 'uid', 'type' => 'int','min'=>1,'require' => true, 'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
			
			'getNew' => array(
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),
			
			'search' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'min'=>1 ,'desc' => '用户ID'),
				'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
			
			'getNearby' => array(
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),
			
			'getRecommend' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => false, 'min'=>1 ,'desc' => '用户ID'),
			),
			
			'attentRecommend' => array(
				'uid' => array('name' => 'uid', 'type' => 'int' ,'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'string', 'desc' => '关注用户ID，多个用,分隔'),
			),
            'profitList'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int','min'=>1,'require' => true, 'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
                'type' => array('name' => 'type', 'type' => 'string', 'default'=>'day' ,'desc' => '参数类型，day表示日榜，week表示周榜，month代表月榜，total代表总榜'),
            ),

            
            'consumeList'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int','min'=>1,'require' => true, 'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
                'type' => array('name' => 'type', 'type' => 'string', 'default'=>'day' ,'desc' => '参数类型，day表示日榜，week表示周榜，month代表月榜，total代表总榜'),
            ),
            
            'getClassLive'=>array(
                'liveclassid' => array('name' => 'liveclassid', 'type' => 'int', 'default'=>'0' ,'desc' => '直播分类ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getRecommendLive'=>array(
                'limit' => array('name' => 'limit', 'type' => 'int', 'default'=>'8' ,'desc' => '每页记录数'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'SubscribeAnchor'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '订阅或取消订阅的用户ID'),
                'type' => array('name' => 'type', 'type' => 'int','min'=>1,'default'=>1, 'desc' => '默认为1，1订阅主播  2取消订阅'),
            ),

            'getRecommendAnchor'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min'=>0 ,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'default' => '', 'desc' => '用户token'),
            ),
            'getAnchorByKeyword'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min'=>0 ,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'default' => '', 'desc' => '用户token'),
                'keyword' => array('name' => 'keyword', 'type' => 'string', 'require' => true,'desc' => '搜索关键词'),				
            ),			
            'getRecommendedStream'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'desc' => '主播用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'default' => '', 'desc' => '用户token'),
                'limit' => array('name' => 'limit', 'type' => 'int', 'default'=>'8' ,'desc' => '每页记录数'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
                'field' => array('name' => 'field', 'type' => 'string', 'default' => 'recom_sort', 'desc' => '可排序字段，支持 recom_sort, starttime'),
                'order' => array('name' => 'order', 'type' => 'string', 'default' => 'desc', 'desc' => '排序，asc, desc, random'),
                'ismatch' => array('name' => 'ismatch', 'type' => 'int', 'min' => 0, 'max' => 1, 'default'=>0 ,'desc' => '是否推荐赛事'),
            ),
		);

        return $rules;
	}

    /**
     * 配置信息
     * @desc 用于获取talk下载地址
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0] 配置信息
     * @return string info[0].talk_download_url_pc TALK PC下载链接
     * @return string info[0].talk_download_url_h5 TALK H5下载链接
     * @return string msg 提示信息
     */
    public function getTalkDownloadUrl() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

		$arr = [];
		$key="getConfig";
		$info=getcaches($key);
		if(!$info){			
			$info = getConfigPub();			
		
		}
		$arr['talk_download_url_pc'] = $info['talk_download_url_pc'];
		$arr['talk_download_url_h5'] = $info['talk_download_url_h5'];
			
		$rs['info'][0] = $arr;
		
        return $rs;
    }		
	
	
    /**
     * 获取首页轮播图及赛事列表
     * @desc 用于获取首页轮播图及赛事列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0]['slide'] 
     * @return string info[0]['slide'][].slide_pic 图片
     * @return string info[0]['slide'][].slide_url 链接
     * @return array info[0]['list'] 赛事列表
     * @return string info[0]['list'][].league_cat 联赛分类		 
     * @return int info[0]['list'][].league_id 游戏分类ID
     * @return int info[0]['list'][].match_id 联赛ID
     * @return string info[0]['list'][].league_name 游戏分类名称
     * @return string info[0]['list'][].match_name 联赛名称
     * @return string info[0]['list'][].logo_a A战队LOGO
     * @return string info[0]['list'][].logo_b B战队LOGO
     * @return string info[0]['list'][].team_name_a A战队名称
     * @return string info[0]['list'][].team_name_b B战队名称	 
     * @return int info[0]['list'][].team_a_score 主队得分
     * @return int info[0]['list'][].team_b_score 客队得分
     * @return int info[0]['list'][].status 比赛中,未开始
     * @return int info[0]['list'][].viewnum 观看次数	 
     * @return string info[0]['list'][].starttime 开始时间
     * @return string info[0]['list'][].startdate 开始时间	 
     * @return string msg 提示信息
     */
    public function getSlideEvents() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Home();
		$key1='getSlide';
		$slide=getcaches($key1);
		if(!$slide){
			$slide = $domain->getSlide();
			setcaches($key1,$slide,2);
		}

		$key2="getSlideEvents";
		$list=getcaches($key2);
		if(!$list){
			$list = $domain->getSlideEvents();
			setCaches($key2,$list,2); 
		}

        $rs['info'][0]['slide'] = $slide;
        $rs['info'][0]['list'] = $list;

        return $rs;
    }
	
    /**
     * 配置信息
     * @desc 用于获取配置信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0] 配置信息
     * @return object info[0].guide 引导页
	 * @return string info[0].guide.switch 开关，0关1开
	 * @return string info[0].guide.type 类型，0图片1视频
	 * @return string info[0].guide.time 图片时间
	 * @return array  info[0].guide.list
	 * @return string info[0].guide.list[].thumb 图片、视频链接
	 * @return string info[0].guide.list[].href 页面链接
     * @return string info[0].prevent_blockade_site 防封域名列表
     * @return string info[0].domain_api_live 直播接口域名
     * @return string info[0].domain_api_sports 体育接口域名
     * @return string info[0].domain_websocket_chat 聊天室域名
     * @return string info[0].domain_websocket_match 赛事通知域名
     * @return string msg 提示信息
     */
    public function getConfig() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

		$key="getConfig";
		$info=getcaches($key);
		if(!$info){			
			$info = getConfigPub();
			
			unset($info['site_url']);
			unset($info['site_seo_title']);
			unset($info['site_seo_keywords']);
			unset($info['site_seo_description']);
			unset($info['site_icp']);
			unset($info['site_gwa']);
			unset($info['site_admin_email']);
			unset($info['site_analytics']);
			unset($info['copyright']);
			unset($info['sina_icon']);
			unset($info['sina_title']);
			unset($info['sina_desc']);
			unset($info['sina_url']);
			unset($info['qq_icon']);
			unset($info['qq_title']);
			unset($info['qq_desc']);
			unset($info['qq_url']);
			unset($info['payment_des']);
			unset($info['share_type'][1]);

			//防封域名
			if (!empty($info['prevent_blockade_site'])) {
				$info['prevent_blockade_site'] = explode(',', $info['prevent_blockade_site']);
			} else {
				$info['prevent_blockade_site'] = [];
			}
			//是否手动配置访问域名
			$httpType = getRequestProtocol();
			$websocketProtocol = str_replace('http', 'ws', $httpType);
			if (empty($info['domain_api_live'])) $info['domain_api_live'] = "{$httpType}://liveapi." . getRequestFirstDomain();
			if (empty($info['domain_api_sports'])) $info['domain_api_sports'] = "{$httpType}://sportsapi." . getRequestFirstDomain();
			if (empty($info['domain_websocket_chat'])) $info['domain_websocket_chat'] = "{$websocketProtocol}://" . "chatsocket." . getRequestFirstDomain();
			if (empty($info['domain_websocket_match'])) $info['domain_websocket_match'] = "{$websocketProtocol}://" . "matchsocket." . getRequestFirstDomain();

			//二维码
			$info['qr_url'] = "{$info['oss_site']}/{$info['qr_url']}";

			$info_pri = getConfigPri();
			
			$list = getLiveClass();
			$videoclasslist = getVideoClass();
			
			$level= getLevelList();
			
			foreach($level as $k=>$v){
				unset($v['level_up']);
				unset($v['addtime']);
				unset($v['id']);
				unset($v['levelname']);
				$level[$k]=$v;
			}
			
			$levelanchor= getLevelAnchorList();
			
			foreach($levelanchor as $k=>$v){
				unset($v['level_up']);
				unset($v['addtime']);
				unset($v['id']);
				unset($v['levelname']);
				$levelanchor[$k]=$v;
			}
			
			$info['liveclass']=$list;
			$info['videoclass']=$videoclasslist;
			
			$info['level']=$level;
			
			$info['levelanchor']=$levelanchor;
			
			$info['tximgfolder']='';//腾讯云图片存储目录
			$info['txvideofolder']='';//腾讯云视频存储目录
			$info['txcloud_appid']='';//腾讯云视频APPID
			$info['txcloud_region']='';//腾讯云视频地区
			$info['txcloud_bucket']='';//腾讯云视频存储桶
			$info['cloudtype']='1';//视频云存储类型
			
			$info['qiniu_domain']=DI()->config->get('app.Qiniu.space_host').'/';//七牛云存储空间地址
			$info['video_audit_switch']=$info_pri['video_audit_switch']; //视频审核是否开启
			
			/* 私信开关 */
			$info['letter_switch']=$info_pri['letter_switch']; //视频审核是否开启

			/* 客服链接 */
			$info['service_switch']=$info_pri['service_switch']; //客服开关			
			$info['service_url']=$info_pri['service_url']; //客服链接
			
			/* 引导页 */
			$domain = new Domain_Guide();
			$guide_info = $domain->getGuide();
			
			$info['guide']=$guide_info;

			$info['huawei'] = getConfigHuawei();
			
			/** 敏感词集合*/

			$info['sensitive_words']=get_sensitives();
			//视频水印图片
			$info['video_watermark']=get_upload_path($info_pri['video_watermark']); //视频审核是否开启
			 
			$info['shopexplain_url']=$info['site']."/portal/page/index?id=38";
			$info['stricker_url']=$info['site']."/portal/page/index?id=39";

			$info['shop_system_name']=$info_pri['shop_system_name']; //系统店铺名称
			setCaches($key,$info,120); 			
		}
		$rs['info'][0] = $info;
		
        return $rs;
    }	

    /**
     * 登录方式开关信息
     * @desc 用于获取登录方式开关信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0].login_type 开启的登录方式
     * @return string info[0].login_type[][0] 登录方式标识

     * @return string msg 提示信息
     */
    public function getLogin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $info = getConfigPub();

        //登录弹框那个地方
        $login_alert=array(
            'title'=>$info['login_alert_title'],
            'content'=>$info['login_alert_content'],
            'login_title'=>$info['login_clause_title'],
            'message'=>array(
                array(
                    'title'=>$info['login_service_title'],
                    'url'=>get_upload_path($info['login_service_url']),
                ),
                array(
                    'title'=>$info['login_private_title'],
                    'url'=>get_upload_path($info['login_private_url']),
                ),
            )
        );

        $login_type=$info['login_type'];
        foreach ($login_type as $k => $v) {
            if($v=='ios'){
                unset($login_type[$k]);
                break;
            }
        }

        $login_type=array_values($login_type);

        $rs['info'][0]['login_alert'] = $login_alert;
        $rs['info'][0]['login_type'] = $login_type;
        $rs['info'][0]['login_type_ios'] = $info['login_type'];

        return $rs;
    }	

    /**
     * 通过关键词搜索获取直播列表
     * @desc 用于通过关键词搜索获取直播列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info 热门直播列表
     * @return string info[].uid 主播id
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].thumb 直播封面
     * @return string info[].isvideo 是否假视频
     * @return string info[].type 直播类型
     * @return string info[].type_val 类型值
     * @return string info[].goodnum 靓号
     * @return string info[].anyway 横竖屏，0表示竖屏，1表示横屏
     * @return string info[].starttime 开播时间
     * @return string info[].isshop 是否开启店铺
     * @return string info[].game_action 游戏类型
     * @return string info[].notice 直播公告
     * @return string info[].hotvotes hotvotes
     * @return string info[].nums 人数
     * @return string info[].viewnum 人气值	 
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].sex 主播性别
     * @return string info[].level 等级
     * @return string info[].level_anchor 主播等级
     * @return string info[].game game
     * @return string info[].issubscribed 是否已订阅
     * @return string msg 提示信息
     */
    public function getLiveListBySearch() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
		$keyword=checkNull($this->keyword);
		
        $checkToken = checkToken($uid,$token);
        $isLoggedIn = $checkToken === 0;

        $domain = new Domain_Home();

		$key2="getLiveListBySearch_".$keyword;
		$list=getcaches($key2);
		if(!$list){
			$list = $domain->getLiveListBySearch($uid, $isLoggedIn,$keyword);
			setCaches($key2,$list,2);
		}

		$rs['info'] = $list;

        return $rs;
    }
	
    /**
     * 获取热门主播
     * @desc 用于获取首页热门主播
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info 热门直播列表
     * @return string info[].uid 主播id
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].thumb 直播封面
     * @return string info[].isvideo 是否假视频
     * @return string info[].type 直播类型
     * @return string info[].type_val 类型值
     * @return string info[].goodnum 靓号
     * @return string info[].anyway 横竖屏，0表示竖屏，1表示横屏
     * @return string info[].starttime 开播时间
     * @return string info[].isshop 是否开启店铺
     * @return string info[].game_action 游戏类型
     * @return string info[].notice 直播公告
     * @return string info[].hotvotes hotvotes
     * @return string info[].nums 人数
     * @return string info[].viewnum 人气值	 
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].sex 主播性别
     * @return string info[].level 等级
     * @return string info[].level_anchor 主播等级
     * @return string info[].game game
     * @return string info[].issubscribed 是否已订阅
     * @return string msg 提示信息
     */
    public function getHot() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $checkToken = checkToken($uid,$token);
        $isLoggedIn = $checkToken === 0;

        $domain = new Domain_Home();
//		$key1='getSlide';
//		$slide=getcaches($key1);
//		if(!$slide){
//			$slide = $domain->getSlide();
//			setcaches($key1,$slide);
//		}

		$key2="getHot_".$this->p.'_'.$this->limit;
		$list=getcaches($key2);
		if(!$list){
			$list = $domain->getHot($uid, $isLoggedIn, $this->p, $this->limit);
			setCaches($key2,$list,60);
		}

		$rs['info'] = $list;

//        $rs['info'][0]['slide'] = $slide;
//        $rs['info'][0]['list'] = $list;

        return $rs;
    }
	
    /**
     * 获取关注主播列表
     * @desc 用于获取用户关注的主播的直播列表
     * @return int code 操作码，0表示成功
     * @return string info[0]['title'] 提示标题
     * @return string info[0]['des'] 提示描述
     * @return array info[0]['list'] 直播列表
     * @return string info[0]['list'][].uid 主播id
     * @return string info[0]['list'][].avatar 主播头像
     * @return string info[0]['list'][].avatar_thumb 头像缩略图
     * @return string info[0]['list'][].user_nicename 直播昵称
     * @return string info[0]['list'][].title 直播标题
     * @return string info[0]['list'][].city 主播位置
     * @return string info[0]['list'][].stream 流名
     * @return string info[0]['list'][].pull 播流地址
     * @return string info[0]['list'][].nums 人数
     * @return string info[0]['list'][].thumb 直播封面
     * @return string info[0]['list'][].level_anchor 主播等级
     * @return string info[0]['list'][].type 直播类型
     * @return string info[0]['list'][].goodnum 靓号
     * @return string msg 提示信息
     */
    public function getFollow() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Home();
        $info = $domain->getFollow($this->uid,$this->p);


        $rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 获取最新主播
     * @desc 用于获取首页最新开播的主播列表
     * @return int code 操作码，0表示成功
     * @return array info 主播列表
     * @return string info[].uid 主播id
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].nums 人数
     * @return string info[].distance 距离
     * @return string info[].thumb 直播封面
     * @return string info[].level_anchor 主播等级
     * @return string info[].type 直播类型
     * @return string info[].goodnum 靓号
     * @return string msg 提示信息
     */
    public function getNew() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$lng=checkNull($this->lng);
		$lat=checkNull($this->lat);
		$p=checkNull($this->p);
		
		if(!$p){
			$p=1;
		}
		
		$key='getNew_'.$p;
		$info=getcaches($key);
		if(!$info){
			$domain = new Domain_Home();
			$info = $domain->getNew($lng,$lat,$p);

			setCaches($key,$info,2);
		}
		
        $rs['info'] = $info;

        return $rs;
    }		
		
	/**
     * 搜索
     * @desc 用于首页搜索会员
     * @return int code 操作码，0表示成功
     * @return array info 会员列表
     * @return string info[].id 用户ID
     * @return string info[].user_nicename 用户昵称
     * @return string info[].avatar 头像
     * @return string info[].sex 性别
     * @return string info[].signature 签名
     * @return string info[].level 等级
     * @return string info[].isattention 是否关注，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function search() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$key=checkNull($this->key);
		$p=checkNull($this->p);
		if($key==''){
			$rs['code'] = 1001;
			$rs['msg'] = "请填写关键词";
			return $rs;
		}
		
		if(!$p){
			$p=1;
		}
		
        $domain = new Domain_Home();
        $info = $domain->search($uid,$key,$p);

        $rs['info'] = $info;

        return $rs;
    }	
	
    /**
     * 获取附近主播
     * @desc 用于获取附近开播的主播列表
     * @return int code 操作码，0表示成功
     * @return array info 主播列表
     * @return string info[].uid 主播id
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].title 直播标题
     * @return string info[].province 省份
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].nums 人数
     * @return string info[].distance 距离
     * @return string info[].thumb 直播封面
     * @return string info[].level_anchor 主播等级
     * @return string info[].type 直播类型
     * @return string info[].goodnum 靓号
     * @return string msg 提示信息
     */
    public function getNearby() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$lng=checkNull($this->lng);
		$lat=checkNull($this->lat);
		$p=checkNull($this->p);
		
		if($lng==''){
			return $rs;
		}
		
		if($lat==''){
			return $rs;
		}
		
		if(!$p){
			$p=1;
		}
		
		$key='getNearby_'.$lng.'_'.$lat.'_'.$p;
		$info=getcaches($key);
		if(!$info){
			$domain = new Domain_Home();
			$info = $domain->getNearby($lng,$lat,$p);

			setcaches($key,$info,2);
		}
		
        $rs['info'] = $info;

        return $rs;
    }	
	
	/**
     * 推荐主播
     * @desc 用于显示推荐主播
     * @return int code 操作码，0表示成功
     * @return array info 会员列表
     * @return string info[].id 用户ID
     * @return string info[].user_nicename 用户昵称
     * @return string info[].avatar 头像
     * @return string info[].fans 粉丝数
     * @return string info[].isattention 是否关注，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function getRecommend() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		
		$key='getRecommend';
		//$info=getcaches($key);
		if(!$info){
			$domain = new Domain_Home();
			$info = $domain->getRecommend();

			setcaches($key,$info,1);
		}
		
		foreach($info as $k=>$v){
			if($uid){
				$info[$k]['isattention']=(string)isAttention($uid,$v['id']);
			}else{
				$info[$k]['isattention'] = 0;
			}
			
		}

        $rs['info'] = $info;

        return $rs;
    }	
	
	/**
     * 关注推荐主播
     * @desc 用于关注推荐主播
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
    public function attentRecommend() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);

        if($uid<1){
            $rs['code'] = 1001;
			$rs['msg'] = "参数错误";
			return $rs;
        }
        if($touid==''){
            $rs['code'] = 1001;
			$rs['msg'] = "请选择要关注的主播";
			return $rs;
        }

		$domain = new Domain_Home();
		$info = $domain->attentRecommend($uid,$touid);

        //$rs['info'] = $info;

        return $rs;
    }

    /**
     * 收益榜单
     * @desc 获取收益榜单
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息 
     * @return array info
     * @return string info[0]['user_nicename'] 主播昵称
     * @return string info[0]['avatar_thumb'] 主播头像
     * @return string info[0]['totalcoin'] 主播钻石数
     * @return string info[0]['uid'] 主播id
     * @return string info[0]['levelAnchor'] 主播等级
     * @return string info[0]['isAttention'] 是否关注主播 0 否 1 是
     **/
    
    public function profitList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $p=checkNull($this->p);
        $type=checkNull($this->type);
        $domain=new Domain_Home();
        $res=$domain->profitList($uid,$type,$p);

        $rs['info']=$res;
        return $rs;
    }

    /**
     * 消费榜单
     * @desc 获取消费榜单
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息 
     * @return array info
     * @return string info[0]['user_nicename'] 用户昵称
     * @return string info[0]['avatar_thumb'] 用户头像
     * @return string info[0]['totalcoin'] 用户钻石数
     * @return string info[0]['uid'] 用户id
     * @return string info[0]['levelAnchor'] 用户等级
     * @return string info[0]['isAttention'] 是否关注用户 0 否 1 是
     **/
    
    public function consumeList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $p=checkNull($this->p);
        $type=checkNull($this->type);
        $domain=new Domain_Home();
        $res=$domain->consumeList($uid,$type,$p);

        $rs['info']=$res;
        return $rs;
    }
    
    /**
     * 获取推荐直播
     * @desc 获取推荐直播
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息 
     * @return array info
     * @return string info[].match_id 赛事ID
     * @return int info[].status  状态 {0:未开始 1:进行中 2:已结束  3:已延期 4:已删除}
     * @return string info[].round_name 轮次名称
     * @return string info[].round_son_name 子轮次名次
     * @return string info[].bo 赛制
     * @return int info[].addtime 添加时间
     * @return int info[].viewnum 播放次数
     * @return string info[].team_a_score 主队得分
     * @return string info[].team_b_score 主队得分
     * @return string info[].startdate 主队得分
     * @return string info[].starttime 主队得分
     * @return string info[].team_a_name 主队名称
     * @return string info[].team_a_logo 主队图
     * @return string info[].team_a_short_name 主队名简称
     * @return string info[].team_b_name 主队名称
     * @return string info[].team_b_logo 主队图
     * @return string info[].team_b_short_name 主队名简称

     *
     **/
    
    public function getRecommendLive(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $limit=checkNull($this->limit);
        $p=checkNull($this->p);

        $domain=new Domain_Home();
        $res=$domain->getRecommendLive($limit,$p);

        $rs['info']=$res;
        return $rs;
    }
	
    /**
     * 获取分类下的直播
     * @desc 获取分类下的直播
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息 
     * @return array info
     * @return string info[].uid 主播id
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].nums 人数
     * @return string info[].distance 距离
     * @return string info[].thumb 直播封面
     * @return string info[].level_anchor 主播等级
     * @return string info[].type 直播类型
     * @return string info[].goodnum 靓号
     **/
    
    public function getClassLive(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $liveclassid=checkNull($this->liveclassid);
        $p=checkNull($this->p);
        
        if(!$liveclassid){
            return $rs;
        }
        $key2="getClassLive_".$this->p;
        $list=getcaches($key2);
        if(!$list){
            /* 推单列表 */
            $domain=new Domain_Home();
            $res=$domain->getClassLive($liveclassid,$p);
            setCaches($key2,$list,60);
        }

        $rs['info']=$res;
        return $rs;
    }

    /**
     * 获取过滤词汇
     * @desc 用于获取聊天过滤词
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0] 配置信息

     * @return string msg 提示信息
     */
    public function getFilterField() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $sensitive_words=getcaches('sensitive_words');

        if($sensitive_words){

            $rs['info']=$sensitive_words;

        }else{

            $rs['info'] = get_sensitives();

            setcaches("sensitive_words",$rs['info']);
        }

        return $rs;
    }
    /**
     * 订阅和取消订阅主播(新接口)
     * @desc 用于当前登录用户去订阅或取消订阅的主播
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function SubscribeAnchor() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $touid=checkNull($this->touid);
        $type=checkNull($this->type);

        $checkToken=checkToken($uid,$token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }

        if($touid==$uid){
            $rs['code'] = 1005;
            $rs['msg'] = '不能订阅自己';
            return $rs;
        }

        $domain = new Domain_Home();
        if($type==1){
            $info = $domain->subscribeAnchor($uid,$touid,1);
            $rs['msg'] = '订阅成功';
        }else{
            $info = $domain->subscribeAnchor($uid,$touid,2);
            $rs['msg'] = '取消订阅成功';
        }

        if($info==1003){
            $rs['code'] = 1003;
            $rs['msg'] = '订阅用户不存在';
            return $rs;
        }else if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = '已订阅';
            return $rs;
        }else if($info==0){
            $skey = 'getSubscribe_'.$uid;
            $subkey = 'getSubscribes_'.$uid.'_1';
            delcache($skey);
            delcache($subkey);
        }

//        $rs['info']=$info;
        return $rs;
    }
	
    /**
     * 获取推荐主播(新接口)
     * @desc 用于获取推荐主播
     * @return int code 操作码，0表示成功
     * @return array info 会员列表
     * @return string info[].id 用户ID
     * @return string info[].user_nicename 用户昵称
     * @return string info[].avatar 头像
     * @return string info[].fans 粉丝数
     * @return string info[].isattention 是否关注，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function getRecommendAnchor() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $checkToken = checkToken($uid,$token);

        $touids = [];
        if($checkToken==0){
            $domain = new Domain_Home();
            $key = 'getSubscribe_'.$uid;
            //$touids = getcaches($key);
            if(!$touids){
                $touids = $domain->getSubscribe($uid);
                setcaches($key,$touids,60);
            }
        }

        if ($uid=='' || !$uid) {
            /**
             * 自定义随机码，用于不带ID的客户可绑定缓存
             */
            $guests = array(
                "TegS", "yfdV", "TP93", "2da8", "eMun",
                "MkSj","B8g4","8Hwl","jOxO","zdCf","H2Y1",
                "6lBE","Ipq1","SpOh","qd84","hVy2","ah70",
                "1szl","4Yzi","viRE",
            );
            $uid = $guests[rand(0, count($guests))];
        }
        $gkey = 'getRecommendAnchor_'.$uid;
        //$recommends = getcaches($gkey);
        $domain = new Domain_Home();
        if(!$recommends){
            $recommends = $domain->getRecommendAnchor();
            setcaches($gkey,$recommends,1);
        }
        $newRecommends = [];
        if(!empty($recommends)){
            if(count($recommends)>=4){
                $rands = array_rand($recommends,4);
                foreach ($rands as $key => $value) {
                    $newRecommends[] = $recommends[$value];
                }
            }else{
                $newRecommends = $recommends;
            }

            foreach ($newRecommends as $k=>&$v){
                $v['isSubscribe'] = 0;
                if(!empty($touids) && in_array($v['id'],$touids)){
                    $v['isSubscribe'] = 1;
                }
                if($v['viewnum']<100){
                    $v['viewnum'] = 2000 + $v['viewnum'];
                }elseif($v['viewnum']>=100 && $v['viewnum']<1000){
                    $v['viewnum'] = 100000+$v['viewnum'];
                }else{
                    $v['viewnum'] = 1000000+$v['viewnum'];
                }
            }
        }

        $rs['info'] = $newRecommends;
        return $rs;
    }

    /**
     * 通过关键词搜索获取主播列表
     * @desc 用于通过关键词搜索获取主播列表
     * @return int code 操作码，0表示成功
     * @return array info 会员列表
     * @return string info[].id 用户ID
     * @return string info[].user_nicename 用户昵称
     * @return string info[].avatar 头像
     * @return string info[].fans 粉丝数
     * @return string info[].isattention 是否关注，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function getAnchorByKeyword() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
		$keyword=checkNull($this->keyword);
		
        $checkToken = checkToken($uid,$token);
        $touids = [];
        if($checkToken==0){
            $domain = new Domain_Home();
            $key = 'getSubscribe_'.$uid;
            //$touids = getcaches($key);
            if(!$touids){
                $touids = $domain->getSubscribe($uid);
                setcaches($key,$touids,1);
            }
        }

        $gkey = 'getAnchorByKeyword_'.$keyword;
        //$recommends = getcaches($gkey);
        $domain = new Domain_Home();
        if(!$recommends){
            $recommends = $domain->getAnchorByKeyword($keyword);
            setcaches($gkey,$recommends,1);
        }

		foreach ($recommends as $k=>&$v){
			$v['isSubscribe'] = 0;
			if(!empty($touids) && in_array($v['id'],$touids)){
				$v['isSubscribe'] = 1;
			}
			if($v['viewnum']<100){
				$v['viewnum'] = 2000 + $v['viewnum'];
			}elseif($v['viewnum']>=100 && $v['viewnum']<1000){
				$v['viewnum'] = 100000+$v['viewnum'];
			}else{
				$v['viewnum'] = 1000000+$v['viewnum'];
			}
		}

        $rs['info'] = $recommends;
        return $rs;
    }
	
    /**
     * 获取推荐直播，可获取随机推荐
     * @desc 用于获取推荐直播
     * @return int code 操作码，0表示成功
     * @return array info 随机直播列表
     * @return string info[0][].uid 主播id
     * @return string info[0][].title 直播标题
     * @return string info[0][].city 主播位置
     * @return string info[0][].stream 流名
     * @return string info[0][].pull 播流地址
     * @return string info[0][].thumb 直播封面
     * @return string info[0][].isvideo 是否假视频
     * @return string info[0][].type 直播类型
     * @return string info[0][].type_val 类型值
     * @return string info[0][].goodnum 靓号
     * @return string info[0][].anyway 横竖屏，0表示竖屏，1表示横屏
     * @return string info[0][].starttime 开播时间
     * @return string info[0][].isshop 是否开启店铺
     * @return string info[0][].game_action 游戏类型
     * @return string info[0][].notice 直播公告
     * @return string info[0][].hotvotes hotvotes
     * @return string info[0][].nums 人数
     * @return string info[0][].avatar 主播头像
     * @return string info[0][].avatar_thumb 头像缩略图
     * @return string info[0][].user_nicename 直播昵称
     * @return string info[0][].sex 主播性别
     * @return string info[0][].level 等级
     * @return string info[0][].level_anchor 主播等级
     * @return string info[0][].game game
     * @return string info[0][].issubscribed 是否已订阅
     * @return string msg 提示信息
     * @return array
     */
    public function getRecommendedStream()
    {
        $uid=checkNull($this->uid);
        $liveuid=checkNull($this->liveuid);
        $token=checkNull($this->token);
        $checkToken = checkToken($uid,$token);
        $isLoggedIn = $checkToken === 0;
        $result = (new Domain_Home())->getRecommendedStream($uid,$liveuid, $isLoggedIn, $this->p, $this->limit, $this->field, $this->order, $this->ismatch);

        return [
            'code' => !$result ? 1001 : 0,
            'msg' => '',
            'info' => $result ?? [],
        ];
    }
}
