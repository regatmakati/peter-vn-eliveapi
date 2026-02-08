<?php

class Domain_Watermark {
	public function getWatermark($device) {
		$rs = array();

		$model = new Model_Watermark();
		$rs = $model->getWatermark($device);

		return $rs;
	}
	
}
