<?php

class Domain_Home {

    public function getSlide() {
        $rs = array();
        $model = new Model_Home();
        $rs = $model->getSlide();
        return $rs;
    }

    public function getAdv() {
        $rs = array();
        $model = new Model_Home();
        $rs = $model->getAdv();
        return $rs;
    }
	public function getHot($userID, bool $isLoggedIn, $page, $limit)
    {
        return (new Model_Home())->getHot($userID, $isLoggedIn, $page, $limit);
    }

	public function getLiveListBySearch($userID, bool $isLoggedIn, $keyword)
    {
        return (new Model_Home())->getLiveListBySearch($userID, $isLoggedIn,$keyword);
    }
	
	public function getSlideEvents() {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getSlideEvents();
				
        return $rs;
    }

    public function getDownload(){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getDownload();
        return $rs;
    }
	
	public function getFollow($uid,$p) {
        $rs = array();
				
        $model = new Model_Home();
        $rs = $model->getFollow($uid,$p);
				
        return $rs;
    }
		
	public function getNew($lng,$lat,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getNew($lng,$lat,$p);
				
        return $rs;
    }
		
	public function search($uid,$key,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->search($uid,$key,$p);
				
        return $rs;
    }
	
	public function getNearby($lng,$lat,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getNearby($lng,$lat,$p);
				
        return $rs;
    }
	
	public function getRecommend() {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getRecommend();
				
        return $rs;
    }
	
	public function attentRecommend($uid,$touid) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->attentRecommend($uid,$touid);
				
        return $rs;
    }

    public function profitList($uid,$type,$p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->profitList($uid,$type,$p);
                
        return $rs;
    }

    public function consumeList($uid,$type,$p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->consumeList($uid,$type,$p);
                
        return $rs;
    }

    public function getClassLive($liveclassid,$p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getClassLive($liveclassid,$p);
                
        return $rs;
    }

    public function getRecommendLive($limit,$p)
    {
        return (new Model_Home())->getRecommendLive($limit, $p);
    }
	
    public function subscribeAnchor($uid,$touid,$type){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->subscribeAnchor($uid,$touid,$type);

        return $rs;
    }

    public function getSubscribe($uid){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getSubscribe($uid);

        return $rs;
    }

    public function getAnchorByKeyword($keyword){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getAnchorByKeyword($keyword);

        return $rs;
    }
	
    public function getRecommendAnchor($limit=40){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getRecommendAnchor($limit);

        return $rs;
    }

    public function getRecommendAnchorOrder($limit){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getRecommendAnchorOrder($limit);

        return $rs;
    }

    public function getPCAnchorOrder($num) {
        $model = new Model_Home();
        $rs = $model->getPCAnchorOrder($num);
        return $rs;
    }

    public function getPCBulletin($num) {
        $model = new Model_Home();
        $rs = $model->getPCBulletin($num);
        return $rs;
    }
    public function getPCNav($num) {
        $model = new Model_Home();
        $rs = $model->getPCNav($num);
        return $rs;
    }	

    public function getPCBenner($num) {
        $model = new Model_Home();
        $rs = $model->getPCBenner($num);
        return $rs;
    }

    public function getPCHot($num) {
        $model = new Model_Home();
        $rs = $model->getPCHot($num);
        return $rs;
    }

    public function getRecommendedStream($userID,$liveuid, bool $isLoggedIn, $page = 1, $limit = 15, $field = 'recom_sort', $order = 'asc', bool $isMatch = false)
    {
        return (new Model_Home())->getRecommendedStream($userID,$liveuid, $isLoggedIn, $page, $limit, $field, $order, $isMatch);
    }

    public function getSlideBackground()
    {
        return (new Model_Home())->getSlideBackground();
    }


}
