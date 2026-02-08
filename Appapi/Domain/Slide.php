<?php

class Domain_Slide {
	public function getSlide(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$model = new Model_Slide();
        $slides = $model->getSlides();
        $slideIds = array_get_column($slides, 'id');
        $model = new Model_Slideitem();
        $slideItems = $model->getSlideItems($slideIds);
        array_index($slideItems, "slide_id");
        foreach ($slides as &$slide) {
            if (!empty($slideItems[$slide['id']])) {
                $slide['items'] = $slideItems[$slide['id']];
            } else {
                $slide['items'] = [];
            }
            unset($slide['id']);
        }
        $rs['info'] = $slides;
        return $rs;
	}

	public function clickSlideItem($id)
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Slideitem();
        $model->click($id);
        return $rs;
    }


	
}
