<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tags";
    protected $guarded = [];
    
    public function posts()
    {
        return $this->hasMany('App\Post','tag_id','id');
    }
}
