<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use Marketplace\User;
use Marketplace\UserType;
use Marketplace\Http\Controllers\BalanceController;
use Marketplace\Http\Controllers\LifePointController;

class UserController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Daftar Pengguna';
        $page = 'user';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function data(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Daftar Pengguna';
        $page = 'user';

        // Columns
        $columns = array( 
            0 => 'name', 
            1 => 'name', 
            2 => 'email',
            3 => 'activated',
            4 => 'mons_wallet',
            5 => 'life_point',
            6 => 'created_at',
            7 => 'action',
        );

        // Input
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // Ordering
        if ($order == 'action' || $order == 'mons_wallet' || $order == 'life_point') {
            $order = 'created_at';
        }

        // List Count
        $totalData = User::count();

        $totalFiltered = $totalData; 

        // Lists
        $lists = User::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $lists = User::where('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->orWhere('id', 'like', '%'.$search.'%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            
            $totalFiltered = User::where('name', 'like', '%'.$search.'%')
                ->count();
        }

        // Lists Array
        $data = array();

        if (!empty($lists))
        {
            foreach ($lists as $item) 
            {
                // Activated
                $activated = null;

                switch ($item->activated) {
                    case 1:
                        $activated = '<div class="badge badge-success">Sudah Aktif</div>';
                        break;
                    case 2:
                        $activated = '<div class="badge badge-danger">Terblokir</div>';
                        break;
                    default:
                        $activated = '<div class="badge badge-warning">Belum Aktif</div>';
                        break;
                }

                $balance = new BalanceController;
                $userBalance = $balance->myBalanceByUserId($item->id);
                $point = new LifePointController;
                $userPoint = $point->get_life_point($item);
                // Data
                $nestedData['id'] = $item->id;
                $nestedData['name'] = $item->name.' @'.$item->username;
                $nestedData['email'] = $item->email;
                $nestedData['activated'] = $activated;
                $nestedData['mons_wallet'] = $userBalance;
                $nestedData['life_point'] = $userPoint;
                $nestedData['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                $nestedData['action'] = '<a href="'.route('admin.'.$page.'.block', ['id' => $item->id]).'"><button class="btn btn-danger ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Blokir</span></button></a>';

                $data[] = $nestedData;
            }
        }
        $collect = collect($data);
        if ($columns[$request->input('order.0.column')] == 'mons_wallet') {
            switch ($dir) {
                case 'asc':
                    $collect->sortBy('mons_wallet');
                    break;
                default:
                    $collect->sortByDesc('mons_wallet');
                    break;
            }
        }

        if ($columns[$request->input('order.0.column')] == 'life_point') {
            switch ($dir) {
                case 'asc':
                    $collect->sortBy('life_point');
                    break;
                default:
                    $collect->sortByDesc('life_point');
                    break;
            }
        }

        // Data Json
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $collect   
        );

        // Return Json
        echo json_encode($json_data);
    }


    public function type()
    {
        // Initialization
        $pageTitle = 'Jenis Pengguna';
        $page = 'user.type';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }
    public function dataType()
    {
        // Initialization
        $items = array();
        $pageTitle = 'Jenis Pengguna';
        $page = 'user.type';

        // Lists
        $lists = UserType::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'name' => $item->name,
                'percent' => $item->percent.' %',
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function editType(Request $request)
    {
        // Initialization
        $id = $request->id;
        $pageTitle = 'Jenis Pengguna';
        $page = 'user.type';
        
        // Check
        $item = UserType::where('id', $id)
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
    public function updateType(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|max:255',
            'content' => 'required',
            'percent' => 'required|numeric',
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $percent = $request->percent;
        $content = $request->content;
        
        $pageTitle = 'Jenis Pengguna';
        $page = 'user.type';
        
        // Check
        $item = UserType::where('id', $id)
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
            $update = UserType::where('id', $id)->update([
                'name' => $name,
                'percent' => $percent,
                'content' => $content,
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

    public function block($id)
    {
        // Validation
        $page = 'user';
        $user = User::find($id);

        if (!$user)
        {
            return redirect()->back();
        }

        // Transaction Update
        DB::beginTransaction();
        try
        {
            // Update
            $update = User::where('id', $id)
                ->update([
                    'activated' => 2,
                ]);
            if ($update != 1) {
                return redirect()->back()->with('warning', 'User gagal diblokir');
            }
            // Return Redirect Update Success
            DB::commit();
            return redirect()->route('admin.'.$page)->with('status', 'User berhasil diblokir');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with('warning', 'User gagal diblokir. Err: '.$e->getMessage());
        }
    }
}
