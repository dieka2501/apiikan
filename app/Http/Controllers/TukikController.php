<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Tukik;
use App\Model\Lokasi;

class TukikController extends Controller
{
    public function __construct(Tukik $tukik,Lokasi $lokasi){
		date_default_timezone_set('Asia/Jakarta');
		$this->tukik 	= $tukik;
		$this->lokasi 	= $lokasi;
		$this->path   	= base_path().'/assets/uploads/';
	}
	public function post_survey(Request $request)
	{	
		if($request->has('surveyor_id') && $request->has('id_prov') && $request->has('id_kabkota') && $request->has('id_kecamatan')){

			$surveyor_id 			= $request->get('surveyor_id');
			$id_prov 				= $request->get('id_prov');
			$id_kabkota 			= $request->get('id_kabkota');
			$id_kecamatan 			= $request->get('id_kecamatan');
			$id_kelurahan 			= $request->get('id_kelurahan');
			$weather  				= $request->get('cuaca');
			$coordinate				= $request->get('koordinat');
			$jenis					= $request->get('jenis');
			$qty_hidup				= $request->get('qty_hidup');
			$qty_mati				= $request->get('qty_mati');	
			$qty_cangkang_kosong	= $request->get('qty_cangkang_kosong');
			$qty_tidak_menetas		= $request->get('qty_tidak_menetas');
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
			if($request->hasFile('image_hidup')){
				$image_hidup		= $request->file('image_hidup');
				$filename_hidup 	= date('YmdHis').'.png';
				$image_hidup->move($this->path,$filename_hidup);
				$insert['image_hidup']	= $filename_hidup;
			}
			if($request->hasFile('image_mati')){
				$image_mati		= $request->file('image_mati');
				$filename_mati 	= date('YmdHis').'.png';
				$image_mati->move($this->path,$filename_mati);
				$insert['image_mati']	= $filename_mati;
			}
			if($request->hasFile('image_cangkang')){
				$image_cangkang		= $request->file('image_cangkang');
				$filename_cangkang 	= date('YmdHis').'.png';
				$image_cangkang->move($this->path,$filename_cangkang);
				$insert['image_cangkang']	= $filename_cangkang;
			}
			if($request->hasFile('image_sarang')){
				$image_sarang			= $request->file('image_sarang');
				$filename_sarang 		= date('YmdHis').'.png';
				$image_sarang->move($this->path,$filename_sarang);
				$insert['image_sarang']	= $filename_sarang;
			}
			if($request->hasFile('image_aktivitas')){
				$image_aktivitas			= $request->file('image_aktivitas');
				$filename_aktivitas			= date('YmdHis').'.png';
				$image_aktivitas->move($this->path,$filename_aktivitas);
				$insert['image_aktivitas']	= $filename_aktivitas;
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
			$insert['qty_hidup'] 			= $qty_hidup;
			$insert['qty_mati'] 			= $qty_mati;
			$insert['qty_cangkang_kosong']	= $qty_cangkang_kosong;
			$insert['qty_tidak_menetas']	= $qty_tidak_menetas;
			$insert['coordinate'] 			= $coordinate;
			$insert['jenis'] 				= $jenis;
			$insert['created_at'] 			= date('Y-m-d H:i:s');
			$insert['updated_at'] 			= date('Y-m-d H:i:s');
			$ids 							= $this->tukik->add($insert);
			if($ids > 0){
				$get_data 				= $this->tukik->get_id($ids);
				$get_prov 				= $this->lokasi->get_prov_idprov($get_data->province_id);
				$get_city 				= $this->lokasi->get_city_idcity($get_data->province_id,$get_data->city_id);
				$get_district 			= $this->lokasi->get_kec_idkec($get_data->province_id,$get_data->city_id,$get_data->district_id);
				// $get_village 			= $this->lokasi->get_kel_idkel($get_data->province_id,$get_data->city_id,$get_data->district_id,$get_data->village_id);
				$json 					= ['id_tukik' => $ids,'prov_id'=>$get_data->province_id,'prov_name'=>$get_prov->lokasi_nama,'city_id'=>$get_data->city_id,'city_name'=>$get_city->lokasi_nama,
										   'district_id'=>$get_data->district_id,'district_name'=>$get_district->lokasi_nama,'village_name'=>$get_data->village_name,
										   'weather'=>$get_data->weather,'coordinate'=>$get_data->coordinate,
										   'qty_hidup'=>$get_data->qty_hidup,'qty_mati'=>$get_data->qty_mati,'qty_cangkang_kosong'=>$get_data->qty_cangkang_kosong,'qty_tidak_menetas'=>$get_data->qty_tidak_menetas,
										   'jenis'=>$get_data->jenis,
										   'image_hidup'=>config('app.url').'/assets/uploads/'.$get_data->image_hidup,
										   'image_mati'=>config('app.url').'/assets/uploads/'.$get_data->image_mati,
										   'image_cangkang'=>config('app.url').'/assets/uploads/'.$get_data->image_cangkang,
										   'image_sarang'=>config('app.url').'/assets/uploads/'.$get_data->image_sarang,
										   'image_aktivitas'=>config('app.url').'/assets/uploads/'.$get_data->image_aktivitas,
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
		$data = $this->tukik->get_page();
		// var_dump($getdata);
		if(count($data) > 0){
			foreach ($data as $get_data) {
				$get_prov 				= $this->lokasi->get_prov_idprov($get_data->province_id);
				$get_city 				= $this->lokasi->get_city_idcity($get_data->province_id,$get_data->city_id);
				$get_district 			= $this->lokasi->get_kec_idkec($get_data->province_id,$get_data->city_id,$get_data->district_id);
				// $get_village 			= $this->lokasi->get_kel_idkel($get_data->province_id,$get_data->city_id,$get_data->district_id,$get_data->village_id);
				$json[] 				= ['id_tukik' => $get_data->id,'id_surveyor'=>$get_data->surveyor_id,
											'surveyor_name'=>$get_data->surveyor_name,
											'prov_id'=>$get_data->province_id,'prov_name'=>$get_prov->lokasi_nama,'city_id'=>$get_data->city_id,'city_name'=>$get_city->lokasi_nama,
										   'district_id'=>$get_data->district_id,'district_name'=>$get_district->lokasi_nama,'village_name'=>$get_data->village_name,
										   'weather'=>$get_data->weather,'coordinate'=>$get_data->coordinate,
										   'qty_hidup'=>$get_data->qty_hidup,'qty_mati'=>$get_data->qty_mati,'qty_cangkang_kosong'=>$get_data->qty_cangkang_kosong,'qty_tidak_menetas'=>$get_data->qty_tidak_menetas,
										   'jenis'=>$get_data->jenis,
										   'status'=>$get_data->status,
										   'primer'=>$get_data->primer,
										   'note_secunder'=>$get_data->note_secunder,
										   'notes'=>$get_data->notes,
										   'island'=>$get_data->island,
										   'date_survey'=>$get_data->date_survey,
										   'image_hidup'=>config('app.url').'/assets/uploads/'.$get_data->image_hidup,
										   'image_mati'=>config('app.url').'/assets/uploads/'.$get_data->image_mati,
										   'image_cangkang'=>config('app.url').'/assets/uploads/'.$get_data->image_cangkang,
										   'image_sarang'=>config('app.url').'/assets/uploads/'.$get_data->image_sarang,
										   'image_aktivitas'=>config('app.url').'/assets/uploads/'.$get_data->image_aktivitas,
										   'created_at'=>$get_data->created_at];
			}
			$status = true;
			$data 	= $json;
			$alert 	= "Data Tukik Diketemukan.";
		}else{
			$status = false;
			$data 	= array();
			$alert 	= "Data Tukik Belum Diketemukan.";
		}
		$json = array('status'=>$status,'data'=>$data,'alert'=>$alert);
		return response()->json($json);
	}

	public function get_id(Request $request){
		if($request->has('id') && $request->has('id_surveyor')){
			$id 			= $request->get('id');
			$id_surveyor 	= $request->get('id_surveyor');
			$get_tukik 		= $this->tukik->get_id($id);
			if(count($get_tukik) > 0){
				if($get_tukik->surveyor_id == $id_surveyor){
					$status = true;
					$data 	= $get_tukik;
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
			$surveyor_id 			= $request->get('surveyor_id');
			$id_prov 				= $request->get('id_prov');
			$id_kabkota 			= $request->get('id_kabkota');
			$id_kecamatan 			= $request->get('id_kecamatan');
			$id_kelurahan 			= $request->get('id_kelurahan');
			$weather  				= $request->get('cuaca');
			$coordinate				= $request->get('koordinat');
			$jenis					= $request->get('jenis');
			$qty_hidup				= $request->get('qty_hidup');
			$qty_mati				= $request->get('qty_mati');	
			$qty_cangkang_kosong	= $request->get('qty_cangkang_kosong');
			$qty_tidak_menetas		= $request->get('qty_tidak_menetas');
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
			if($request->hasFile('image_hidup')){
				$image_hidup		= $request->file('image_hidup');
				$filename_hidup 	= date('YmdHis').'.png';
				$image_hidup->move($this->path,$filename_hidup);
				$insert['image_hidup']	= $filename_hidup;
			}
			if($request->hasFile('image_mati')){
				$image_mati		= $request->file('image_mati');
				$filename_mati 	= date('YmdHis').'.png';
				$image_mati->move($this->path,$filename_mati);
				$insert['image_mati']	= $filename_mati;
			}
			if($request->hasFile('image_cangkang')){
				$image_cangkang		= $request->file('image_cangkang');
				$filename_cangkang 	= date('YmdHis').'.png';
				$image_cangkang->move($this->path,$filename_cangkang);
				$insert['image_cangkang']	= $filename_cangkang;
			}
			if($request->hasFile('image_sarang')){
				$image_sarang			= $request->file('image_sarang');
				$filename_sarang 		= date('YmdHis').'.png';
				$image_sarang->move($this->path,$filename_sarang);
				$insert['image_sarang']	= $filename_sarang;
			}
			if($request->hasFile('image_aktivitas')){
				$image_aktivitas			= $request->file('image_aktivitas');
				$filename_aktivitas			= date('YmdHis').'.png';
				$image_aktivitas->move($this->path,$filename_aktivitas);
				$insert['image_aktivitas']	= $filename_aktivitas;
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
			$insert['qty_hidup'] 			= $qty_hidup;
			$insert['qty_mati'] 			= $qty_mati;
			$insert['qty_cangkang_kosong']	= $qty_cangkang_kosong;
			$insert['qty_tidak_menetas']	= $qty_tidak_menetas;
			$insert['coordinate'] 			= $coordinate;
			$insert['jenis'] 				= $jenis;
			$insert['updated_at'] 			= date('Y-m-d H:i:s');
			if($this->tukik->edit($id,$insert)){
				$get_data 				= $this->tukik->get_id($id);
				$get_prov 				= $this->lokasi->get_prov_idprov($get_data->province_id);
				$get_city 				= $this->lokasi->get_city_idcity($get_data->province_id,$get_data->city_id);
				$get_district 			= $this->lokasi->get_kec_idkec($get_data->province_id,$get_data->city_id,$get_data->district_id);
				// $get_village 			= $this->lokasi->get_kel_idkel($get_data->province_id,$get_data->city_id,$get_data->district_id,$get_data->village_id);
				$json 					= ['id_tukik' => $id,'prov_id'=>$get_data->province_id,'prov_name'=>$get_prov->lokasi_nama,'city_id'=>$get_data->city_id,'city_name'=>$get_city->lokasi_nama,
										   'district_id'=>$get_data->district_id,'district_name'=>$get_district->lokasi_nama,'village_name'=>$get_data->village_name,
										   'weather'=>$get_data->weather,'coordinate'=>$get_data->coordinate,
										   'qty_hidup'=>$get_data->qty_hidup,'qty_mati'=>$get_data->qty_mati,'qty_cangkang_kosong'=>$get_data->qty_cangkang_kosong,'qty_tidak_menetas'=>$get_data->qty_tidak_menetas,
										   'jenis'=>$get_data->jenis,
										   'image_hidup'=>config('app.url').'/assets/uploads/'.$get_data->image_hidup,
										   'image_mati'=>config('app.url').'/assets/uploads/'.$get_data->image_mati,
										   'image_cangkang'=>config('app.url').'/assets/uploads/'.$get_data->image_cangkang,
										   'image_sarang'=>config('app.url').'/assets/uploads/'.$get_data->image_sarang,
										   'image_aktivitas'=>config('app.url').'/assets/uploads/'.$get_data->image_aktivitas,
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
