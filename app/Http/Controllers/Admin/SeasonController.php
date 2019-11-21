<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Auth;
use Image;
use Validator;
use File;

use Marketplace\Season;
use Marketplace\SeasonProduct;
use Marketplace\Product;

class SeasonController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Promo Musiman';
        $page = 'season';

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
        $pageTitle = 'Promo Musiman';
        $page = 'season';

        // Lists
        $lists = Season::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {   
            // Array
            $items[] = array(
                'name' => $item->name,
                'expired' => $item->expired,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split mr-2"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah Promo</span></button></a><a href="'.route('admin.'.$page.'.product', ['id' => $item->id]).'"><button class="btn btn-info ks-split"><span class="la la-check-circle ks-icon"></span><span class="ks-text">Kelola Produk</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }


    public function create(Request $request)
    {
        // Initialization
        $pageTitle = 'Promo Musiman';
        $page = 'season';

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'background' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=1680,height=520',
            'expired' => 'required|date',
        ]);

        // Initialization
        $name = $request->name;
        $expired = $request->expired;
        $pageTitle = 'Promo Musiman';
        $page = 'season';

        // Upload Background
        if (!empty($request->background))
        {
            $directory = 'seasons';

            // Upload New Background
            $background = md5('background'.Auth::user()->id.$request->name.$request->background->getClientOriginalName()).'.'.$request->background->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$background;

            $request->background->move($public.'uploads/'.$directory.'/', $background);

            // Resize Background
			$resize = Image::make($path)->fit(1680, 520);
            $resize->save($public.'uploads/'.$directory.'/'.$background);
        }

        // Insert
        $insert = new Season;
        $insert->user_id = Auth::user()->id;
        $insert->name = $name;
        $insert->background = $background;
        $insert->expired = $expired;
        $insert->save();
        
        // Return Redirect Insert Success
        return redirect()->route('admin.'.$page)
            ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
    }

    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'seasons';
        $pageTitle = 'Promo Musiman';
        $page = 'season';
        
        // Check
        $item = Season::where('id', $id)
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
    public function update(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|max:255',
            'expired' => 'required|date',
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $expired = $request->expired;
        $pageTitle = 'Promo Musiman';
        $page = 'season';
        
        // Check
        $item = Season::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $background = $item->background;

        // Update Background
        if (!empty($request->background))
        {
            $directory = 'seasons';

            // Validation Background
            $validated = $request->validate([
                'background' => 'image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=1680,height=520'
            ]);

            // Delete Old Background
            if (!empty($background))
            {
                $delete = $public.'uploads/'.$directory.'/'.$item->background;

                if(file_exists($delete)) { unlink($delete); }
            }

            // Upload New Background
            $background = md5('background'.Auth::user()->id.$request->name.$request->background->getClientOriginalName()).'.'.$request->background->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$background;

            $request->background->move($public.'uploads/'.$directory.'/', $background);

            // Resize Background
			$resize = Image::make($path)->fit(1680, 520);
            $resize->save($public.'uploads/'.$directory.'/'.$background);
        }
        
        // Update
        $update = Season::where('id', $id)->update([
            'name' => $name,
            'background' => $background,
            'expired' => $expired,
        ]);

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }
    public function delete(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $pageTitle = 'Promo Musiman';
        $page = 'season';
        
        // Check
        $item = Season::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Delete Background
        if (!empty($item->background))
        {
            $directory = 'seasons';

            $delete = $public.'uploads/'.$directory.'/'.$item->background;

            if(file_exists($delete)) { unlink($delete); }
        }
        
        // Delete
        $item = Season::where('id', $id)->delete();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }

    // Season Product
    public function product(Request $request)
    {
        // Initialization
        $id = $request->id;
        $pageTitle = 'Daftar Produk';
        $page = 'season.product';
        
        // Check
        $item = Season::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $pageTitle = 'Daftar Produk - '.$item->name;

        // Return View
        return view('admin.'.$page)->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'item' => $item,
        ]);
    }
    public function dataProduct(Request $request)
    {
        // Initialization
        $id = $request->id;
        $pageTitle = 'Daftar Produk';
        $page = 'season.product';

        // Check
        $season = Season::where('id', $id)
            ->first();

        if (empty($season))
        {
            return redirect('/');
        }

        $pageTitle = 'Daftar Produk - '.$season->name;

        // Columns
        $columns = array( 
            0 => 'name', 
            1 => 'status',
            2 => 'created_at',
            3 => 'updated_at',
            4 => 'action',
        );

        // Input
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // Ordering
        if ($order == 'action' OR $order == 'status') {
            $order = 'updated_at';
        }

        // List Count
        $totalData = Product::where('status', 1)
            ->count();

        $totalFiltered = $totalData; 

        // Lists
        $lists = Product::where('status', 1)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $lists = Product::where('status', 1)
                ->where('name', 'like', '%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            
            $totalFiltered = Product::where('status', 1)
                ->where('name', 'like', '%'.$search.'%')
                ->count();
        }

        // Lists Array
        $data = array();

        if (!empty($lists))
        {
            foreach ($lists as $item) 
            {   
                $action = '<form method="post" action="'.route('admin.'.$page.'.store').'"><input type="hidden" name="season_id" value="'.$season->id.'" /><input type="hidden" name="product_id" value="'.$item->id.'" />'.csrf_field().'<button type="submit" class="btn btn-success ks-split"><span class="la la-plus ks-icon"></span><span class="ks-text">Tambah Produk</span></button></form>';

                $status = '<div class="badge badge-warning">Tidak Aktif</div>';

                // Check
                $check = SeasonProduct::where('season_id', $season->id)
                    ->where('product_id', $item->id)
                    ->first();

                if (!empty($check)) {
                    $action = '<form method="post" action="'.route('admin.'.$page.'.delete').'"><input type="hidden" name="season_id" value="'.$season->id.'" /><input type="hidden" name="product_id" value="'.$item->id.'" />'.csrf_field().'<button type="submit" class="btn btn-danger ks-split"><span class="la la-trash ks-icon"></span><span class="ks-text">Hapus Produk</span></button></form>';

                    $status = '<div class="badge badge-success">Aktif</div>';
                }
                    
                // Data
                $nestedData['name'] = $item->name;
                $nestedData['status'] = $status;
                $nestedData['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                $nestedData['updated_at'] = $item->updated_at->format('Y-m-d H:i:s');
                $nestedData['action'] = $action;

                $data[] = $nestedData;
            }
        }

        // Data Json
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );

        // Return Json
        echo json_encode($json_data);
    }
    public function storeProduct(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'season_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        // Initialization
        $season_id = $request->season_id;
        $product_id = $request->product_id;
        $pageTitle = 'Produk Promo Musiman';
        $page = 'season.product';
        
        // Check
        $check = SeasonProduct::where('season_id', $season_id)
            ->where('product_id', $product_id)
            ->first();

        if (!empty($check))
        {
            return redirect()->route('admin.'.$page, ['id' => $season_id]);
        }

        // Insert
        $insert = new SeasonProduct;
        $insert->user_id = Auth::user()->id;
        $insert->season_id = $season_id;
        $insert->product_id = $product_id;
        $insert->save();

        // Return Redirect Insert Success
        return redirect()->route('admin.'.$page, ['id' => $season_id])
            ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
    }
    public function deleteProduct(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'season_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        // Initialization
        $season_id = $request->season_id;
        $product_id = $request->product_id;
        $pageTitle = 'Produk Promo Musiman';
        $page = 'season.product';
        
        // Check
        $check = SeasonProduct::where('season_id', $season_id)
            ->where('product_id', $product_id)
            ->first();

        if (empty($check))
        {
            return redirect('/');
        }

        // Delete
        $item = SeasonProduct::where('season_id', $season_id)
            ->where('product_id', $product_id)
            ->delete();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page, ['id' => $season_id])
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }
}
