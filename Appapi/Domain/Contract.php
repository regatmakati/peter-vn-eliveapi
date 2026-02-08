<?php

class Domain_Contract {
	public function createOrUpdate($uid,$contract) {
		$model = new Model_Contract();
		return $model->createOrUpdate($uid,$contract);
	}
}