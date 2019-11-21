<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Marketplace\Http\Controllers\Controller;

use Auth;
use Image;
use Validator;
use File;
use DB;
use Marketplace\PpobTransaction;
use Marketplace\Banner;
use Marketplace\Promo;
use Marketplace\Promo_Banner;
use Marketplace\PpobType;


class BannerController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Banner';
        $page = 'banner';

        // Return View
        // return "hir";
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function data()
    {
        // Initialization
        $items = array();
        $pageTitle = 'Banner';
        $page = 'banner';
        // return "hir";

        // Lists
        $lists = Banner::orderBy('id', 'DESC')
            ->get(); 
      
        foreach ($lists as $item) 
        {
            $items[] = array(
                'id'=> $item->id,
                'title' => $item->title,
                'description'=> $item->description,
                'link'=> $item->link,
                'flag' => $item->flag === 1 ? '<span class="badge badge-success">Tayang</span>' : '<span class="badge badge-secondary">Tidak Tayang</span>',
                'end_date'=> $item->end_date,
                'slug'=> $item->slug,
                'publish_date'=> $item->publish_date,
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split mr-2"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function create(Request $request)
    {
        // Initialization
        $pageTitle = 'Banner';
        $page = 'banner';
        $id = $request->id;
        $promo = Promo::orderBy('id', 'ASC') 
                ->where('type_id','=','3')
                ->get();
        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'promo'=>$promo,
        ]);

    }

    public function store(Request $request)
    {
        // dd($request);
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            // 'link' => 'required|max:255',
            'flag' => 'required|max:255',
            // 'slug' => 'required|max:255',
            'publish_date' => 'required|date',
            'end_date' => 'required|date',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Initialization
        $id = $request->id;
        $title = $request->title;
        $description = $request->description;
        $link = ($request->link)?$request->link:"";
        $flag = $request->flag;
        $slug = $request->slug;
        $publish_date = $request->publish_date;
        $end_date = $request->end_date;
        $created_at = $request->created_at;
        $updated_at= $request->updated_at;
        $pageTitle = 'Banner';
        $page = 'banner';

         // Transaction
         DB::beginTransaction();
         try {
            $path = "";
            // Background
            if (!empty($request->image_path))
            {
                $directory = 'Banner';
    
                // Upload New Background
                $background = md5('image_path'.Auth::user()->id.$request->name.$request->image_path->getClientOriginalName()).'.'.$request->image_path->getClientOriginalExtension();
                $path ='uploads/'.$directory.'/'.$background;
    
                $request->image_path->move($public.'uploads/'.$directory.'/', $background);
    
                // Resize Background
                // $resize = Image::make($path)->fit(1680, 520);
                $resize = Image::make($path);
                $resize->save($public.'uploads/'.$directory.'/'.$background);
            }
            // Insert
            $insert = new Banner;
            $insert->title = $title;
            $insert->image_path = $path;
            $insert->slug = $slug;
            $insert->description = $description;
            $insert->link = $link;
            $insert->flag = $flag;
            $insert->publish_date = $publish_date;
            $insert->end_date = $end_date;    
            $insert->save();

            if($request->has('promo')){
                $promo = $request->promo;
                foreach($promo as $p){  
                    $arr = new Promo_Banner;
                    $arr->promo_id = $p;
                    $arr->banner_id= $insert->id;
                    $arr->save();
                }
            }
            
            DB::commit();
            // Return Redirect Insert Success
            return redirect()->route('admin.'.$page)
            ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }



    public function edit(Request $request)
    {
        // dd($request);
        // Initialization
        $id = $request->id;
        $directory = 'Banner';
        $pageTitle = 'Banner';
        $page = 'banner';

        // Check
        $item = Banner::where('id', $id)
            ->first();
          
        $promo_type = Promo::orderBy('id', 'ASC')
            ->where('type_id','=','3')
            ->get();
        $promo_type_check = Promo_Banner::orderBy('id', 'ASC')
            ->where('banner_id','=',$id)
            ->select("promo_id")
            ->get();
        // dd($id,$item,$promo_type,$promo_type_check);

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'item' => $item,

            'directory' => $directory,
            'promo_type' => $promo_type,
            'promo_type_check' => $promo_type_check,
        ]);
    }

    public function update(Request $request)
    {
        // dd($request);
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            // 'title' => 'required|max:255',
            // 'description' => 'required|max:255',
            'image_path' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Initialization
        $id = $request->id;
        $title = $request->title;
        $slug = $request->slug;
        $description = $request->description;
        $link = $request->link;
        $flag = $request->flag;
        $publish_date = $request->publish_date;
        $end_date = $request->end_date;
        $pageTitle = 'Banner Update';
        $page = 'banner';
       
         // Transaction
         DB::beginTransaction();
         try {
              // Check
            $item = Banner::where('id', $id)
                ->first();

            if (empty($item))
            {
                return redirect('/');
            }

            $image_path = $item->image_path;


            if (!empty($request->image_path))
            {

                $directory = 'Banner';

                // Validation Background
                $validated = $request->validate([
                    'image_path' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);

                // Delete Old Background
                if (!empty($image_path))
                {

                    // $delete = $public.'uploads/'.$item->image_path;
                    $delete = $public.$item->image_path;


                    if(file_exists($delete)) { 
                        unlink($delete);                   
                        // dd($image_path,$request->image_path,$delete);
                    }
                }

                // Upload New Background
                $background = md5('Banner'.$item->id.$request->image_path->getClientOriginalName()).'.'.$request->image_path->getClientOriginalExtension();
                $path = 'uploads/'.$directory.'/'.$background;
                $image_path = $path;
                // dd($image_path);

                $request->image_path->move($public.'uploads/'.$directory.'/', $background);

                // Resize Background
                // $resize = Image::make($path)->fit(1680, 520);
                $resize = Image::make($path);
                $resize->save($public.'uploads/'.$directory.'/'.$background);
            }

              // Update
            $update = Banner::where('id', $id)->update([
                'title' =>  $title,
                'slug' =>  $slug,
                'description' =>  $description,
                'link' =>  $link,
                'image_path' =>  $image_path,
                'flag' => $flag,
                'publish_date' =>$publish_date,
                'end_date' => $end_date ,
            ]);

            if($request->has('promo')){
                $item = Promo_Banner::where('banner_id', $id)->delete();
                $promo_type = $request->promo;
                foreach($promo_type as $p){
                    $arr = new Promo_Banner;
                    $arr->promo_id = $p;
                    $arr->banner_id= $id;
                    $arr->save();
                }
            }
            DB::commit();
            // Return Redirect Insert Success
            return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }    
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
        $pageTitle = 'Banner';
        $page = 'banner';
        
        // Delete
        DB::beginTransaction();
        try {
             // Check
            $item = Banner::where('id', $id)
            ->first();

            if (empty($item))
            {
                return redirect('/');
            }


            // Delete Background
            if (!empty($item->image_path))
            {
                $directory = 'Banner';

                $delete = $public.'uploads/'.$directory.'/'.$item->image_path;

                if(file_exists($delete)) { unlink($delete); }
            }
            $item = Promo_Banner::where('banner_id', $id)->delete();
            $items = Banner::where('id', $id)->delete();
            DB::commit();
            // Return Redirect Delete Success
            return redirect()->route('admin.'.$page)
                ->with('status', 'Hapus '.$pageTitle.' telah berhasil');

        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }    
    }
}
