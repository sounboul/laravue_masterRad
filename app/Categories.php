<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function article()
    {
    	return $this->hasOne(Articles::class);
    }
}
