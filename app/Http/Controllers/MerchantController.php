<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\Desa;
use Marketplace\User;
use Marketplace\UserType;
use Marketplace\Merchant;
use Marketplace\MerchantAddress;
use Marketplace\MerchantFinance;

use Marketplace\MerchantAccountRequest;
use Marketplace\MerchantStoreRequest;
use Marketplace\MerchantFinanceRequest;

use Auth;
use Image;
use Carbon\Carbon;

class MerchantController extends Controller
{
	public function join()
	{
        // Initialization
        $pageTitle = 'Menjadi Merchant';
        $pageSubTitle = 'Informasi Akun';
        $user_id = Auth::user()->id;

        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();
        
        if (!empty($merchant))
        {
            if ($merchant->status == 1) {
                return redirect('/');
            }
            if ($merchant->status == 3)
            {
                return redirect()->route('merchant.complete');
            }
        }

		// Lists
		$places = Kabupaten::orderBy('province_id', 'asc')
			->get();
		
		// Return View
		return view('merchant.join')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'places' => $places,
            'merchant' => $merchant,
        ]);
    }
    public function one(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|numeric',
            'place_birth' => 'required|integer',
            'date_birth' => 'required|date',
            'identity_number' => 'required|max:255',
            'identity_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Initialization
        $user_id = Auth::user()->id;
        $name = $request->name;
        $phone = $request->phone;
        $place_birth = $request->place_birth;
        $date_birth = $request->date_birth;
        $identity_number = $request->identity_number;
        $identity_photo = $request->identity_photo;
        
        // Transaction Update
        //DB::beginTransaction();

        if (!empty($request->identity_photo))
        {
            $directory = 'identities';

            // Upload New Photo
            $identity_photo = md5(Auth::user()->id).'.'.$request->identity_photo->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$identity_photo;

            $request->identity_photo->move($public.'uploads/'.$directory.'/', $identity_photo);

            // Resize Photo
            $resize = Image::make($path)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resize->save($public.'uploads/'.$directory.'/'.$identity_photo);
        }

        // Update
        $update = User::where('id', $user_id)->update([
            'name' => $name,
            'phone' => $phone,
            'place_birth' => $place_birth,
            'date_birth' => $date_birth,
            'identity_name' => $name,
            'identity_number' => $identity_number,
            'identity_photo' => $identity_photo,
        ]);

        //DB::commit();

        // Return Redirect Update Success
        return redirect()
            ->route('merchant.store');
    }

	public function store()
	{
        // Initialization
        $pageTitle = 'Menjadi Merchant';
        $pageSubTitle = 'Informasi Toko';
        $user_id = Auth::user()->id;
		
        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();

		// Lists
		$types = UserType::orderBy('id', 'asc')
            ->get();
            
        $dataProvinsi = Provinsi::orderBy('name', 'asc')
            ->get();
		
		// Return View
		return view('merchant.store')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'merchant' => $merchant,

            'types' => $types,
            'dataProvinsi' => $dataProvinsi,
        ]);
    }
    public function two(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'type_id' => 'required|integer',
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'additional_id' => 'required|integer',
            'address' => 'required|max:255',
            'provinsi_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'desa_id' => 'required|integer',
            'postal_code' => 'required|numeric',
            'checkbox' => 'required',
        ]);

        // Initialization
        $user_id = Auth::user()->id;
        $type_id = $request->type_id;
        $name = $request->name;
        $category_id = $request->category_id;
        $additional_id = $request->additional_id;
        $address = $request->address;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $desa_id = $request->desa_id;
        $postal_code = $request->postal_code;
        $referral_name = $request->referral_name;
        
        // Transaction
        //DB::beginTransaction();

        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();

        if (empty($merchant))
        {
            // Merchant Insert
            $insert = new Merchant;
            $insert->user_id = $user_id;
            $insert->type_id = $type_id;
            $insert->name = $name;
            $insert->referral_name = $referral_name;
            $insert->category_id = $category_id;
            $insert->additional_id = $additional_id;
            $insert->save();

            // Merchant ID
            $merchant_id = $insert->id;

            // Merchant Address Insert
            $insert = new MerchantAddress;
            $insert->merchant_id = $merchant_id;
            $insert->address = $address;
            $insert->provinsi_id = $provinsi_id;
            $insert->kabupaten_id = $kabupaten_id;
            $insert->kecamatan_id = $kecamatan_id;
            $insert->desa_id = $desa_id;
            $insert->postal_code = $postal_code;
            $insert->save();

            // Merchant Update
            $update = Merchant::where('user_id', $user_id)->update([
                'address_id' => $insert->id,
            ]);
        }

        if (!empty($merchant))
        {
            // Merchant Update
            $update = Merchant::where('user_id', $user_id)->update([
                'type_id' => $type_id,
                'name' => $name,
                'referral_name' => $referral_name,
                'category_id' => $category_id,
                'additional_id' => $additional_id,
            ]);

            // Merchant Address Insert
            if(empty($merchant->address_id))
            {  
                // Insert
                $insert = new MerchantAddress;
                $insert->merchant_id = $merchant->merchant_id;
                $insert->address = $address;
                $insert->provinsi_id = $provinsi_id;
                $insert->kabupaten_id = $kabupaten_id;
                $insert->kecamatan_id = $kecamatan_id;
                $insert->desa_id = $desa_id;
                $insert->postal_code = $postal_code;
                $insert->save();

                // Merchant Update
                $update = Merchant::where('user_id', $user_id)->update([
                    'address_id' => $insert->id,
                ]);
            }

            // Merchant Address Update
            if(!empty($merchant->address_id))
            {
                $update = MerchantAddress::where('id', $merchant->address_id)->update([
                    'address' => $address,
                    'provinsi_id' => $provinsi_id,
                    'kabupaten_id' => $kabupaten_id,
                    'kecamatan_id' => $kecamatan_id,
                    'desa_id' => $desa_id,
                    'postal_code' => $postal_code,
                ]);
            }
        }

        // Merchant Update
        $update = User::where('id', $user_id)->update([
            'name' => $name,
            'place_birth' => $kabupaten_id,
        ]);

        //DB::commit();

        // Return Redirect Update Success
        return redirect()
            ->route('merchant.finance');
    }

	public function finance()
	{
        // Initialization
        $pageTitle = 'Menjadi Merchant';
        $pageSubTitle = 'Informasi Administrasi';
		
		// Return View
		return view('merchant.finance')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
        ]);
    }
    public function three(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'bank_name' => 'required|max:255',
            'bank_branch' => 'required|max:255',
            'account_number' => 'required|numeric',
            'account_name' => 'required|max:255',
            'npwp_number' => 'required|numeric',
            'npwp_name' => 'required|max:255',
            'npwp_address' => 'required|max:255',
            'npwp_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Initialization
        $user_id = Auth::user()->id;
        $bank_name = $request->bank_name;
        $bank_branch = $request->bank_branch;
        $account_number = $request->account_number;
        $account_name = $request->account_name;
        $npwp_number = $request->npwp_number;
        $npwp_name = $request->npwp_name;
        $npwp_address = $request->npwp_address;
        $npwp_photo = $request->npwp_photo;

        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();
        
        if (empty($merchant))
        {
            return redirect()
                ->route('merchant.store');
        }
        
        // Transaction
        //DB::beginTransaction();

        // Merchant Finance Insert
        if (empty($merchant->finance_id))
        {
            if (!empty($request->npwp_photo))
            {
                $directory = 'npwp';
    
                // Upload New Photo
                $npwp_photo = md5(Auth::user()->id).'.'.$request->npwp_photo->getClientOriginalExtension();
                $path = $public.'uploads/'.$directory.'/'.$npwp_photo;
    
                $request->npwp_photo->move($public.'uploads/'.$directory.'/', $npwp_photo);
    
                // Resize Photo
                $resize = Image::make($path)->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $resize->save($public.'uploads/'.$directory.'/'.$npwp_photo);
            }

            // Insert
            $insert = new MerchantFinance;
            $insert->merchant_id = $merchant->id;
            $insert->bank_name = $bank_name;
            $insert->bank_branch = $bank_branch;
            $insert->account_number = $account_number;
            $insert->account_name = $account_name;
            $insert->npwp_number = $npwp_number;
            $insert->npwp_name = $npwp_name;
            $insert->npwp_address = $npwp_address;
            $insert->npwp_photo = $npwp_photo;
            $insert->save();

            // Merchant Update
            $update = Merchant::where('user_id', $user_id)->update([
                'finance_id' => $insert->id,
                'status' => 3,
            ]);
        }

        // Merchant Finance Update
        if (!empty($merchant->finance_id))
        {
            if (!empty($request->npwp_photo))
            {
                $directory = 'npwp';
    
                // Upload New Photo
                $npwp_photo = md5(Auth::user()->id).'.'.$request->npwp_photo->getClientOriginalExtension();
                $path = $public.'uploads/'.$directory.'/'.$npwp_photo;
    
                $request->npwp_photo->move($public.'uploads/'.$directory.'/', $npwp_photo);
    
                // Resize Photo
                $resize = Image::make($path)->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $resize->save($public.'uploads/'.$directory.'/'.$npwp_photo);
            }

            $update = MerchantFinance::where('id', $merchant->finance_id)->update([
                'bank_name' => $bank_name,
                'bank_branch' => $bank_branch,
                'account_number' => $account_number,
                'account_name' => $account_name,
                'npwp_number' => $npwp_number,
                'npwp_name' => $npwp_name,
                'npwp_address' => $npwp_address,
                'npwp_photo' => $npwp_photo,
            ]);

            // Merchant Update
            $update = Merchant::where('user_id', $user_id)->update([
                'status' => 3,
            ]);
        }

        //DB::commit();

        // Return Redirect Success
        return redirect()
            ->route('merchant.complete');
    }

	public function complete()
	{
        // Initialization
        $pageTitle = 'Menjadi Merchant';
        $user_id = Auth::user()->id;

        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();
        
        if (empty($merchant))
        {
            return redirect()
                ->route('merchant.finance');
        }
		
		// Return View
		return view('merchant.complete')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
        ]);
    }

    
	public function editAccount()
	{
        // Initialization
        $pageTitle = 'Merchant';
        $pageSubTitle = 'Informasi Akun';
        $user_id = Auth::user()->id;

        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();

        $check = MerchantAccountRequest::where('user_id', $user_id)
            ->where('status', '!=', 1)
            ->first();

        // Lists
        $places = Kabupaten::orderBy('province_id', 'asc')
            ->get();
		
		// Return View
		return view('merchant.edit.account')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'merchant' => $merchant,
            'places' => $places,
            'check' => $check,
        ]);
    }
    public function updateAccount(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'identity_name' => 'required|max:255',
            'phone' => 'required|numeric',
            'place_birth' => 'required|integer',
            'date_birth' => 'required|date',
            'identity_number' => 'required|max:255',
        ]);

        // Initialization
        $pageTitle = 'Informasi Akun';
        $directory = 'identities';

        $user_id = Auth::user()->id;
        $merchant_id = Auth::user()->merchant_id;

        $identity_name = $request->identity_name;
        $phone = $request->phone;
        $place_birth = $request->place_birth;
        $date_birth = $request->date_birth;
        $identity_number = $request->identity_number;
        $identity_photo = null;

        // Check
        $check = MerchantAccountRequest::where('user_id', $user_id)
            ->where('status', '!=', 1)
            ->first();
        
        // Transaction Update
        //DB::beginTransaction();

        // Upload
        if (!empty($request->identity_photo))
        {
            // Validation
            $validated = $request->validate([
                'identity_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete Old photo
            if (!empty($check->identity_photo)) {
                $delete = $public.'uploads/'.$directory.'/'.$check->identity_photo;

                if (file_exists($delete)) { unlink($delete); }
            }

            // Photo File
            $identity_photo = $request->identity_photo;

            // Upload New Photo
            $identity_photo = md5($user_id.Carbon::now()).'.'.$request->identity_photo->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$identity_photo;

            $request->identity_photo->move($public.'uploads/'.$directory.'/', $identity_photo);

            // Resize Photo
            $resize = Image::make($path)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resize->save($public.'uploads/'.$directory.'/'.$identity_photo);
        }

        // Update
        if (!empty($check)) {
            // Update
            $update = MerchantAccountRequest::where('id', $check->id)->update([
                'identity_name' => $identity_name,
                'phone' => $phone,
                'place_birth' => $place_birth,
                'date_birth' => $date_birth,
                'identity_number' => $identity_number,
                'identity_photo' => $identity_photo,
                'status' => 0,
            ]);
        }

        // Insert
        if (empty($check)) {
            $insert = new MerchantAccountRequest;
            $insert->user_id = $user_id;
            $insert->merchant_id = $merchant_id;
            $insert->identity_name = $identity_name;
            $insert->phone = $phone;
            $insert->place_birth = $place_birth;
            $insert->date_birth = $date_birth;
            $insert->identity_number = $identity_number;
            $insert->identity_photo = $identity_photo;
            $insert->status = 0;
            $insert->save();
        }

        //DB::commit();

        // Return Redirect
		return redirect()
			->route('merchant.account.edit')
			->with('status', 'Selamat!! Permintaan Perubahan '.$pageTitle.' telah berhasil dikirim.');
    }
    
	public function editStore()
	{
        // Initialization
        $pageTitle = 'Merchant';
        $pageSubTitle = 'Informasi Toko';
        $user_id = Auth::user()->id;
		
        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();

        $check = MerchantStoreRequest::where('user_id', $user_id)
            ->where('status', '!=', 1)
            ->first();

		// Lists
		$types = UserType::orderBy('id', 'asc')
            ->get();
            
        $dataProvinsi = Provinsi::orderBy('name', 'asc')
            ->get();
		
		// Return View
		return view('merchant.edit.store')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'merchant' => $merchant,
            'check' => $check,

            'types' => $types,
            'dataProvinsi' => $dataProvinsi,
        ]);
    }
    public function updateStore(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'type_id' => 'required|integer',
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'additional_id' => 'required|integer',
            
            'address' => 'required|max:255',
            'provinsi_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'desa_id' => 'required|integer',
            'postal_code' => 'required|numeric',
        ]);

        // Initialization
        $pageTitle = 'Informasi Toko';
        
        $user_id = Auth::user()->id;
        $merchant_id = Auth::user()->merchant_id;
        
        $type_id = $request->type_id;
        $name = $request->name;
        $category_id = $request->category_id;
        $additional_id = $request->additional_id;
        $address = $request->address;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $desa_id = $request->desa_id;
        $postal_code = $request->postal_code;

        // Check
        $check = MerchantStoreRequest::where('user_id', $user_id)
            ->where('status', '!=', 1)
            ->first();
        
        // Transaction
        //DB::beginTransaction();

        // Update
        if (!empty($check))
        {
            $update = MerchantStoreRequest::where('id', $check->id)->update([
                'type_id' => $type_id,
                'name' => $name,
                'category_id' => $category_id,
                'additional_id' => $additional_id,

                'address' => $address,
                'provinsi_id' => $provinsi_id,
                'kabupaten_id' => $kabupaten_id,
                'kecamatan_id' => $kecamatan_id,
                'desa_id' => $desa_id,
                'postal_code' => $postal_code,
                'status' => 0,
            ]);
        }

        // Insert
        if (empty($check))
        {
            // Merchant Insert
            $insert = new MerchantStoreRequest;
            $insert->user_id = $user_id;
            $insert->merchant_id = $merchant_id;

            $insert->type_id = $type_id;
            $insert->name = $name;
            $insert->category_id = $category_id;
            $insert->additional_id = $additional_id;
            
            $insert->address = $address;
            $insert->provinsi_id = $provinsi_id;
            $insert->kabupaten_id = $kabupaten_id;
            $insert->kecamatan_id = $kecamatan_id;
            $insert->desa_id = $desa_id;
            $insert->postal_code = $postal_code;
            $insert->status = 0;
            $insert->save();
        }

        //DB::commit();

        // Return Redirect
		return redirect()
            ->route('merchant.store.edit')
            ->with('status', 'Selamat!! Permintaan Perubahan '.$pageTitle.' telah berhasil dikirim.');
    }

	public function editFinance()
	{
        // Initialization
        $pageTitle = 'Merchant';
        $pageSubTitle = 'Informasi Administrasi';
        $user_id = Auth::user()->id;
		
        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();

        $check = MerchantFinanceRequest::where('user_id', $user_id)
            ->where('status', '!=', 1)
            ->first();
		
		// Return View
		return view('merchant.edit.finance')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'merchant' => $merchant,
            'check' => $check,
        ]);
    }
    public function updateFinance(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'bank_name' => 'required|max:255',
            'bank_branch' => 'required|max:255',
            'account_number' => 'required|numeric',
            'account_name' => 'required|max:255',

            // 'npwp_number' => 'required|numeric',
            // 'npwp_name' => 'required|max:255',
            // 'npwp_address' => 'required|max:255',
        ]);

        // Initialization
        $pageTitle = 'Informasi Administrasi';
        $directory = 'npwp';

        $user_id = Auth::user()->id;
        $merchant_id = Auth::user()->merchant_id;

        $bank_name = $request->bank_name;
        $bank_branch = $request->bank_branch;
        $account_number = $request->account_number;
        $account_name = $request->account_name;
        $npwp_number = $request->npwp_number;
        $npwp_name = $request->npwp_name;
        $npwp_address = $request->npwp_address;
        $npwp_photo = null;

        // Check
        $check = MerchantFinanceRequest::where('user_id', $user_id)
            ->where('status', '!=', 1)
            ->first();
        
        // Transaction
        //DB::beginTransaction();

        //Upload
        if (!empty($request->npwp_photo))
        {
            // Validation
            $validated = $request->validate([
                'npwp_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete Old photo
            if (!empty($check->npwp_photo)) {
                $delete = $public.'uploads/'.$directory.'/'.$check->npwp_photo;

                if (file_exists($delete)) { unlink($delete); }
            }

            // Photo File
            $npwp_photo = $request->npwp_photo;

            // Upload New Photo
            $npwp_photo = md5($user_id.Carbon::now()).'.'.$request->npwp_photo->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$npwp_photo;

            $request->npwp_photo->move($public.'uploads/'.$directory.'/', $npwp_photo);

            // Resize Photo
            $resize = Image::make($path)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resize->save($public.'uploads/'.$directory.'/'.$npwp_photo);
        }

        // Update
        if (!empty($check))
        {
            $update = MerchantFinanceRequest::where('id', $check->id)->update([
                'bank_name' => $bank_name,
                'bank_branch' => $bank_branch,
                'account_number' => $account_number,
                'account_name' => $account_name,
                'npwp_number' => $npwp_number,
                'npwp_name' => $npwp_name,
                'npwp_address' => $npwp_address,
                'npwp_photo' => $npwp_photo,
                'status' => 0,
            ]);
        }
        // Insert
        if (empty($check))
        {
            // Insert
            $insert = new MerchantFinanceRequest;
            $insert->user_id = $user_id;
            $insert->merchant_id = $merchant_id;
            $insert->bank_name = $bank_name;
            $insert->bank_branch = $bank_branch;
            $insert->account_number = $account_number;
            $insert->account_name = $account_name;
            $insert->npwp_number = $npwp_number;
            $insert->npwp_name = $npwp_name;
            $insert->npwp_address = $npwp_address;
            $insert->npwp_photo = $npwp_photo;
            $insert->status = 0;
            $insert->save();
        }

        //DB::commit();

        // Return Redirect
		return redirect()
			->route('merchant.finance.edit')
			->with('status', 'Selamat!! Permintaan Perubahan '.$pageTitle.' telah berhasil dikirim.');
    }

	public function editShipping()
	{
        // Initialization
        $pageTitle = 'Merchant';
        $pageSubTitle = 'Metode Pengiriman';
        $user_id = Auth::user()->id;
		
        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();
		
		// Return View
		return view('merchant.edit.shipping')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'merchant' => $merchant,
        ]);
    }
    public function updateShipping(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'shipping_pos' => 'required|integer',
            'shipping_jne' => 'required|integer',
            'shipping_tiki' => 'required|integer',
        ]);

        // Initialization
        $user_id = Auth::user()->id;
        $shipping_pos = $request->shipping_pos;
        $shipping_jne = $request->shipping_jne;
        $shipping_tiki = $request->shipping_tiki;
		
        // Check
        $merchant = Merchant::where('user_id', $user_id)
            ->first();

        if (empty($merchant)) {
			// Return Redirect
        	return redirect('/');
        }
        
        // Transaction Update
        //DB::beginTransaction();

        // Update
        $update = Merchant::where('user_id', $user_id)->update([
            'shipping_pos' => $shipping_pos,
            'shipping_jne' => $shipping_jne,
            'shipping_tiki' => $shipping_tiki,
        ]);

        //DB::commit();

        // Return Redirect Update Success
		return redirect()
			->route('merchant.shipping.edit')
			->with('status', 'Selamat!! Perubahan anda telah berhasil disimpan.');
    }

    public function type(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'type' => 'required|integer',
        ]);

        // Initialization
        $type = $request->type;
        $content = null;
        
        // Check
        $check = UserType::where('id', $type)
            ->first();

        if (!empty($check)) {
            $content = $check->content;
        }
        
        return $content;
    }
}
