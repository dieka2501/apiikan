<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Instansi;

class InstansiController extends Controller
{
    protected $model;

    public function __construct(Instansi $model)
    {
    	$this->model = $model;
    }

    public function get_all()
    {
    	$get_data = $this->model->get_all();
		if(count($get_data)>0){
			$status = true;
			$data 	= $get_data;
			$alert 	= "Data diketemukan";
		}else{
			$status = false;
			$data 	= array();
			$alert 	= "Data tidak diketemukan";
		}
		$json = array('status'=>$status,'data'=>$data,'alert'=>$alert);
		return response()->json($json);
    }
}
