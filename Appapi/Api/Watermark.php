<?php
/**
 * 水印页
 */
class Api_Watermark extends PhalApi_Api {

	public function getRules() {
		return array(
            'getWatermark' => array(
                'device' => array('name' => 'device', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '设备1:PC 2:APP/H5'),
            ),
		);
	}
	

	/**
	 * 引导页
	 * @desc 用于 获取引导页信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].position 位置，1:上左 2:上右 3:下左 4:下右
	 * @return string info[0].xpos 图片左侧在背景左侧方向百分比
	 * @return string info[0].ypos 图片上方在背景上方方向百分比
     * @return string info[0].width 宽度占直播宽度百分比
	 * @return string info[0].height 高度占直播宽度百分比
	 * @return string info[0].image 图片
	 * @return string msg 提示信息
	 */
	public function getWatermark() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        $device=$this->device;

        $key2="getWatermark";
        $list=getcaches($key2);
        if(!$list){
            /* 推单列表 */
            $domain = new Domain_Watermark();
            $info = $domain->getWatermark($device);
            setCaches($key2,$list,600);
        }

		
		$rs['info']=$info;
		return $rs;			
	}		
	

}
