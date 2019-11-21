<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\AdsPosition;
use Marketplace\AdsRequest;

class AdsController extends Controller
{
	public function request()
	{
        // Initialization
        $pageTitle = 'Beriklan Sekarang';

		// Lists
		$lists = AdsPosition::orderBy('name', 'asc')
			->get();
		
		// Return View
		return view('ads.request')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'lists' => $lists,
        ]);
    }
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric',
            'position_id' => 'required|integer',
            'content' => 'required',
        ]);

        // Initialization
        $pageTitle = 'Beriklan Sekarang';
        $user_id = $request->user_id;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $position_id = $request->position_id;
        $content = $request->content;

        // Check
        $check = AdsPosition::where('id', $position_id)
            ->first();
        
        if (empty($check))
        {
            return redirect()
                ->route('ads.request');
        }

        // Transaction
        //DB::beginTransaction();
    
        // Insert
        $insert = new AdsRequest;
        $insert->user_id = $user_id;
        $insert->name = $name;
        $insert->email = $email;
        $insert->phone = $phone;
        $insert->position_id = $position_id;
        $insert->content = $content;
        $insert->save();

        //DB::commit();

		// Return View
		return view('ads.complete')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
        ]);
    }
}
