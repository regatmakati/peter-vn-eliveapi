<?php

class Model_Contract extends PhalApi_Model_NotORM
{
    public function createOrUpdate($uid, $contract)
    {
        if (empty($contract['contract'])) {
            return DI()->notorm->live_contract
            ->where('uid=?', $uid)
            ->update([
                'contract' => '',
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        }
        $recvContract = $contract['contract'] ?? '';
        $recvStatus = $contract['status'] ?? 0;
        $contractRes = DI()->notorm->live_contract
            ->where('uid=? AND type=1', $uid)
            ->fetchOne();
        if (empty($contractRes)) {
            DI()->notorm->live_contract->insert([
                'uid' => $uid,
                'type' => 1,
                'contract' => $recvContract,
                'status' => $recvStatus,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        } else {
            $sql = "UPDATE cmf_live_contract SET contract=:contract,status=:status,updated_at=:updated_at WHERE uid=:uid AND type=:type LIMIT 1";
            $this->getORM()->queryAll($sql, [
                ':uid' => $uid,
                ':type' => 1,

                ':contract' => $recvContract,
                ':status' => $recvStatus,
                ':updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        }
    }
}