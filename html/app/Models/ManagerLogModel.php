<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class managerLogModel extends Model
{
    use HasFactory;
    protected $table = 'manager_log';
    protected $fillable = ['account','action','dataNum'];
}
