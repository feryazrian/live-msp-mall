<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;
use Carbon\Carbon;

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

class MerchantController extends Controller
{
    // Approve Account
    public function account()
    {
        // Initialization
        $pageTitle = 'Informasi Akun';
        $page = 'merchant.account';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function dataAccount()
    {
        // Initialization
        $items = array();
        $pageTitle = 'Informasi Akun';
        $page = 'merchant.account';

        // Lists
        $lists = MerchantAccountRequest::orderBy('updated_at', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            // Status
            $status = null;
            switch ($item->status) {
                case 1:
                    $status = '<div class="badge badge-success">Disetujui</div>';
                    break;

                case 2:
                    $status = '<div class="badge badge-danger">Ditolak</div>';
                    break;

                default:
                    $status = '<div class="badge badge-warning">Menunggu</div>';
                    break;
            }

            // Content
            $content = $item->status_content;
            if (empty($content)) {
                $content = '-';
            }

            // Array
            $items[] = array(
                'merchant' => $item->merchant->name,
                'status' => $status,
                'content' => $content,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function editAccount(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'identities';
        $pageTitle = 'Informasi Akun';
        $page = 'merchant.account';
        
        // Check
        $item = MerchantAccountRequest::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,
            'item' => $item,
        ]);
    }
    public function updateAccount(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $status = $request->status;
        $status_content = $request->status_content;
        $pageTitle = 'Informasi Akun';
        $page = 'merchant.account';
        
        // Check
        $item = MerchantAccountRequest::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $user_id = $item->user_id;

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = MerchantAccountRequest::where('id', $id)->update([
            'status' => $status,
            'status_content' => $status_content,
        ]);

        // Approve
        if ($status == 1)
        {
            // Update
            $update = User::where('id', $user_id)->update([
                'phone' => $item->phone,
                'place_birth' => $item->place_birth,
                'date_birth' => $item->date_birth,
                'identity_name' => $item->identity_name,
                'identity_number' => $item->identity_number,
            ]);

            // Update Photo
            if (!empty($item->identity_photo)) {
                $directory = 'identities';

                // Delete Old photo
                if (!empty($item->user->identity_photo)) {
                    $delete = $public.'uploads/'.$directory.'/'.$item->user->identity_photo;

                    if (file_exists($delete)) { unlink($delete); }
                }

                // Update
                $update = User::where('id', $user_id)->update([
                    'identity_photo' => $item->identity_photo,
                ]);
            }
        }

        //DB::commit();

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }


    // Approve Finance
    public function finance()
    {
        // Initialization
        $pageTitle = 'Informasi Administrasi';
        $page = 'merchant.finance';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function dataFinance()
    {
        // Initialization
        $items = array();
        $pageTitle = 'Informasi Administrasi';
        $page = 'merchant.finance';

        // Lists
        $lists = MerchantFinanceRequest::orderBy('updated_at', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            // Status
            $status = null;
            switch ($item->status) {
                case 1:
                    $status = '<div class="badge badge-success">Disetujui</div>';
                    break;

                case 2:
                    $status = '<div class="badge badge-danger">Ditolak</div>';
                    break;

                default:
                    $status = '<div class="badge badge-warning">Menunggu</div>';
                    break;
            }

            // Content
            $content = $item->status_content;
            if (empty($content)) {
                $content = '-';
            }

            // Array
            $items[] = array(
                'merchant' => $item->merchant->name,
                'status' => $status,
                'content' => $content,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function editFinance(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'identities';
        $pageTitle = 'Informasi Administrasi';
        $page = 'merchant.finance';
        
        // Check
        $item = MerchantFinanceRequest::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,
            'item' => $item,
        ]);
    }
    public function updateFinance(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $status = $request->status;
        $status_content = $request->status_content;
        $pageTitle = 'Informasi Administrasi';
        $page = 'merchant.finance';
        
        // Check
        $item = MerchantFinanceRequest::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $user_id = $item->user_id;
        $merchant_id = $item->merchant_id;

        $npwp_number = $item->npwp_number;
        $npwp_name = $item->npwp_name;
        $npwp_address = $item->npwp_address;

        // Merchant
        $finance = MerchantFinance::where('merchant_id', $merchant_id)
            ->first();

        if (empty($npwp_number)) {
            $npwp_number = $finance->npwp_number;
        }

        if (empty($npwp_name)) {
            $npwp_name = $finance->npwp_name;
        }

        if (empty($npwp_address)) {
            $npwp_address = $finance->npwp_address;
        }

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = MerchantFinanceRequest::where('id', $id)->update([
            'status' => $status,
            'status_content' => $status_content,
        ]);

        // Approve
        if ($status == 1)
        {
            // Update
            $update = MerchantFinance::where('merchant_id', $merchant_id)->update([
                'bank_name' => $item->bank_name,
                'bank_branch' => $item->bank_branch,
                'account_number' => $item->account_number,
                'account_name' => $item->account_name,

                'npwp_number' => $npwp_number,
                'npwp_name' => $npwp_name,
                'npwp_address' => $npwp_address,
            ]);

            // Update Photo
            if (!empty($item->npwp_photo)) {
                $directory = 'npwp';
                
                // Delete Old photo
                if (!empty($item->user->npwp_photo)) {
                    $delete = $public.'uploads/'.$directory.'/'.$item->user->npwp_photo;

                    if (file_exists($delete)) { unlink($delete); }
                }

                // Update
                $update = MerchantFinance::where('merchant_id', $merchant_id)->update([
                    'npwp_photo' => $item->npwp_photo,
                ]);
            }
        }

        //DB::commit();

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }


    // Approve Store
    public function store()
    {
        // Initialization
        $pageTitle = 'Informasi Toko';
        $page = 'merchant.store';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function dataStore()
    {
        // Initialization
        $items = array();
        $pageTitle = 'Informasi Toko';
        $page = 'merchant.store';

        // Lists
        $lists = MerchantStoreRequest::orderBy('updated_at', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            // Status
            $status = null;
            switch ($item->status) {
                case 1:
                    $status = '<div class="badge badge-success">Disetujui</div>';
                    break;

                case 2:
                    $status = '<div class="badge badge-danger">Ditolak</div>';
                    break;

                default:
                    $status = '<div class="badge badge-warning">Menunggu</div>';
                    break;
            }

            // Content
            $content = $item->status_content;
            if (empty($content)) {
                $content = '-';
            }
            
            // Array
            $items[] = array(
                'merchant' => $item->merchant->name,
                'status' => $status,
                'content' => $content,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function editStore(Request $request)
    {
        // Initialization
        $id = $request->id;
        $pageTitle = 'Informasi Toko';
        $page = 'merchant.store';
        
        // Check
        $item = MerchantStoreRequest::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'item' => $item,
        ]);
    }
    public function updateStore(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $status = $request->status;
        $status_content = $request->status_content;
        $pageTitle = 'Informasi Toko';
        $page = 'merchant.store';
        
        // Check
        $item = MerchantStoreRequest::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $user_id = $item->user_id;
        $merchant_id = $item->merchant_id;

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = MerchantStoreRequest::where('id', $id)->update([
            'status' => $status,
            'status_content' => $status_content,
        ]);

        // Approve
        if ($status == 1)
        {
            // Merchant Update
            $update = Merchant::where('user_id', $user_id)->update([
                'type_id' => $item->type_id,
                'name' => $item->name,
                'category_id' => $item->category_id,
                'additional_id' => $item->additional_id,
            ]);
            
            // Merchant Address Update
            $update = MerchantAddress::where('id', $merchant_id)->update([
                'address' => $item->address,
                'provinsi_id' => $item->provinsi_id,
                'kabupaten_id' => $item->kabupaten_id,
                'kecamatan_id' => $item->kecamatan_id,
                'desa_id' => $item->desa_id,
                'postal_code' => $item->postal_code,
            ]);

            // Update User
            $update = User::where('id', $user_id)->update([
                'name' => $item->name,
            ]);
        }

        //DB::commit();

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }
}
