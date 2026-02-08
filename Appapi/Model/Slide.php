<?php

class Model_Slide extends PhalApi_Model_NotORM {
    CONST STATUS_ON = 1;
    CONST STATUS_OFF = 0;
    public static $statusMap = [
        self::STATUS_ON => '显示',
        self::STATUS_OFF => '隐藏',
    ];

    CONST POSITION_PC_INDEX_BIG = 1;
    CONST POSITION_PC_INDEX_LEFT= 2;
    CONST POSITION_PC_INDEX_RIGHT = 3;
    CONST POSITION_PC_INDEX_TOP = 4;
    CONST POSITION_APP_BANNER = 5;
    public static $positionMap = [
        self::POSITION_PC_INDEX_BIG => 'PC首页大背景图',
        self::POSITION_PC_INDEX_LEFT => 'PC首页左小轮图',
        self::POSITION_PC_INDEX_RIGHT => 'PC首页右小轮图',
        self::POSITION_PC_INDEX_TOP => 'PC顶部广告图',
        self::POSITION_APP_BANNER => 'APP首页轮播',
    ];

    /* PC幻灯片 */
    public function getSlides(){
        $rs = DI()->notorm->slide
            ->select("id, position")
            ->where(
                [
                'status' => self::STATUS_ON
                ]
            )
            ->fetchAll();
        return $rs;
    }

}
