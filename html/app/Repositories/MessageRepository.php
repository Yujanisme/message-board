<?php
namespace App\Repositories;

use App\Models\MessageModel;

class MessageRepository extends BaseRepository
{
    /**
     * @param MessageModel $model
     */
    protected $model;

    /**
     * @param MessageModel $model
     */
    public function __construct(MessageModel $model)
    {
        parent::__construct($model);
    }
    
}