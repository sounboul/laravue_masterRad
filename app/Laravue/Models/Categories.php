<?php

namespace App\Laravue\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $guarded = [];
    protected $table = "categories";
    public $timestamps = false;

    /*public function article()
    {
    	return $this->belongsToMany(Articles::class);
    }*/
}
