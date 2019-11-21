<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Jobs\SendVerificationEmail;

use Marketplace\User;
use Marketplace\UserAddress;
use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\Desa;

use Validator;
use Hash;
use Auth;
use Image;

use Nexmo;
use Marketplace\UserLinked;
use Marketplace\SmsRecord;

class SettingController extends Controller
{
	public function profile(Request $request)
	{
        // Initialization
        $pageTitle = 'Pengaturan';
        $pageSubTitle = 'Data Diri';
        $user_id = Auth::user()->id;

		// Lists
		$places = Kabupaten::orderBy('province_id', 'asc')
			->get();
		
		// Return View
		return view('setting.profile')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'places' => $places,
        ]);
	}
	protected function validatorProfile(array $data)
	{
		$data['username'] = str_slug($data['username']);
	    $data['username'] = str_replace('-','_',$data['username']);
		$data['bio'] = filter_var($data['bio'], FILTER_SANITIZE_STRING);

		if ($data['username'] == Auth::user()->username) {
			return Validator::make($data, [
			   	'name' => 'required|max:255',
				'phone' => 'required|numeric',
				'date_birth' => 'required|date',
				'place_birth' => 'required|integer',
				'bio' => 'required',
			]);
		}

		return Validator::make($data, [
		   'username' => 'required|max:255|unique:users',
		   'name' => 'required|max:255',
		   'phone' => 'required|numeric',
		   'date_birth' => 'required|date',
		   'place_birth' => 'required|integer',
		   'bio' => 'required',
	    ]);
    }
	public function updateProfile(Request $request)
	{
		if (Auth::user()->activated == 2) {
            return redirect()->back()
                ->with('warning', 'Maaf akun anda terblokir untuk informasi lebih lanjut silahkan hubungi customer service MSP Mall.');
        } else if(Auth::user()->activated == 0){
            return redirect()->back()
                ->with('warning', 'Silahkan aktivasi akun anda.');
		}

        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
		
		// Validation
		$this->validatorProfile($request->all())->validate();

		// Transaction
		//DB::beginTransaction();

		// Initialization
		$status = null;
		$data = $request->all();

	    $data['username'] = str_slug($data['username']);
		$data['username'] = str_replace('-','_',$data['username']);
		
		// Update Photo
		if (!empty($request->photo))
		{
			// Validation
			$validator = Validator::make($request->all(), [
				'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=300,min_height=300'
			]);

			if ($validator->fails()) {
				return redirect()
					->route('setting')
					//->with('warning', 'Terjadi Kesalahan!! Resolusi minimal Foto Profil 300 x 300px || Ukuran Foto Maksimal : 2MB')
					->withErrors($validator);
			}

			// Unlink Photo
			if (Auth::user()->photo != "default.png") {
				$fileDelete = $public.'uploads/photos/'.Auth::user()->photo;

				$fileDeleteLarge = $public.'uploads/photos/large-'.Auth::user()->photo;
				$fileDeleteMedium = $public.'uploads/photos/medium-'.Auth::user()->photo;
				$fileDeleteSmall = $public.'uploads/photos/small-'.Auth::user()->photo;

				if (file_exists($fileDelete)) { unlink($fileDelete); }
				if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
				if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
				if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }
			}

			// Upload Photo
			$imageName = md5(Auth::user()->id.Auth::user()->username.$request->photo->getClientOriginalName()).'.'.$request->photo->getClientOriginalExtension();
			$imagePath = $public.'uploads/photos/'.$imageName;

			$update = User::find(Auth::user()->id);
			$update->photo = $imageName;
			$update->save();

			$request->photo->move($public.'uploads/photos/', $imageName);

			$imageLarge = Image::make($imagePath)->fit(300, 300);
			$imageLarge->save($public.'uploads/photos/large-'.$imageName);

			$imageMedium = Image::make($imagePath)->fit(125, 125);
			$imageMedium->save($public.'uploads/photos/medium-'.$imageName);

			$imageSmall = Image::make($imagePath)->fit(45, 45);
			$imageSmall->save($public.'uploads/photos/small-'.$imageName);
		}

		// Update
		$update = User::find(Auth::user()->id);
		//$update->username = $data['username'];
		$update->name = $data['name'];
		$update->place_birth = $data['place_birth'];
		$update->date_birth = $data['date_birth'];
		$update->phone = $data['phone'];
		$update->bio = filter_var($data['bio'], FILTER_SANITIZE_STRING);

		if (!$update->save()) {
			// Return Redirect
			return redirect()
				->route('setting')
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap lengkapi formulir di bawah ini dengan benar.');
		}

		//DB::commit();

		// Return Redirect
		return redirect()
			->route('setting')
			->with('status', 'Selamat!! Perubahan anda telah berhasil disimpan. '.$status);
	}

	public function password(Request $request)
	{
        // Initialization
        $pageTitle = 'Pengaturan';
        $pageSubTitle = 'Password';
        $user_id = Auth::user()->id;
		
		// Return View
		return view('setting.password')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
        ]);
	}
	protected function validatorPassword(array $data)
	{
		return Validator::make($data, [
			'old_password' => 'required|min:8',
			'new_password' => 'required|min:8',
			'confirm_password' => 'required|min:8',
	    ]);
    }
	public function updatePassword(Request $request)
	{
		// Validation
		$this->validatorPassword($request->all())->validate();

		// Initialization
		$data = $request->all();

		// Check
		$user = User::find(Auth::user()->id);

		if ($data['new_password'] != $data['confirm_password']) {
			// Return Redirect
			return redirect()
				->route('setting.password')
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap masukkan Password Baru dan Konfirmasi Password dengan benar.');
		}

		if (!Hash::check($data['old_password'], $user->password)) {
			// Return Redirect
			return redirect()
				->route('setting.password')
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap Masukkan Password Lama dengan benar.');
		}

		$user->password = Hash::make($data['new_password']);

		if (!$user->save()) {
			// Return Redirect
			return redirect()
				->route('setting.password')
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap lengkapi formulir di bawah ini dengan benar.');
		}

		// Return Redirect
		return redirect()
			->route('setting.password')
			->with('status', 'Selamat! Password Anda telah berhasil di ubah.');
	}

	public function address(Request $request)
	{
        // Initialization
        $pageTitle = 'Pengaturan';
        $pageSubTitle = 'Daftar Alamat';
        $user_id = Auth::user()->id;

		// Lists
		$lists = UserAddress::where('user_id', Auth::user()->id)
			->get();
		
		// Return View
		return view('setting.address.index')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'lists' => $lists,
        ]);
	}
	protected function validatorAddress(array $data)
	{
		$data['address'] = filter_var($data['address'], FILTER_SANITIZE_STRING);

		if (!empty($data['id']))
		{
			return Validator::make($data, [
				'address_name' => 'required|max:255',
				'first_name' => 'required|max:255',
				'last_name' => 'required|max:255',
				'phone' => 'required|numeric',
				'address' => 'required',
				'postal_code' => 'required|integer',
			]);
		}

		return Validator::make($data, [
			'address_name' => 'required|max:255',
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
 		   	'phone' => 'required|numeric',
		   	'provinsi_id' => 'required|integer',
		   	'kabupaten_id' => 'required|integer',
		   	'kecamatan_id' => 'required|integer',
		   	'desa_id' => 'required|integer',
		   	'address' => 'required',
		  	'postal_code' => 'required|integer',
	    ]);
	}
	
	public function addAddress(Request $request)
	{
        // Initialization
        $pageTitle = 'Pengaturan';
        $pageSubTitle = 'Tambah Alamat';

		// Lists
        $dataProvinsi = Provinsi::orderBy('name', 'asc')
			->get();
		
		// Return View
		return view('setting.address.add')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'dataProvinsi' => $dataProvinsi,
        ]);
	}
	public function storeAddress(Request $request)
	{
		// Validation
		$this->validatorAddress($request->all())->validate();

		// Initialization
		$data = $request->all();

		// Create
		$create = new UserAddress;
		$create->user_id = Auth::user()->id;
		$create->address_name = $data['address_name'];
		$create->first_name = $data['first_name'];
		$create->last_name = $data['last_name'];
		$create->phone = $data['phone'];
		$create->provinsi_id = $data['provinsi_id'];
		$create->kabupaten_id = $data['kabupaten_id'];
		$create->kecamatan_id = $data['kecamatan_id'];
		$create->desa_id = $data['desa_id'];
		$create->address = filter_var($data['address'], FILTER_SANITIZE_STRING);
		$create->postal_code = $data['postal_code'];

		if (!$create->save()) {
			if (!empty($request->checkout)) {
				// Return Redirect
				return redirect()
					->route('checkout')
					->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap lengkapi formulir Alamat dengan benar.');
			}

			// Return Redirect
			return redirect()
				->route('setting.address.add')
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap lengkapi formulir di bawah ini dengan benar.');
		}

		if (!empty($request->checkout)) {
			// Return Redirect
			return redirect()
				->route('checkout')
				->with('status', 'Selamat!! Alamat Baru telah berhasil ditambahkan.');
		}

		// Return Redirect
		return redirect()
			->route('setting.address')
			->with('status', 'Selamat!! Alamat Baru telah berhasil ditambahkan.');
	}

	public function editAddress(Request $request)
	{
        // Initialization
        $pageTitle = 'Pengaturan';
		$pageSubTitle = 'Ubah Alamat';
		$id = $request->id;

		// Check
		$address = UserAddress::where('id', $id)
			->where('user_id', Auth::user()->id)
			->first();

		if (empty($address)) {
			return redirect('/');
		}

		// Lists
        $dataProvinsi = Provinsi::orderBy('name', 'asc')
			->get();
		
		// Return View
		return view('setting.address.edit')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
			'address' => $address,
			'chained' => true,
			
			'dataProvinsi' => $dataProvinsi,
        ]);
	}
	public function updateAddress(Request $request)
	{
		// Validation
		$this->validatorAddress($request->all())->validate();

		// Initialization
		$data = $request->all();
		$id = $data['id'];

		// Check
		$check = UserAddress::where('id', $id)
			->where('user_id', Auth::user()->id)
			->first();

		if (empty($check)) {
			// Return Redirect
			return redirect()
				->route('setting.address');
		}

		// Transaction
		//DB::beginTransaction();

		// Create
		$create = UserAddress::find($id);
		$create->address_name = $data['address_name'];
		$create->first_name = $data['first_name'];
		$create->last_name = $data['last_name'];
		$create->phone = $data['phone'];

		if (!empty($data['kabupaten_id']))
		{
			$create->provinsi_id = $data['provinsi_id'];
			$create->kabupaten_id = $data['kabupaten_id'];
			$create->kecamatan_id = $data['kecamatan_id'];
			$create->desa_id = $data['desa_id'];
		}

		$create->address = filter_var($data['address'], FILTER_SANITIZE_STRING);
		$create->postal_code = $data['postal_code'];

		if (!$create->save()) {
			// Return Redirect
			return redirect()
				->route('setting.address.edit', ['id' => $id])
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap lengkapi formulir di bawah ini dengan benar.');
		}

		//DB::commit();

		// Return Redirect
		return redirect()
			->route('setting.address')
			->with('status', 'Selamat!! Alamat Anda telah berhasil diperbaruhi.');
	}
	public function deleteAddress(Request $request)
	{
		// Delete
		$delete = UserAddress::where('id', $request->id)
			->where('user_id', Auth::user()->id)
			->delete();

		// Return Redirect
		return redirect()
			->route('setting.address')
			->with('status', 'Alamat telah berhasil di Hapus.');
	}
}
