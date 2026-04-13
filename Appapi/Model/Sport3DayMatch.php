<?php

class Model_Sport3DayMatch extends PhalApi_Model_NotORM
{
    private function _model()
    {
        $pdo = new PDO("mysql:host=129.226.143.41;dbname=center_sports;charset=utf8mb4", "sports", "aO!FeJR8lAaH7*yW");
        return new NotORM($pdo);
    }

    public function getMatchIdByUid($uid)
    {
        $rs = [
            'match_id' => 0,
            'liveclassid' => 0,
            'title' => '',
        ];


        $anchor = $this->_model()->sports_3day_match_anchor_vn()->where("FIND_IN_SET($uid, user_ids)")->fetch();
        if($anchor){
            $row = $this->_model()->sports_3day_match()
                    ->select("match_id,sport_id,user_ids,comp,home,away")
                    ->where("match_id = {$anchor['match_id']} and sport_id = {$anchor['sport_id']}")
                    ->fetch();

            if ($row) {
                $rs['match_id'] = $row['match_id'];
                $rs['liveclassid'] = $row['sport_id'] == 2 ? 2 : ($row['sport_id'] == 1 ? 4 : 0);
                $rs['title'] = $row['home']." VS ".$row['away'];
            }
        }
        return $rs;

    }


    public function getMatchInfo($match_id, $type)
    {

        $info = $this->_model()->sports_3day_match()
            ->select("*")
            ->where("match_id = {$match_id} and sport_id = {$type}")
            ->fetch();

        $anchor = $this->_model()->sports_3day_match_anchor_vn()->where("match_id = {$match_id} and sport_id = {$type}")->fetch();

        $info['is_hot'] = $anchor['is_hot'];
        $info['user_ids'] = $anchor['user_ids'];

        return $info;

    }

}
