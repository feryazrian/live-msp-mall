<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use Marketplace\BalanceWithdraw;

class WithdrawController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Status Penarikan';
        $page = 'withdraw';

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
        $pageTitle = 'Status Penarikan';
        $page = 'withdraw';

        // Lists
        $lists = BalanceWithdraw::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $status = null;
            switch ($item->status) {
                case 1:
                    $status = '<div class="badge badge-success">Disetujui</div>';
                    $action = '<button class="btn btn-primary ks-split disabled"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button>';
                    break;
                case 2:
                    $status = '<div class="badge badge-danger">Ditolak</div>';
                    $action = '<button class="btn btn-primary ks-split disabled" ><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button>';
                    break;
                default:
                    $status = '<div class="badge badge-warning">Menunggu</div>';
                    $action = '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>';
                    break;
            }

            $items[] = array(
                'name' => $item->user->name,
                'status' => $status,
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'action' => $action,
            );
        }

        // Return Array
        return array('aaData' => $items);
    }


    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'withdraw';
        $pageTitle = 'Status Penarikan';
        $page = 'withdraw';
        
        // Check
        $item = BalanceWithdraw::where('id', $id)
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
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);
        // Initialization
        $id = $request->id;
        $status = $request->status;
        $pageTitle = 'Status Penarikan';
        $page = 'withdraw';
        
        // Check
        $item = BalanceWithdraw::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = BalanceWithdraw::where('id', $id)->update([
            'status' => $status,
        ]);

        //DB::commit();

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }
}
