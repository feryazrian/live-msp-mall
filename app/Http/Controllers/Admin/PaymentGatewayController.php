<?php

namespace Marketplace\Http\Controllers\Admin;

use DB;
use Image;
use Validator;
use Illuminate\Http\Request;
// use Illuminate\Validation\Validator;

use Marketplace\Http\Controllers\Controller;
use Marketplace\TransactionGateway;

class PaymentGatewayController extends Controller
{
    private $pageTitle;
    private $page;
    private $directory;
    private $publicPath;

    public function __construct()
    {
        $this->pageTitle = 'Payment Method';
        $this->page = 'payment';
        $this->directory = 'payments';
        $this->publicPath = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
    }
 
    public function index()
    {
        // Return View
        return view('admin.'.$this->page.'.index')->with([
            'pageTitle' => $this->pageTitle,
            'page' => $this->page,
        ]);
    }

    public function data()
    {
        // Initialization
        $items = array();

        // Lists
        $lists = TransactionGateway::all();

        foreach ($lists as $item) 
        {
            $image_path = asset("assets/payments/$item->image_path"); 
            $items[] = array(
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'logo' => '<img src="'. $image_path . '" alt="'. $item->slug .'" style="max-width:100px;">',
                'status' => $item->status === 1 ? '<span class="badge badge-success">Show</span>' : '<span class="badge badge-secondary">Hidden</span>',
                'type' =>  $item->type === 0 ? ('<span class="badge badge-info">e-Channel</span>') : ($item->type === 2 ? ('<span class="badge badge-danger">Installment</span>') : ('<span class="badge badge-warning">Balance</span>')),
                'updated' => $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$this->page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$this->pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function create()
    {
        // Return View
        return view('admin.'.$this->page.'.create')->with([
            'pageTitle' => $this->pageTitle,
            'page' => $this->page,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'          => 'required',
            'status'        => 'required',
            'title'         => 'required',
            'slug'          => 'required',
            'name'          => 'required',
            'description'   => 'required',
            'image_path'    => 'required|image',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return redirect()->back()->with(['warning' => $message])->withInput();
        }

        DB::beginTransaction();
        try {
            $insert = new TransactionGateway;

            if ($request->image_path) {
                // Upload New Photo
                $image_name = md5($request->title.$request->image_path->getClientOriginalName()).'.'.$request->image_path->getClientOriginalExtension();
                $request->image_path->move($this->publicPath.'assets/'.$this->directory.'/', $image_name);
                $insert->image_path = $image_name;
            }
    
            // Insert
            $insert->type = $request->type;
            $insert->status = $request->status;
            $insert->title = $request->title;
            $insert->slug = $request->slug;
            $insert->name = $request->name;
            $insert->description = $request->description;
            $insert->save();

            // Return Redirect and commit transaction when Insert Success
            DB::commit();
            return redirect()->route('admin.'.$this->page.'.index')
                ->with('status', $this->pageTitle.' Baru telah berhasil diterbitkan');
        } catch (\Exception $e) {
            // Return redirect back and rollback transaction when insert failed
            DB::rollback();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e)->withInput();
        }
    }

    public function edit($id)
    {
        // Check
        $item = TransactionGateway::where('id', $id)->first();

        if (empty($item))
        {
            redirect()->back()->with('warning', 'Oops!!, Data tidak ditemukan');
        }

        // Return View
        return view('admin.'.$this->page.'.edit')->with([
            'pageTitle' => $this->pageTitle,
            'page' => $this->page,
            'item' => $item,
            'directory' => $this->directory
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type'          => 'required',
            'status'        => 'required',
            'title'         => 'required',
            'slug'          => 'required',
            'name'          => 'required',
            'description'   => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return redirect()->back()->with(['warning' => $message])->withInput();
        }

        DB::beginTransaction();
        try {
            $update = TransactionGateway::find($id);
            if ($request->image_path) {
                if ($update->image_path){
                    $delete = $this->publicPath.'assets/'.$this->directory.'/'.$update->image_path;
                    if(file_exists($delete)) { unlink($delete); }
                }
                // Upload New Photo
                $image_name = md5($request->title.$request->image_path->getClientOriginalName()).'.'.$request->image_path->getClientOriginalExtension();
                $request->image_path->move($this->publicPath.'assets/'.$this->directory.'/', $image_name);
                $update->image_path = $image_name;
            }
    
            // Update data
            $update->type = $request->type;
            $update->status = $request->status;
            $update->title = $request->title;
            $update->slug = $request->slug;
            $update->name = $request->name;
            $update->description = $request->description;
            $update->save();

            // Return Redirect and commit transaction when Insert Success
            DB::commit();
            return redirect()->route('admin.'.$this->page.'.index')
                ->with('status', 'Perubahan '.$this->pageTitle.' telah berhasil disimpan');
        } catch (\Exception $e) {
            // Return redirect back and rollback transaction when insert failed
            DB::rollback();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e)->withInput();
        }
    }

}
