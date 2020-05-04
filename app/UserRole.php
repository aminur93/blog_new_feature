<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = "user_role";
    
    public function users()
    {
        return $this->hasMany('App\User','user_role_id','id');
    }
}
