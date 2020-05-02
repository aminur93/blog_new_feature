<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";
    protected $guarded = [];
    
    public function category()
    {
        return $this->belongsTo('App\Category','category_id','id');
    }
    
    public function tag()
    {
        return $this->belongsTo('App\Tag','tag_id','id');
    }
}
