<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use Marketplace\LoyaltyMember;

class LoyaltyController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Loyalty Member';
        $page = 'loyalty';

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
        $pageTitle = 'Loyalty Member';
        $page = 'loyalty';

        // Lists
        $lists = LoyaltyMember::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'name' => $item->name,
                'point' => $item->point.' Point',
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
        $pageTitle = 'Loyalty Member';
        $page = 'loyalty';

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
            'point' => 'required|numeric',
        ]);

        // Initialization
        $name = $request->name;
        $point = $request->point;
        $pageTitle = 'Loyalty Member';
        $page = 'loyalty';

        // Transaction Insert
        //DB::beginTransaction();

        // Insert
        $insert = new LoyaltyMember;
        $insert->user_id = Auth::user()->id;
        $insert->name = $name;
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
        $pageTitle = 'Loyalty Member';
        $page = 'loyalty';
        
        // Check
        $item = LoyaltyMember::where('id', $id)
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
            'point' => 'required|numeric',
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $point = $request->point;
        $pageTitle = 'Loyalty Member';
        $page = 'loyalty';
        
        // Check
        $item = LoyaltyMember::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = LoyaltyMember::where('id', $id)->update([
            'name' => $name,
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
        $pageTitle = 'Loyalty Member';
        $page = 'loyalty';
        
        // Check
        $item = LoyaltyMember::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Delete
        //DB::beginTransaction();
        
        // Delete
        $item = LoyaltyMember::where('id', $id)->delete();

        //DB::commit();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }
}
