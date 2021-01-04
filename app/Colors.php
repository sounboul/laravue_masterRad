<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colors extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function article()
    {
    	return $this->hasMany(Articles::class);
    }
}
