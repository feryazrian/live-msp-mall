<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;
use File;

use Marketplace\AdsRequest;

class AdsController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Pesan Pengiklan';
        $page = 'ads';

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
        $pageTitle = 'Pesan Pengiklan';
        $page = 'ads';

        // Lists
        $lists = AdsRequest::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'name' => $item->name,
                'position' => $item->position->name,
                'created' => $item->created_at->format('Y-m-d H:i:s'),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-outline-primary ks-split"><span class="la la-envelope ks-icon"></span><span class="ks-text">Detail '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }


    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'ads';
        $pageTitle = 'Pesan Pengiklan';
        $page = 'ads';
        
        // Check
        $item = AdsRequest::where('id', $id)
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
}
