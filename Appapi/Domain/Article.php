<?php

class Domain_Article {

    public function getRelatedArticles($artid,$num){
        $rs = array();

        $model = new Model_Article();
        $rs = $model->getRelatedArticles($artid,$num);

        return $rs;
    }
	
	public function getComments($uid,$artid,$p,$num) {
        $rs = array();

        $model = new Model_Article();
        $rs = $model->getComments($uid,$artid,$p,$num);

        return $rs;
    }

	public function getReplys($uid,$commentid,$p,$num) {
        $rs = array();

        $model = new Model_Article();
        $rs = $model->getReplys($uid,$commentid,$p,$num);

        return $rs;
    }
	
    public function addCommentLike($uid,$commentid) {
        $rs = array();

        $model = new Model_Article();
        $rs = $model->addCommentLike($uid,$commentid);

        return $rs;
    }
	
    public function setComment($data) {
        $rs = array();

        $model = new Model_Article();
        $rs = $model->setComment($data);

        return $rs;
    }
	
    public function collectArticle($uid,$artid){
        $rs = array();

        $model = new Model_Article();
        $rs = $model->collectArticle($uid,$artid);

        return $rs;
    }
	
    public function addSubscribe($uid,$author) {
        $rs = array();

        $model = new Model_Article();
        $rs = $model->addSubscribe($uid,$author);

        return $rs;
    }

    public function addLike($uid,$artid) {
        $rs = array();

        $model = new Model_Article();
        $rs = $model->addLike($uid,$artid);

        return $rs;
    }

	
    public function getArticleCat() {
        $rs = array();

        $model = new Model_Article();
        $rs = $model->getArticleCat();

        return $rs;
    }

    public function getArticleList($ishot,$isrecommend,$ptype,$author,$type,$p,$nums,$uid){
        $rs = array();

        $model = new Model_Article();
        $rs = $model->getArticleList($ishot,$isrecommend,$ptype,$author,$type,$p,$nums,$uid);

        return $rs;
    }

    public function getArticleDetail($id,$uid){
        $rs = array();

        $model = new Model_Article();
        $rs = $model->getArticleDetail($id,$uid);

        return $rs;
    }

}
