<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;
use File;

use Marketplace\Option;

class OptionController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Pengaturan';
        $page = 'option';

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
        $pageTitle = 'Pengaturan';
        $page = 'option';

        // Lists
        $lists = Option::orderBy('id', 'DESC')
            ->where('id','!=',42)
            ->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'name' => $item->name,
                'format' => $item->format,
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }


    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'options';
        $pageTitle = 'Pengaturan';
        $page = 'option';
        
        // Check
        $item = Option::where('id', $id)
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
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $content = null;
        $pageTitle = 'Pengaturan';
        $page = 'option';
        
        // Check
        $item = Option::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        //DB::beginTransaction();

        // Format Photo
        if ($item->format == 'photo')
        {
	        if (!empty($request->photo))
	        {
	        	$directory = 'options';

                // Delete Old Photo
                if (!empty($item->content))
                {
                    $delete = $public.'uploads/'.$directory.'/'.$item->content;

                    if(file_exists($delete)) { unlink($delete); }
                }
                
	            // Upload New Photo
	            $photo = md5(Auth::user()->id.$request->name.$request->photo->getClientOriginalName()).'.'.$request->photo->getClientOriginalExtension();
	            $path = $public.'uploads/'.$directory.'/'.$photo;

	            $request->photo->move($public.'uploads/'.$directory.'/', $photo);

	            $content = $photo;
	        }
        }

        // Format Date
        if ($item->format == 'datetime')
        {
            $content = $request->content;
        }

        // Format Text
        if ($item->format == 'text')
        {
            $content = $request->content;
        }

        // Format Boolean
        if ($item->format == 'boolean')
        {
            $content = $request->content;
        }

        // Validation
        if ($item->type == 'max-point')
        {
            if ($content > 100)
            {
                return redirect()
                    ->route('admin.'.$page.'.edit', ['id' => $item->id])
                    ->with('warning', $item->name.' adalah 100');
            }
        }
        
        // Update
        $update = Option::where('id', $id)->update([
            'name' => $name,
            'content' => $content,
        ]);

        //DB::commit();

        // Return Redirect Update Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
    }
}
