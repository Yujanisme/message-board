<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MessageModel extends Model
{
    use HasFactory;
    
    public function serializeDate(DateTimeInterface $date){
        {
            return $date->format('Y-m-d');
        }
    }
    protected $table = 'content';
    protected $primaryKey = 'id';
    protected $fillable = ['user_nickname','content','create','created_at'];
    protected $dataFormat = 'Y-m-d';
    const UPDATED_AT =null;
}
