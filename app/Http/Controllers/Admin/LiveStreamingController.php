<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use DB;
use Marketplace\Live;

class LiveStreamingController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Live Streaming';
        $page = 'streaming';

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
        $pageTitle = 'Live Streaming';
        $page = 'streaming';

        // Lists
        $lists = Live::orderBy('start_time', 'DESC')->get();

        foreach ($lists as $item) 
        {
            $items[] = array(
                'title' => $item->title,
                'episode' => $item->episode,
                'start_time' => $item->start_time,
                'status' => $item->show === 1 ? '<span class="badge badge-success">Tayang</span>' : '<span class="badge badge-secondary">Tidak Tayang</span>',
                'url' => '<a href="'.$item->url.'">'.$item->url.'</a>',
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
        $pageTitle = 'Live Streaming';
        $page = 'streaming';

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
            'title' => 'required|max:255',
            'episode' => 'required|max:255',
            'url' => 'required|max:255',
            'show' => 'required',
            'description' => 'required',
            'start_time' => 'required',
        ]);

        // Initialization
        $pageTitle = 'Live Streaming';
        $page = 'streaming';

        // Transaction
        DB::beginTransaction();
        try {
            // Insert data
            $insert = new Live;
            $insert->start_time = $request->start_time;
            $insert->end_time = $request->end_time;
            $insert->url = $request->url;
            $insert->title = $request->title;
            $insert->episode = $request->episode;
            $insert->description = $request->description;
            $insert->show = $request->show;
            $insert->save();

            // Return Redirect and commit transaction when Insert Success
            DB::commit();
            return redirect()->route('admin.'.$page.'.live')
                ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
        } catch (\Exception $e) {
            // Return redirect back and rollback transaction when insert failed
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }

    public function edit($id)
    {
        // Initialization
        $pageTitle = 'Live Streaming';
        $page = 'streaming';
        
        // Check
        $item = Live::where('id', $id)->first();

        if (empty($item))
        {
            redirect()->back()->with('warning', 'Oops!!, Data tidak ditemukan');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'item' => $item,
        ]);
    }

    public function update($id, Request $request)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|max:255',
            'episode' => 'required|max:255',
            'url' => 'required|max:255',
            'show' => 'required',
            'description' => 'required',
            'start_time' => 'required',
        ]);

        // Initialization
        $pageTitle = 'Live Streaming';
        $page = 'streaming';

        // Transaction
        DB::beginTransaction();
        try {
            // Update data
            $update = Live::find($id);
            $update->start_time = $request->start_time;
            $update->end_time = $request->end_time;
            $update->url = $request->url;
            $update->title = $request->title;
            $update->episode = $request->episode;
            $update->description = $request->description;
            $update->show = $request->show;
            $update->save();

            // Return Redirect and commit transaction when update Success
            DB::commit();
            return redirect()->route('admin.'.$page.'.live')
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
        } catch (\Exception $e) {
            // Return redirect back and rollback transaction when update failed
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }
}
