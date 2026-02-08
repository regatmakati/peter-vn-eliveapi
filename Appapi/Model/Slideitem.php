<?php

class Model_Slideitem extends PhalApi_Model_NotORM {
    CONST STATUS_ON = 1;
    CONST STATUS_OFF = 0;
    public static $statusMap = [
        self::STATUS_ON => '显示',
        self::STATUS_OFF => '隐藏',
    ];
    /**
     * 获取轮播
     * @param $slideIds
     * @return mixed
     */
    public function getSlideItems($slideIds){

        $slideItems = DI()->notorm->slide_item
            ->select("id,slide_id,title,image,url,hf_isshow,target,description,content,more")
            ->where([
                "status" => self::STATUS_ON,
                "slide_id" => $slideIds,
            ])
            ->order("list_order DESC")
            ->fetchAll();
        foreach ($slideItems as &$slideItem) {
            $slideItem['image'] = get_upload_path($slideItem['image']);
        }
        return $slideItems;
    }

    /**
     * 点击
     * @param $id
     * @return
     */
    public function click($id)
    {
        $sql = "UPDATE `cmf_slide_item` SET `click_cnt` = `click_cnt`+1 WHERE `id` = :id";
        return $this->getORM()->queryAll($sql, [':id' => $id]);
    }


}
