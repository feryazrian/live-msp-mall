<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;
use File;

use Marketplace\Slide;
use Marketplace\SlidePosition;

class SlideController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Slide';
        $page = 'slide';

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
        $pageTitle = 'Slide';
        $page = 'slide';

        // Lists
        $lists = Slide::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'name' => $item->name,
                'position' => $item->position->name,
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
        $pageTitle = 'Slide';
        $page = 'slide';

        // Lists
        $positions = SlidePosition::orderBy('name','ASC')
            ->get();

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'positions' => $positions,
        ]);
    }

    public function store(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Check
        $position = SlidePosition::where('id', $request->position)
            ->first();

        if (empty($position))
        {
            return redirect('/');
        }

        $height = $position->height;
        $width = $position->width;

        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'position' => 'required|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'required|max:255',
        ]);

        // Initialization
        $name = $request->name;
        $url = $request->url;
        $positionId = $request->position;
        $photo = null;
        $pageTitle = 'Slide';
        $page = 'slide';

        // Transaction Insert
        //DB::beginTransaction();

        if (!empty($request->photo))
        {
            $directory = 'slides';

            // Upload New Photo
            $photo = md5(Auth::user()->id.$request->name.$request->photo->getClientOriginalName()).'.'.$request->photo->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$photo;

            $request->photo->move($public.'uploads/'.$directory.'/', $photo);

            // Resize Photo
			$resize = Image::make($path)->fit($width, $height);
            $resize->save($public.'uploads/'.$directory.'/'.$photo);
        }

        // Insert
        $insert = new Slide;
        $insert->user_id = Auth::user()->id;
        $insert->position_id = $positionId;
        $insert->name = $name;
        $insert->photo = $photo;
        $insert->url = $url;
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
        $directory = 'slides';
        $pageTitle = 'Slide';
        $page = 'slide';
        
        // Check
        $item = Slide::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Lists
        $positions = SlidePosition::orderBy('name','ASC')
            ->get();

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,
            'item' => $item,
            'positions' => $positions,
        ]);
    }
    public function update(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
        
        // Check
        $position = SlidePosition::where('id', $request->position)
            ->first();

        if (empty($position))
        {
            return redirect('/');
        }

        $height = $position->height;
        $width = $position->width;

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|max:255',
            'position' => 'required|integer',
            'url' => 'required|max:255',
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $positionId = $request->position;
        $url = $request->url;
        $pageTitle = 'Slide';
        $page = 'slide';
        
        // Check
        $item = Slide::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $photo = $item->photo;

        // Transaction Update
        //DB::beginTransaction();

        if (!empty($request->photo))
        {
            $directory = 'slides';

            // Validation Photo
            $validated = $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Delete Old Photo
            if (!empty($photo))
            {
                $delete = $public.'uploads/'.$directory.'/'.$item->photo;

                if(file_exists($delete)) { unlink($delete); }
            }

            // Upload New Photo
            $photo = md5(Auth::user()->id.$request->name.$request->photo->getClientOriginalName()).'.'.$request->photo->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$photo;

            $request->photo->move($public.'uploads/'.$directory.'/', $photo);

            // Resize Photo
			$resize = Image::make($path)->fit($width, $height);
            $resize->save($public.'uploads/'.$directory.'/'.$photo);
        }
        
        // Update
        $update = Slide::where('id', $id)->update([
            'name' => $name,
            'position_id' => $positionId,
            'url' => $url,
            'photo' => $photo,
        ]);

        //DB::commit();

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
        $pageTitle = 'Slide';
        $page = 'slide';
        
        // Check
        $item = Slide::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Delete
        //DB::beginTransaction();

        // Delete Photo
        if (!empty($item->photo))
        {
            $directory = 'slides';

            $delete = $public.'uploads/'.$directory.'/'.$item->photo;

            if(file_exists($delete)) { unlink($delete); }
        }
        
        // Delete
        $item = Slide::where('id', $id)->delete();

        //DB::commit();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }
}
