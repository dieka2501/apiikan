<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $table = "instansi";

    function get_all()
    {
    	return Instansi::all();
    }

}
