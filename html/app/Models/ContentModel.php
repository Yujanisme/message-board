<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ContentModel extends Model
{
    use HasFactory;
    
    public function serializeDate(DateTimeInterface $date){
        {
            return $date->format('Y-m-d');
        }
    }
    protected $table = 'content';
    protected $fillable = ['user_nickname','content','created_at'];
    protected $dataFormat = 'Y-m-d';
    const UPDATED_AT =null;
}
