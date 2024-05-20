<?php

namespace App\Model;

use Phalcon\Mvc\Model;

class CentralCallback extends Model
{
    private $db;

    public function initialize()
    {
        $this->db = $this->getDI()->get("db");
        $this->setSource("CPCCORE_CENTRAL_CALLBACK");
    }

    // set-your-code

    public function columnMap()
    {
        return [
            'CPC_CC_PPID' => 'ppid',
            'CPC_CC_URL' => 'url',
            'CPC_CC_UID' => 'uid',
            'CPC_CC_CREATED' => 'created',
            'CPC_CC_CREATED_BY' => 'created_by',
            'CPC_CC_UPDATED' => 'updated',
            'CPC_CC_UPDATED_BY' => 'updated_by',
            'CPC_CC_ACTIVE' => 'active',
        ];
    }
}
