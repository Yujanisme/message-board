<?php

namespace App\Repositories;

use App\Models\ManagerModel;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class ManagerRepository extends BaseRepository
{
    /**
     * @param ManagerModel $model
     */
    protected $model;

    /**
     * @param ManagerModel $model
     */
    protected $repository;
   // Constructor
   public function __construct(ManagerModel $model)
   {
        parent::__construct($model); 
   }

    /**
     * 新增管理員
     */
    public function createManager(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }
    
    /**
     * 更新管理員密碼
     */
    public function updateManager($id, array $data)
    {
        return $this->updateById($id, ['password' => Hash::make($data['new_password'])]);
    }

    /**
     * 刪除管理員
     * @param int $id
     */
    public function deleteManager(int $id)
    {
        $manager = $this->getById($id);
        if ($manager) {
            return $this->deleteById($id);
        }
        return false;
    }
} 

