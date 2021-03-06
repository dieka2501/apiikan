<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Bcf;
use App\Model\Lokasi;

class BcfController extends Controller
{
	protected $bcf;
	protected $lokasi;
	protected $path;

    public function __construct(Bcf $bcf,Lokasi $lokasi){
		date_default_timezone_set('Asia/Jakarta');
		$this->bcf 			= $bcf;
		$this->lokasi 		= $lokasi;
		$this->path   		= base_path().'/assets/uploads/';
	}

	public function post_survey(Request $request){	
		if($request->has('surveyor_id') && $request->has('id_prov') && $request->has('id_kabkota') && $request->has('id_kecamatan')){

			$surveyor_id 			= $request->get('surveyor_id');
			$id_prov 				= $request->get('id_prov');
			$id_kabkota 			= $request->get('id_kabkota');
			$id_kecamatan 			= $request->get('id_kecamatan');
			$id_kelurahan 			= $request->get('id_kelurahan');
			$weather  				= $request->get('cuaca');
			$coordinate				= $request->get('koordinat');
			$qty					= $request->get('qty');
			$primer 				= $request->get('primer');	
			$note_sekunder 			= $request->get('note_sekunder');
			$notes 					= $request->get('notes');
			$island 				= $request->get('island');
			$nama_desa 				= $request->get('village_name');
			$date_survey 			= $request->get('date_survey');
			if($primer == 0){
				$status = 0;
			}else{
				$status = 1;
			}
			if($request->hasFile('image')){
				$image				= $request->file('image');
				$extimage 			= $image->getClientOriginalExtension();
				$filename 			= date('YmdHis').'.'.$extimage;
				$image->move($this->path,$filename);
				$insert['image']	= $filename;
			}
			if($request->hasFile('image2')){
				$image2				= $request->file('image2');
				$extimage2 			= $image2->getClientOriginalExtension();
				$filename2 			= 'image2'.date('YmdHis').'.'.$extimage2;
				$image2->move($this->path,$filename2);
				$insert['image2']	= $filename2;
			}
			if($request->hasFile('image3')){
				$image3				= $request->file('image3');
				$extimage3 			= $image3->getClientOriginalExtension();
				$filename3 			= 'image3'.date('YmdHis').'.'.$extimage3;
				$image3->move($this->path,$filename3);
				$insert['image3']	= $filename3;
			}
			$insert['primer'] 				= $primer;
			$insert['note_secunder']		= $note_sekunder;
			$insert['island']				= $island;
			$insert['village_name']			= $nama_desa;
			$insert['date_survey']			= $date_survey;
			$insert['status']				= $status;
			$insert['notes'] 				= $notes;
			$insert['surveyor_id'] 			= $surveyor_id;
			$insert['province_id'] 			= $id_prov;
			$insert['city_id'] 				= $id_kabkota;
			$insert['district_id'] 			= $id_kecamatan;
			$insert['village_id'] 			= $id_kelurahan;
			$insert['weather'] 				= $weather;
			$insert['qty']	 				= $qty;
			$insert['coordinate'] 			= $coordinate;
			$insert['created_at'] 			= date('Y-m-d H:i:s');
			$insert['updated_at'] 			= date('Y-m-d H:i:s');
			$ids 							= $this->bcf->add($insert);
			if($ids > 0){
				$get_data 				= $this->bcf->get_id($ids);
				$get_prov 				= $this->lokasi->get_prov_idprov($get_data->province_id);
				$get_city 				= $this->lokasi->get_city_idcity($get_data->province_id,$get_data->city_id);
				$get_district 			= $this->lokasi->get_kec_idkec($get_data->province_id,$get_data->city_id,$get_data->district_id);
				// $get_village 			= $this->lokasi->get_kel_idkel($get_data->province_id,$get_data->city_id,$get_data->district_id,$get_data->village_id);
				$json 					= ['id_bcf' => $ids,'prov_id'=>$get_data->province_id,'prov_name'=>$get_prov->lokasi_nama,'city_id'=>$get_data->city_id,'city_name'=>$get_city->lokasi_nama,
										   'district_id'=>$get_data->district_id,'district_name'=>$get_district->lokasi_nama,'village_name'=>$get_data->village_name,
										   'weather'=>$get_data->weather,'coordinate'=>$get_data->coordinate,
										   'qty'=>$get_data->qty,
										   'image'=>config('app.url').'/assets/uploads/'.$get_data->image,
										   'created_at'=>$get_data->created_at];
				$status = true;
				$data 	= $json;
				$alert 	= "Insert data berhasil";	
			}else{
				$status = false;
				$data 	= null;
				$alert 	= "Insert data gagal";	
			}
		}else{
			$status = false;
			$data 	= null;
			$alert 	= "Paramater surveyor_id, id_prov, id_kabkota, id_kecamatan, dan id_kelurahan tidak ada";
		}
		$json = array('status'=>$status,'data'=>$data,'alert'=>$alert);
		return response()->json($json);
	}
	public function get_list(Request $request){
		$getdata = $this->bcf->get_page();
		// var_dump($getdata);
		if(count($getdata) > 0){
			foreach ($getdata as $get_datas) {
				$get_prov 				= $this->lokasi->get_prov_idprov($get_datas->province_id);
				$get_city 				= $this->lokasi->get_city_idcity($get_datas->province_id,$get_datas->city_id);
				$get_district 			= $this->lokasi->get_kec_idkec($get_datas->province_id,$get_datas->city_id,$get_datas->district_id);
				// $get_village 			= $this->lokasi->get_kel_idkel($get_datas->province_id,$get_datas->city_id,$get_datas->district_id,$get_datas->village_id);
				$arr_data[]				= ['id_bcfLaut' => $get_datas->id,
											'id_surveyor'=>$get_datas->surveyor_id,
											'surveyor_name'=>$get_datas->surveyor_name,
											'prov_id'=>$get_datas->province_id,'prov_name'=>$get_prov->lokasi_nama,'city_id'=>$get_datas->city_id,'city_name'=>$get_city->lokasi_nama,
										   'district_id'=>$get_datas->district_id,'district_name'=>$get_district->lokasi_nama,'village_name'=>$get_datas->village_name,
										   'weather'=>$get_datas->weather,'coordinate'=>$get_datas->coordinate,
										   'qty'=>$get_datas->qty,
										   'status'=>$get_datas->status,
										   'primer'=>$get_datas->primer,
										   'note_secunder'=>$get_datas->note_secunder,
										   'notes'=>$get_datas->notes,
										   'island'=>$get_datas->island,
										   'date_survey'=>$get_datas->date_survey,
										   'image'=>config('app.url').'/assets/uploads/'.$get_datas->image,
										   'image2'=>config('app.url').'/assets/uploads/'.$get_datas->image2,
										   'image3'=>config('app.url').'/assets/uploads/'.$get_datas->image3,
										   'created_at'=>$get_datas->created_at];
			}
			$status = true;
			$data 	= $arr_data;
			$alert 	= "Data BCF Ada.";
		}else{
			$status = false;
			$data 	= array();
			$alert 	= "Data BCF Belum Ada.";
		}
		$json = array('status'=>$status,'data'=>$data,'alert'=>$alert);
		return response()->json($json);
	}
	
	public function get_id(Request $request){
		if($request->has('id') && $request->has('id_surveyor')){
			$id 			= $request->get('id');
			$id_surveyor 	= $request->get('id_surveyor');
			$get_bcf 		= $this->bcf->get_id($id);
			if(count($get_bcf) > 0){
				if($get_bcf->surveyor_id == $id_surveyor){
					$status = true;
					$data 	= $get_bcf;
					$alert 	= "Data diketemukan.";			
				}else{
					$status = false;
					$data 	= null;
					$alert 	= "Surveyor tidak diizinkan untuk mengedit data ini.";			
				}
			}else{
				$status = false;
				$data 	= null;
				$alert 	= "Data survey tidak diketemukan.";		
			}
		}else{
			$status = false;
			$data 	= null;
			$alert 	= "Paramater id dan id_surveyor tidak diketemukan.";	
		}
		$json = array('status'=>$status,'data'=>$data,'alert'=>$alert);
		return response()->json($json);
	}

	public function edit(Request $request){
		if($request->has('id') && $request->has('id_prov') && $request->has('id_kabkota') && $request->has('id_kecamatan')){
			$id 					= $request->get('id');
			$id_prov 				= $request->get('id_prov');
			$id_kabkota 			= $request->get('id_kabkota');
			$id_kecamatan 			= $request->get('id_kecamatan');
			$weather  				= $request->get('cuaca');
			$coordinate				= $request->get('koordinat');
			$qty					= $request->get('qty');
			$primer 				= $request->get('primer');	
			$note_sekunder 			= $request->get('note_sekunder');
			$notes 					= $request->get('notes');
			$island 				= $request->get('island');
			$nama_desa 				= $request->get('village_name');
			$date_survey 			= $request->get('date_survey');
			if($primer == 0){
				$status = 0;
			}else{
				$status = 1;
			}
			if($request->hasFile('image')){
				$image				= $request->file('image');
				$extimage 			= $image->getClientOriginalExtension();
				$filename 			= date('YmdHis').'.'.$extimage;
				$image->move($this->path,$filename);
				$insert['image']	= $filename;
			}
			if($request->hasFile('image2')){
				$image2				= $request->file('image2');
				$extimage2 			= $image2->getClientOriginalExtension();
				$filenameimage2		= 'image2'.date('YmdHis').'.'.$extimage2;
				$image2->move($this->path,$filenameimage2);
				$insert['image2']	= $filenameimage2;
			}
			if($request->hasFile('image3')){
				$image3				= $request->file('image3');
				$extimage3 			= $image3->getClientOriginalExtension();
				$filenameimage3		= 'image3'.date('YmdHis').'.'.$extimage3;
				$image3->move($this->path,$filenameimage3);
				$insert['image3']	= $filenameimage3;
			}
			$insert['primer'] 				= $primer;
			$insert['note_secunder']		= $note_sekunder;
			$insert['island']				= $island;
			$insert['village_name']			= $nama_desa;
			$insert['date_survey']			= $date_survey;
			$insert['status']				= $status;
			$insert['notes'] 				= $notes;
			$insert['province_id'] 			= $id_prov;
			$insert['city_id'] 				= $id_kabkota;
			$insert['district_id'] 			= $id_kecamatan;
			$insert['weather'] 				= $weather;
			$insert['qty']	 				= $qty;
			$insert['coordinate'] 			= $coordinate;
			$insert['updated_at'] 			= date('Y-m-d H:i:s');
			if($this->bcf->edit($id,$insert)){
				$get_data 				= $this->bcf->get_id($id);
				$get_prov 				= $this->lokasi->get_prov_idprov($get_data->province_id);
				$get_city 				= $this->lokasi->get_city_idcity($get_data->province_id,$get_data->city_id);
				$get_district 			= $this->lokasi->get_kec_idkec($get_data->province_id,$get_data->city_id,$get_data->district_id);
				// $get_village 			= $this->lokasi->get_kel_idkel($get_data->province_id,$get_data->city_id,$get_data->district_id,$get_data->village_id);
				$json 					= ['id_bcf' => $id,'prov_id'=>$get_data->province_id,'prov_name'=>$get_prov->lokasi_nama,'city_id'=>$get_data->city_id,'city_name'=>$get_city->lokasi_nama,
										   'district_id'=>$get_data->district_id,'district_name'=>$get_district->lokasi_nama,'village_name'=>$get_data->village_name,
										   'weather'=>$get_data->weather,'coordinate'=>$get_data->coordinate,
										   'qty'=>$get_data->qty,
										   'image'=>config('app.url').'/assets/uploads/'.$get_data->image,
										   'created_at'=>$get_data->created_at];
				$status = true;
				$data 	= $json;
				$alert 	= "Update data berhasil";	
			}else{
				$status = false;
				$data 	= null;
				$alert 	= "Paramater id.";			
			}
		}else{
			$status = false;
			$data 	= null;
			$alert 	= "Paramater id.";		
		}
		$json = array('status'=>$status,'data'=>$data,'alert'=>$alert);
		return response()->json($json);
	}
}
