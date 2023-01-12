<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class model_roleaccess extends Model
{
    use HasFactory;

    protected $table = 'role_access';
    protected $primaryKey = 'id_roleaccess';
    protected $guraded = [];

    public function group_access(){
        return $this->belongsTo('App\Models\model_groupaccess', 'idgroupaccess');
    }
}
