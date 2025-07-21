<?php

namespace App\Repositories;

use App\Models\ManagerLogModel;
use App\Repositories\BaseRepository;

class ManagerLogRepository extends BaseRepository
{
    /** 
     * @parm ManagerLog model
     */
    protected $model;
    public function __construct(ManagerLogModel $model)
    {
        parent::__construct($model);
    }
    
    public function addLog(string $account,string $action_type,string $action){
        $this->create([
            'account'=>$account,
            'action_type'=>$action_type,
            'action'=>$action
        ]);
    }
}
 
