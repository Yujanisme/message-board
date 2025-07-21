<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class managerLogModel extends Model
{
    use HasFactory;
    protected $table = 'manager_log';
    protected $primaryKey = 'id';
    protected $fillable = ['account','action_type','action'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; 
}
