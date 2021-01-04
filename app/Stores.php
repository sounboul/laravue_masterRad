<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function article()
    {
    	return $this->hasMany(Articles::class);
    }
}
