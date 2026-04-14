<?php
/**
 * 幻灯片
 */
class Api_Slide extends PhalApi_Api
{
    public function getRules() {
        $rules = array(
            'clickSlideItem' => array(
                'slide_items_id' => array('name' => 'slide_items_id', 'require' => true, 'type' => 'int', 'default'=>'' ,'desc' => '幻灯片id'),
            ),
        );

        return $rules;
    }

	
    /**
     * 获取幻灯片
     * @desc 用于获取首页轮播图及赛事列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0]['position']    幻灯片位置：1、PC首页大背景图，2、PC首页左小轮图，3PC首页右小轮图，4、PC顶部广告图，5APP首页轮播
     * @return string info[0]['items']  幻灯片列表
     * @return string info[0]['items'][].id 幻灯片id
     * @return string info[0]['items'][].slide_id 幻灯片位置id
     * @return string info[0]['items'][].title 幻灯片标题
     * @return string info[0]['items'][].image 幻灯片图片
     * @return string info[0]['items'][].url 幻灯片图片链接
     * @return string info[0]['items'][].target 友情链接打开方式
     * @return string info[0]['items'][].description 幻灯片描述
     * @return string info[0]['items'][].content 幻灯片内容
     * @return string info[0]['items'][].more 幻灯片扩展
     * @return string msg 提示信息
     */
    public function getSlide() {
        $domain = new Domain_Slide();
		$key1 = 'getSlideItems';
		$slide = getcaches($key1);
		if(empty($slide)){
			$slide = $domain->getSlide();
			setcaches($key1, $slide, 60);
		}
		return $slide;
    }

    /**
     * 幻灯片点击
     * @desc 用户点击幻灯片时记录点击数量
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string msg 提示信息
     */
    public function clickSlideItem() {
        $itemId = checkNull($this->slide_items_id);
        $domain = new Domain_Slide();
        $slide = $domain->clickSlideItem($itemId);
        return $slide;
    }
}
