<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use Marketplace\Page;
use Marketplace\Footer;

class PageController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Halaman';
        $page = 'page';

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
        $pageTitle = 'Halaman';
        $page = 'page';

        // Lists
        $lists = Page::orderBy('id', 'DESC')
            ->get();

        foreach ($lists as $item) 
        {
            $position = '-';

            if (!empty($item->footer))
            {
                $position = $item->footer->position->name.' - '.$item->footer->name;
            }

            $items[] = array(
                'name' => $item->name,
                'position' => $position,
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
        $pageTitle = 'Halaman';
        $page = 'page';

        // Lists
        $footers = Footer::orderBy('name','ASC')
            ->get();

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'footers' => $footers,
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'content' => 'required',
        ]);

        // Initialization
        $name = $request->name;
        $content = $request->content;
        $footerId = null;
        $pageTitle = 'Halaman';
        $page = 'page';

        // Footer
        if (!empty($request->footer))
        {
            $footerId = $request->footer;
        }

        // Transaction Insert
        //DB::beginTransaction();

        // Insert
        $insert = new Page;
        $insert->user_id = Auth::user()->id;
        $insert->footer_id = $footerId;
        $insert->name = $name;
        $insert->content = $content;
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
        $directory = 'pages';
        $pageTitle = 'Halaman';
        $page = 'page';
        
        // Check
        $item = Page::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Lists
        $footers = Footer::orderBy('name','ASC')
            ->get();

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,
            'item' => $item,
            'footers' => $footers,
        ]);
    }
    public function update(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|max:255',
            'content' => 'required',
        ]);

        // Initialization
        $id = $request->id;
        $name = $request->name;
        $footerId = null;
        $content = $request->content;
        $pageTitle = 'Halaman';
        $page = 'page';

        // Footer
        if (!empty($request->footer))
        {
            $footerId = $request->footer;
        }
        
        // Check
        $item = Page::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Update
        //DB::beginTransaction();
        
        // Update
        $update = Page::where('id', $id)->update([
            'name' => $name,
            'footer_id' => $footerId,
            'content' => $content,
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
        $pageTitle = 'Halaman';
        $page = 'page';
        
        // Check
        $item = Page::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Delete
        //DB::beginTransaction();
        
        // Delete
        $item = Page::where('id', $id)->delete();

        //DB::commit();

        // Return Redirect Delete Success
        return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');
    }
}
