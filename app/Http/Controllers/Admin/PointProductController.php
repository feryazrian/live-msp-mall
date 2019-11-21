<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use Marketplace\PointProduct;

class PointProductController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Point Produk';
        $page = 'point.product';

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
        $pageTitle = 'Point Produk';
        $page = 'point.product';

        // Lists
        $lists = PointProduct::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'name' => $item->name,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }


    public function create(Request $request)
    {
        // Initialization
        $pageTitle = 'Point Produk';
        $page = 'point.product';

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'point' => 'required|integer',
        ]);

        // Initialization
        $name = $request->name;
        $price = $request->price;
        $point = $request->point;
        $pageTitle = 'Point Produk';
        $page = 'point.product';

        // Transaction Insert
        //DB::beginTransaction();

        // Insert
        $insert = new PointProduct;
        $insert->user_id = Auth::user()->id;
        $insert->name = $name;
        $insert->price = $price;
        $insert->point = $point;
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
        $pageTitle = 'Point Produk';
        $page = 'point.product';
        
        // Check
        $item = PointProduct::where('id', $id)
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
    public function update(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'point' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $price = $request->price;
        $point = $request->point;
        $pageTitle = 'Point Produk';
        $page = 'point.product';
        
        // Check
        $item = PointProduct::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = PointProduct::where('id', $id)->update([
            'name' => $name,
            'price' => $price,
            'point' => $point,
        ]);

        //DB::commit();

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
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
        $pageTitle = 'Point Produk';
        $page = 'point.product';
        
        // Check
        $item = PointProduct::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Delete
        //DB::beginTransaction();
        
        // Delete
        $item = PointProduct::where('id', $id)->delete();

        //DB::commit();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }
}
