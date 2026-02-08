<?php

class Model_Watermark extends PhalApi_Model_NotORM {
	/* 水印 */
	public function getWatermark($device) {
	    $where['device'] = $device;
        $list=DI()->notorm->watermark
            ->select('position,xpos,ypos,width,height,image')
            ->where($where)
            ->fetchAll();
        foreach($list as $k=>$v){
            $v['image']=get_upload_path($v['image']);
            $list[$k]=$v;
        }
		return $list;
	}			

}
