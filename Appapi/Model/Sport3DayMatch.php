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


        $row = $this->_model()->sports_3day_match()
            ->select("match_id,sport_id,user_ids,comp,home,away")
            ->where("FIND_IN_SET($uid, user_ids)")
            ->fetch();

        if ($row) {
            $rs['match_id'] = $row['match_id'];
            $rs['liveclassid'] = $row['sport_id'] == 2 ? 2 : ($row['sport_id'] == 1 ? 4 : 0);
            $rs['title'] = $row['home']." VS ".$row['away'];
        }
        return $rs;

    }

}
