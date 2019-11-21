<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Auth;
use Validator;

use Marketplace\Page;
use Marketplace\Footer;

use Marketplace\ShippingWaybill;
use Marketplace\ShippingStatus;
use Marketplace\ShippingManifest;
use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\User;
use Marketplace\Option;

class ShippingController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Pengiriman';
        $page = 'shipping';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function data()
    {
        // Initialization
        $items = array();
        $pageTitle = 'Waybill';
        $page = 'shipping';

        // Lists
        $lists = ShippingWaybill::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'name' => $item->name,
                'status' => $item->status->name,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function dataManifest(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Manifest';
        $page = 'manifest';
        $id = $request->id;

        // Lists
        $lists = ShippingManifest::where('waybill_id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item)
        {
            $items[] = array(
                'code' => $item->id,
                'description' => $item->description,
                'city' => $item->kabupaten->name,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.delete', ['id' => $item->id]).'"><button class="btn btn-danger ks-split"><span class="la la-trash ks-icon"></span><span class="ks-text">Hapus '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }
    public function createManifest(Request $request)
    {
        // Initialization
        $id = $request->id;
        $pageTitle = 'Manifest';
        $page = 'manifest';
        
        // Check
        $item = ShippingWaybill::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Lists
		$places = Kabupaten::orderBy('province_id', 'asc')
            ->get();

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'item' => $item,
            'places' => $places,
        ]);
    }
    public function storeManifest(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'description' => 'required|max:255',
            'kabupaten_id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $description = $request->description;
        $kabupaten_id = $request->kabupaten_id;

        $pageTitle = 'Manifest';
        $page = 'shipping';

        // Check
        $item = ShippingWaybill::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Insert
        //DB::beginTransaction();

        // Insert
        $insert = new ShippingManifest;
        $insert->user_id = Auth::user()->id;
        $insert->waybill_id = $id;
        $insert->description = $description;
        $insert->kabupaten_id = $kabupaten_id;
        $insert->save();

        //DB::commit();
        
        // Return Redirect Insert Success
        return redirect()->route('admin.'.$page.'.edit', ['id' => $id])
            ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
    }
    public function deleteManifest(Request $request)
    {
        // Initialization
        $id = $request->id;
        $pageTitle = 'Manifest';
        $page = 'shipping';
        
        // Check
        $item = ShippingManifest::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Delete
        //DB::beginTransaction();
        
        // Delete
        $delete = ShippingManifest::where('id', $id)->delete();

        //DB::commit();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page.'.edit', ['id' => $item->waybill_id])
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }

    public function create(Request $request)
    {
        // Initialization
        $pageTitle = 'Waybill';
        $page = 'shipping';
        $shipper_name = null;
        $shipper_address = null;

        // Check
        $shipping_username = Option::where('type', 'shipping-username')
            ->first();
        $shipper_name = $shipping_username->content;

        $shipper_address = Option::where('type', 'shipping-address')
            ->first();
        $shipper_address = $shipper_address->content;

        $check_user = User::where('username', $shipper_name)
            ->first();
        if (!empty($check_user))
        {
            $shipper_name = $check_user->name;
        }

        // Lists
        $dataProvinsi = Provinsi::orderBy('name', 'asc')
            ->get();

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'dataProvinsi' => $dataProvinsi,
            'shipper_name' => $shipper_name,
            'shipper_address' => $shipper_address,
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'shipper_name' => 'required|max:255',
            'shipper_address' => 'required|max:255',
            'name' => 'required|max:255',
            'provinsi_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'postal_code' => 'required|integer',
            'address' => 'required',
            'transaction' => 'required|integer',
        ]);

        // Initialization
        $shipper_name = $request->shipper_name;
        $shipper_address = $request->shipper_address;
        $name = $request->name;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $postal_code = $request->postal_code;
        $address = $request->address;
        $transaction = $request->transaction;
        $distance = null;
        $price = null;

        $pageTitle = 'Waybill';
        $page = 'shipping';

        // Shipping
        $shipping = new \Marketplace\Http\Controllers\Shipping\PricingController;
					
        $shipping = $shipping->json(
            $kabupaten_id,
            $kecamatan_id,
            $postal_code,
            $transaction
        );

        if (!empty($shipping['items']))
        {
            foreach ($shipping['items']['results'] as $result) {
                foreach ($result['costs'] as $ongkir) {
                    $distance = $ongkir['distance'];
                    $price = $ongkir['value'];
                }
            }
        }

        if (empty($shipping['items']))
        {
            // Maximum
            $shipping_maximum = Option::where('type', 'shipping-maximum')
                ->first();
            $shipping_maximum = $shipping_maximum->content;

            // Return Redirect Insert Success
            return redirect()->route('admin.'.$page.'.create')
                ->with('warning', 'Maaf, Jarak Tujuan Pengiriman melebihi Batas Maksimal '.$shipping_maximum.' km');
        }

        // Transaction Insert
        //DB::beginTransaction();

        // Insert
        $insert = new ShippingWaybill;
        $insert->user_id = Auth::user()->id;
        $insert->shipper_name = $shipper_name;
        $insert->shipper_address = $shipper_address;
        $insert->name = $name;
        $insert->provinsi_id = $provinsi_id;
        $insert->kabupaten_id = $kabupaten_id;
        $insert->kecamatan_id = $kecamatan_id;
        $insert->postal_code = $postal_code;
        $insert->address = $address;
        $insert->transaction = $transaction;
        $insert->distance = $distance;
        $insert->price = $price;
        $insert->save();

        //DB::commit();
        
        // Return Redirect Insert Success
        return redirect()->route('admin.'.$page)
            ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
    }

    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'shippings';
        $pageTitle = 'Waybill';
        $page = 'shipping';
        $pageSubTitle = 'Manifest';
        $subpage = 'manifest';
        
        // Check
        $item = ShippingWaybill::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Lists
        $dataProvinsi = Provinsi::orderBy('name', 'ASC')
            ->get();
        
        $status = ShippingStatus::orderBy('id', 'DESC')
            ->get();
    
        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'pageSubTitle' => $pageSubTitle,
            'subpage' => $subpage,
            'directory' => $directory,
            'item' => $item,
            'dataProvinsi' => $dataProvinsi,
            'status' => $status,
        ]);
    }
    public function update(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'status_id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $status_id = $request->status_id;
        $pageTitle = 'Waybill';
        $page = 'shipping';
        
        // Check
        $item = ShippingWaybill::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = ShippingWaybill::where('id', $id)->update([
            'status_id' => $status_id,
        ]);

        //DB::commit();

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page.'.edit', ['id' => $id])
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }
    public function delete(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $pageTitle = 'Waybill';
        $page = 'shipping';
        
        // Check
        $item = ShippingWaybill::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Delete
        //DB::beginTransaction();
        
        // Delete
        $item = ShippingWaybill::where('id', $id)->delete();

        //DB::commit();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }
}
