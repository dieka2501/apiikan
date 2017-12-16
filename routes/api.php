<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(array('prefix'=>'/v1'),function(){
	//Instansi
	Route::get('/instansi/all','InstansiController@get_all');

	//Register
	Route::post('/register','RegistrasiController@register');

	//login
	Route::post('/login','LoginController@login');

	//Reset Password
	Route::post('/resetpass','ResetPasswordController@verifyemail');

	//Do Reset Password
	Route::post('/resetpass/do','ResetPasswordController@do_reset');

	//Get Provinsi
	Route::get('/province/all','LokasiController@get_provinsi_all');	

	//Get Kabupaten
	Route::get('/city','LokasiController@get_kab_kota');

	//Get Kecamatan
	Route::get('/district','LokasiController@get_kecamatan');	

	//Get Kelurahan
	Route::get('/village','LokasiController@get_kelurahan');	

	//Get Jenis Penyu
	Route::get('/penyu/jenis','PenyuController@get_jenis_penyu');	

	//Get Jenis Hiu
	Route::get('/hiu/jenis','HiuMartilController@get_jenis_hiu');	

	Route::group(['middleware'=>['token']],function(){
		//Karang
		Route::post('/karang/post','KarangController@post_survey');	
		Route::get('/karang/list','KarangController@get_list');	
		Route::get('/karang/id','KarangController@get_id');	
		Route::post('/karang/edit','KarangController@edit');	

		//Hiu Paus
		Route::post('/hiupaus/post','HiuPausController@post_survey');	
		Route::get('/hiupaus/list','HiuPausController@get_list');
		Route::get('/hiupaus/id','HiuPausController@get_id');	
		Route::post('/hiupaus/edit','HiuPausController@edit');	

		//Pari Manta
		Route::post('/parimanta/post','PariMantaController@post_survey');	
		Route::get('/parimanta/list','PariMantaController@get_list');
		Route::get('/parimanta/id','PariMantaController@get_id');	
		Route::post('/parimanta/edit','PariMantaController@edit');

		//Penyu
		Route::post('/penyu/post','PenyuController@post_survey');
		Route::get('/penyu/list','PenyuController@get_list');
		Route::get('/penyu/id','PenyuController@get_id');	
		Route::post('/penyu/edit','PenyuController@edit');	

		//Tukik
		Route::post('/tukik/post','TukikController@post_survey');	
		Route::get('/tukik/list','TukikController@get_list');
		Route::get('/tukik/id','TukikController@get_id');	
		Route::post('/tukik/edit','TukikController@edit');
			
		//Napoleon
		Route::post('/napoleon/post','NapoleonController@post_survey');	
		Route::get('/napoleon/list','NapoleonController@get_list');
		Route::get('/napoleon/id','NapoleonController@get_id');	
		Route::post('/napoleon/edit','NapoleonController@edit');

		//Dugong
		Route::post('/dugong/post','DugongController@post_survey');	
		Route::get('/dugong/list','DugongController@get_list');
		Route::get('/dugong/id','DugongController@get_id');	
		Route::post('/dugong/edit','DugongController@edit');	

		//Paus Lumba-lumba
		Route::post('/pauslumba/post','PausLumbaController@post_survey');	
		Route::get('/pauslumba/list','PausLumbaController@get_list');	
		Route::get('/pauslumba/id','PausLumbaController@get_id');	
		Route::post('/pauslumba/edit','PausLumbaController@edit');

		//Bambu Laut
		Route::post('/bambulaut/post','BambuLautController@post_survey');	
		Route::get('/bambulaut/list','BambuLautController@get_list');	
		Route::get('/bambulaut/id','BambuLautController@get_id');	
		Route::post('/bambulaut/edit','BambuLautController@edit');	
		//Kima
		Route::post('/kima/post','KimaController@post_survey');	
		Route::get('/kima/list','KimaController@get_list');
		Route::get('/kima/id','KimaController@get_id');	
		Route::post('/kima/edit','KimaController@edit');	
			
		//Lola
		Route::post('/lola/post','LolaController@post_survey');	
		Route::get('/lola/list','LolaController@get_list');
		Route::get('/lola/id','LolaController@get_id');	
		Route::post('/lola/edit','LolaController@edit');	

		//Kuda Laut
		Route::post('/kudalaut/post','KudaLautController@post_survey');	
		Route::get('/kudalaut/list','KudaLautController@get_list');	
		Route::get('/kudalaut/id','KudaLautController@get_id');	
		Route::post('/kudalaut/edit','KudaLautController@edit');	

		//Bcf
		Route::post('/bcf/post','BcfController@post_survey');	
		Route::get('/bcf/list','BcfController@get_list');	
		Route::get('/bcf/id','BcfController@get_id');
		Route::post('/bcf/edit','BcfController@edit');	

		//mola
		Route::post('/mola/post','MolaController@post_survey');	
		Route::get('/mola/list','MolaController@get_list');	
		Route::get('/mola/id','MolaController@get_id');	
		Route::post('/mola/edit','MolaController@edit');	

		//sidat
		Route::post('/sidat/post','SidatController@post_survey');	
		Route::get('/sidat/list','SidatController@get_list');	
		Route::get('/sidat/id','SidatController@get_id');	
		Route::post('/sidat/edit','SidatController@edit');	

		//Teripang
		Route::post('/teripang/post','TeripangController@post_survey');	
		Route::get('/teripang/list','TeripangController@get_list');	
		Route::get('/teripang/id','TeripangController@get_id');	
		Route::post('/teripang/edit','TeripangController@edit');

		//Labi-labi
		Route::post('/labi/post','LabiController@post_survey');
		Route::get('/labi/list','LabiController@get_list');	
		Route::get('/labi/id','LabiController@get_id');	
		Route::post('/labi/edit','LabiController@edit');	

		//Hiu Martil
		Route::post('/hiumartil/post','HiuMartilController@post_survey');
		Route::get('/hiumartil/list','HiuMartilController@get_list');
		Route::get('/hiumartil/id','HiuMartilController@get_id');	
		Route::post('/hiumartil/edit','HiuMartilController@edit');	

		//Dashboard
		Route::get('/dashboard','DashboardController@dashboard');	

	});
});

