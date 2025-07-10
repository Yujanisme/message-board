<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ManagerModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'manager';
    protected $primaryKey = 'id';
    protected $fillable = ['manager_name','account','password'];
    public $timestamps = true;
}
