<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class model_groupaccess extends Model
{
    use HasFactory;

    protected $table = 'group_access';
    protected $primaryKey = 'id_groupaccess';
    protected $guraded = [];

    public function role_access(){
        return $this->hasMany('App\Models\model_roleaccess', 'idgroupaccess');
    }

    public function users(){
        return $this->hasMany('App\Models\User', 'role_access_group');
    }
}
