<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;
use File;

use Marketplace\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Kategori';
        $page = 'category';

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
        $pageTitle = 'Kategori';
        $page = 'category';

        // Lists
        $lists = Category::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {   
            // Highlight
            $highlight = null;
            if ($item->highlight == 1)
            {
                $highlight = '<span class="ml-2 badge badge-success">Highlight</span>';
            }

            // Name
            $name = $item->name.$highlight;
            if (!empty($item->parent))
            {
                $name = $item->parent->name.' > '.$item->name.$highlight;
            }

            // Array
            $items[] = array(
                'name' => $name,
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
        $pageTitle = 'Kategori';
        $page = 'category';

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
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=70,height=70',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=195,height=420',
            'background' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=1680,height=520',
            'highlight' => 'required|integer',
        ]);

        // Initialization
        $name = $request->name;
        $parent_id = $request->parent_id;
        $highlight = $request->highlight;
        $pageTitle = 'Kategori';
        $page = 'category';

        // Transaction Insert
        //DB::beginTransaction();

        // Icon
        if (!empty($request->icon))
        {
            $directory = 'categories';

            // Upload New Icon
            $icon = md5('icon'.Auth::user()->id.$request->name.$request->icon->getClientOriginalName()).'.'.$request->icon->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$icon;

            $request->icon->move($public.'uploads/'.$directory.'/', $icon);

            // Resize Icon
			$resize = Image::make($path)->fit(70, 70);
            $resize->save($public.'uploads/'.$directory.'/'.$icon);
        }

        // Cover
        if (!empty($request->cover))
        {
            $directory = 'categories';

            // Upload New Cover
            $cover = md5('cover'.Auth::user()->id.$request->name.$request->cover->getClientOriginalName()).'.'.$request->cover->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$cover;

            $request->cover->move($public.'uploads/'.$directory.'/', $cover);

            // Resize Cover
			$resize = Image::make($path)->fit(195, 420);
            $resize->save($public.'uploads/'.$directory.'/'.$cover);
        }

        // Background
        if (!empty($request->background))
        {
            $directory = 'categories';

            // Upload New Background
            $background = md5('background'.Auth::user()->id.$request->name.$request->background->getClientOriginalName()).'.'.$request->background->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$background;

            $request->background->move($public.'uploads/'.$directory.'/', $background);

            // Resize Background
			$resize = Image::make($path)->fit(1680, 520);
            $resize->save($public.'uploads/'.$directory.'/'.$background);
        }

        // Insert
        $insert = new Category;
        $insert->user_id = Auth::user()->id;
        $insert->parent_id = $parent_id;
        $insert->name = $name;
        $insert->icon = $icon;
        $insert->cover = $cover;
        $insert->background = $background;
        $insert->highlight = $highlight;
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
        $directory = 'categories';
        $pageTitle = 'Kategori';
        $page = 'category';
        
        // Check
        $item = Category::where('id', $id)
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
            'highlight' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $parent_id = $request->parent_id;
        $highlight = $request->highlight;
        $pageTitle = 'Kategori';
        $page = 'category';
        
        // Check
        $item = Category::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        $icon = $item->icon;
        $cover = $item->cover;
        $background = $item->background;

        // Transaction Update
        //DB::beginTransaction();

        // Icon
        if (!empty($request->icon))
        {
            $directory = 'categories';

            // Validation Icon
            $validated = $request->validate([
                'icon' => 'image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=70,height=70'
            ]);

            // Delete Old Icon
            if (!empty($icon))
            {
                $delete = $public.'uploads/'.$directory.'/'.$item->icon;

                if(file_exists($delete)) { unlink($delete); }
            }

            // Upload New Icon
            $icon = md5('icon'.Auth::user()->id.$request->name.$request->icon->getClientOriginalName()).'.'.$request->icon->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$icon;

            $request->icon->move($public.'uploads/'.$directory.'/', $icon);

            // Resize Icon
			$resize = Image::make($path)->fit(70, 70);
            $resize->save($public.'uploads/'.$directory.'/'.$icon);
        }
        
        // Cover
        if (!empty($request->cover))
        {
            $directory = 'categories';

            // Validation Cover
            $validated = $request->validate([
                'cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=195,height=420'
            ]);

            // Delete Old Cover
            if (!empty($cover))
            {
                $delete = $public.'uploads/'.$directory.'/'.$item->cover;

                if(file_exists($delete)) { unlink($delete); }
            }

            // Upload New Cover
            $cover = md5('cover'.Auth::user()->id.$request->name.$request->cover->getClientOriginalName()).'.'.$request->cover->getClientOriginalExtension();
            $path = $public.'uploads/'.$directory.'/'.$cover;

            $request->cover->move($public.'uploads/'.$directory.'/', $cover);

            // Resize Cover
			$resize = Image::make($path)->fit(195, 420);
            $resize->save($public.'uploads/'.$directory.'/'.$cover);
        }

        // Background
        if (!empty($request->background))
        {
            $directory = 'categories';

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
        $update = Category::where('id', $id)->update([
            'name' => $name,
            'slug' => str_slug($name),
            'parent_id' => $parent_id,
            'highlight' => $highlight,
            'icon' => $icon,
            'cover' => $cover,
            'background' => $background,
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
        $pageTitle = 'Kategori';
        $page = 'category';
        
        // Check
        $item = Category::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        if ($item->id == 12)
        {
            return redirect()->route('admin.'.$page)
                ->with('status', 'Maaf, anda tidak dapat menghapus Kategori E-Voucher');
        }

        // Transaction Delete
        //DB::beginTransaction();

        // Delete Icon
        if (!empty($item->icon))
        {
            $directory = 'categories';

            $delete = $public.'uploads/'.$directory.'/'.$item->icon;

            if(file_exists($delete)) { unlink($delete); }
        }

        // Delete Cover
        if (!empty($item->cover))
        {
            $directory = 'categories';

            $delete = $public.'uploads/'.$directory.'/'.$item->cover;

            if(file_exists($delete)) { unlink($delete); }
        }

        // Delete Background
        if (!empty($item->background))
        {
            $directory = 'categories';

            $delete = $public.'uploads/'.$directory.'/'.$item->background;

            if(file_exists($delete)) { unlink($delete); }
        }
        
        // Delete
        $item = Category::where('id', $id)->delete();

        //DB::commit();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }
}
