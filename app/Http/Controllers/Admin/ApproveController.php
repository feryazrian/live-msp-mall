<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;

use Marketplace\Product;
use Marketplace\ProductAction;
use Marketplace\ProductPhoto;
use Marketplace\Merchant;
use Marketplace\User;

class ApproveController extends Controller
{
    // Approve Product
    public function product()
    {
        // Initialization
        $pageTitle = 'Status Produk';
        $page = 'approve.product';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function dataProduct(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Status Produk';
        $page = 'approve.product';

        // Columns
        $columns = array( 
            0 => 'name', 
            1 => 'type_id',
            2 => 'action_id',
            3 => 'status',
            4 => 'created_at',
            5 => 'updated_at',
            6 => 'action',
        );

        // Input
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // Ordering
        if ($order == 'action') {
            $order = 'created_at';
        }

        // List Count
        $totalData = Product::count();

        $totalFiltered = $totalData; 

        // Lists
        $lists = Product::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $lists = Product::where('name', 'like', '%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            
            $totalFiltered = Product::where('name', 'like', '%'.$search.'%')
                ->count();
        }

        // Lists Array
        $data = array();

        if (!empty($lists))
        {
            foreach ($lists as $item) 
            {
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
                
                $nestedData['name'] = $item->name;
                $nestedData['type_id'] = $item->type->name;
                $nestedData['action_id'] = $item->action->name;
                $nestedData['status'] = $status;
                $nestedData['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                $nestedData['updated_at'] = $item->updated_at->format('Y-m-d H:i:s');
                $nestedData['action'] = '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>';

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

    public function editProduct(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'products';
        $pageTitle = 'Status Produk';
        $page = 'approve.product';
        
        // Check
        $item = Product::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }
		
		// Price & Discount
		$price = $item->price;
		$discount = $item->discount;

		if (!empty($item->discount))
		{
			$item->discount = $price;
			$item->price = $discount;
        }
        
        // List
        $actions = ProductAction::orderBy('id', 'ASC')
            ->get();

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,
            'item' => $item,
            'actions' => $actions,
        ]);
    }
    public function updateProduct(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer',
            'action' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $status = $request->status;
        $action = $request->action;
        $action_content = $request->action_content;
        $pageTitle = 'Status Produk';
        $page = 'approve.product';
        
        // Check
        $item = Product::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        DB::beginTransaction();
        try
        {
            // Update
            $update = Product::where('id', $id)->update([
                'status' => $status,
                'action_id' => $action,
                'action_content' => $action_content,
            ]);
                
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollback();
        }

        // Delete
        if ($status == 1 AND $action == 3)
        {
            // Path
            $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
            
            // Transaction Delete
            DB::beginTransaction();
            try
            {
                // Check
                $product = Product::where('id', $id)
                    ->first();

                if (empty($product)) {
                    return redirect('/');
                }

                $productId = $product->id;

                $productPhoto = ProductPhoto::where('product_id', $productId)
                    ->get();

                // Unlick
                foreach ($productPhoto as $photo) {
                    $fileName = $photo->photo;

                    $fileDelete = $public.'uploads/products/'.$fileName;
                    $fileDeleteLarge = $public.'uploads/products/large-'.$fileName;
                    $fileDeleteMedium = $public.'uploads/products/medium-'.$fileName;
                    $fileDeleteSmall = $public.'uploads/products/small-'.$fileName;

                    if (file_exists($fileDelete)) { unlink($fileDelete); }
                    if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
                    if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
                    if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }
                }

                // Delete
                $productPhotoDelete = ProductPhoto::where('product_id', $productId)
                    ->delete();

                $productDelete = Product::where('id', $productId)
                    ->delete();
            
                DB::commit();
            }
            catch (\Exception $e)
            {
                DB::rollback();
            }

            // Return Redirect Update Success
            return redirect()->route('admin.'.$page)
                ->with('status', 'Selamat!! Produk telah berhasil dihapus');

        }

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }


    // Approve Sale
    public function sale()
    {
        // Initialization
        $pageTitle = 'Status Flash Sale';
        $page = 'approve.sale';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function dataSale(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Status Flash Sale';
        $page = 'approve.sale';

        // Columns
        $columns = array( 
            0 => 'name', 
            1 => 'type_id',
            2 => 'sale',
            3 => 'created_at',
            4 => 'updated_at',
            5 => 'action',
        );

        // Input
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // Ordering
        if ($order == 'action') {
            $order = 'created_at';
        }

        // List Count
        $totalData = Product::whereNotNull('discount')
            ->count();

        $totalFiltered = $totalData; 

        // Lists
        $lists = Product::whereNotNull('discount')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $lists = Product::whereNotNull('discount')
                ->where('name', 'like', '%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            
            $totalFiltered = Product::whereNotNull('discount')
                ->where('name', 'like', '%'.$search.'%')
                ->count();
        }

        // Lists Array
        $data = array();

        if (!empty($lists))
        {
            foreach ($lists as $item) 
            {
                $status = null;
                switch ($item->sale) {
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
                
                // Data
                $nestedData['name'] = $item->name;
                $nestedData['type_id'] = $item->type->name;
                $nestedData['sale'] = $status;
                $nestedData['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                $nestedData['updated_at'] = $item->updated_at->format('Y-m-d H:i:s');
                $nestedData['action'] = '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>';

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

    public function editSale(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'products';
        $pageTitle = 'Status Flash Sale';
        $page = 'approve.sale';
        
        // Check
        $item = Product::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }
		
		// Price & Discount
		$price = $item->price;
		$discount = $item->discount;

		if (!empty($item->discount))
		{
			$item->discount = $price;
			$item->price = $discount;
		}

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,
            'item' => $item,
        ]);
    }
    public function updateSale(Request $request)
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
        $sale = $request->status;
        $pageTitle = 'Status Flash Sale';
        $page = 'approve.sale';
        
        // Check
        $item = Product::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $status = 0;

        if ($sale == 1)
        {
            $status = 1;
        }

        // Transaction Update
        DB::beginTransaction();
        try
        {
            // Update
            $update = Product::where('id', $id)->update([
                'sale' => $sale,
                'status' => $status,
            ]);
                
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollback();
        }

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }


    // Approve Merchant
    public function merchant()
    {
        // Initialization
        $pageTitle = 'Status Merchant';
        $page = 'approve.merchant';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function dataMerchant(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Status Merchant';
        $page = 'approve.merchant';

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
        if ($order == 'action') {
            $order = 'created_at';
        }

        // List Count
        $totalData = Merchant::where('status', '>', 0)
            ->count();

        $totalFiltered = $totalData; 

        // Lists
        $lists = Merchant::where('status', '>', 0)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $lists = Merchant::where('status', '>', 0)
                ->where('name', 'like', '%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            
            $totalFiltered = Merchant::where('status', '>', 0)
                ->where('name', 'like', '%'.$search.'%')
                ->count();
        }

        // Lists Array
        $data = array();

        if (!empty($lists))
        {
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

                    case 3:
                        $status = '<div class="badge badge-warning">Menunggu</div>';
                        break;
                }

                // Data
                $nestedData['name'] = $item->name;
                $nestedData['status'] = $status;
                $nestedData['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                $nestedData['updated_at'] = $item->updated_at->format('Y-m-d H:i:s');
                $nestedData['action'] = '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>';

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

    public function editMerchant(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'merchants';
        $pageTitle = 'Status Merchant';
        $page = 'approve.merchant';
        
        // Check
        $item = Merchant::where('id', $id)
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
    public function updateMerchant(Request $request)
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
        $pageTitle = 'Status Merchant';
        $page = 'approve.merchant';

        // Check
        $item = Merchant::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        DB::beginTransaction();
        try
        {
            // Update User
            if ($status == 1)
            {
                $update = User::where('id', $item->user_id)->update([
                    'merchant_id' => $item->id,
                ]);
            }
            
            if ($status != 1)
            {
                $update = User::where('id', $item->user_id)->update([
                    'merchant_id' => null,
                ]);
                Product::where('user_id', $item->user_id)->update([
                    'status'    => $status
                ]);
            }

            // Update Merchant
            $update = Merchant::where('id', $id)->update([
                'status' => $status,
            ]);
                
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollback();
        }

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }
}
