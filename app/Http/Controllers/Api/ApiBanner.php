<?php
namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;
use Marketplace\Banner;

class ApiBanner extends Controller
{
    public function get_all_banner(Request $request)
    {
        $banner = DB::table('banners')
                    ->select('*')
                    ->get();
        if ($banner== null)
        {
            $responses = array(
                'status_code' => 203,
                'status_message' => 'Not Found',
                'status' => false,
                'items' => $banner,
            );
        }
        else {
       
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'status' => true,
                'items' => $banner,
            );
        }
        return response()->json($responses, $responses['status_code']);
    }

    public function getBannerById($id,Request $request)
    {
        $banner = DB::table('banners')
                    ->select('*')
                    ->where('id',$id)
                    ->first();

        if ($banner== null)
        {
            $responses = array(
                'status_code' => 203,
                'status_message' => 'Not Found',
                'status' => false,
                'items' => $banner,
            );
        }
        else {
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'status' => true,
                'items' => $banner,
            );
        }
        return response()->json($responses, $responses['status_code']);           

    }
    

    public function add_banner(Request $request) {
        DB::beginTransaction();
        try {

            $insert = new Banner();
            $insert->title = $request->title;
            $insert->description = $request->description;
            $insert->image_path = $request->image_path;
            $insert->link = $request->link;
            $insert->flag = $request->flag;
            $insert->publish_date = $request->publish_date;
            $insert->end_date = $request->end_date;
            $insert->created_at = $request->created_at;
            $insert->updated_at = $request->updated_at;
            $insert->deleted_at = $request->deleted_at;
            $insert->save();
            $id = $insert->id;

            $photo = $request->file('image_path');
            if ($photo) {
                $photo_path = $photo->storeAs('Banner', $id."_photo.".$photo->getClientOriginalExtension() , 'public');
                
                $insert = Banner::where('id', $id)->first();
                $insert->image_path = $photo_path;

                $insert->save();
            }

            DB::commit();

            $responses = array(
                'status_code' => 200,
                'status_message' => 'Banner registered successfully',
                'status' => true,
                'items' => $insert,
            );
        }

        catch(Exception $error) {
            DB::rollback();
            $responses = array(
                'status_code' => 203,
                'status_message' => $error->getMessage(),
                'status' => false,
                'items' => $insert,
            );
        }
        return response()->json($responses, $responses['status_code']);  
    }

    public function delete_banner(Request $request) {
        DB::beginTransaction();
        try{
            $banner_id = $request->banner_id;

            
            $banner = DB::table('banners')
                        ->where('id',$banner_id)
                        ->first();

            if($banner!=null){
                DB::table('banners')
                    ->where('id',$banner_id)
                    ->delete();

                DB::commit();
                $responses = array(
                    'status_code' => 200,
                    'status_message' => 'record successfully deleted',
                    'status' => true,
                    'items' => $banner,
                );
            }
            else{
                DB::rollback();
                $responses = array(
                    'status_code' => 203,
                    'status_message' => 'Record tidak ditemukan',
                    'status' => false,
                    'items' => $banner,
                );

            }
        }
        catch(Exception $error) {
            DB::rollback();
            $responses = array(
                'status_code' => 203,
                'status_message' => $error->getMessage(),
                'status' => false,
                'items' => $banner,
            );

        }
        return response()->json($responses, $responses['status_code']);  
    } 

    public function update_banner(Request $request)
    {
        $status = true;
        $message="";
        $data = null;
        DB::beginTransaction();
        try {
            $banner_id = $request->banner_id;
            $banner = DB::table('banners')
                ->where('id',$banner_id)
                ->first();
             $photo = $request->file('image_path');
            if ($photo) {
                echo $photo;
                $photo_path = $photo->storeAs('Banner', $banner_id."_photo.".$photo->getClientOriginalExtension() , 'public');
            }

            DB::table('banners')
            ->where('id', $banner_id)
            ->update(
                [ 
                    'title' => $request->title,
                    'description'=> $request->description,
                    'image_path'=> $photo_path,
                    'link'=> $request->link,
                    'flag'=> $request->flag,
                    'publish_date'=> $request->publish_date,
                    'end_date'=> $request->end_date,
                    'created_at'=> $request->created_at,
                    'updated_at'=> $request->updated_at
                ]
            );

            DB::commit();
            $responses = array(
                'status_code' => 200,
                'status_message' => 'Updated Successfully',
                'status' => true,
                'items' => $banner,
            );
        }
        catch(Exception $error)
        {
            DB::rollback();
            $responses = array(
                'status_code' => 203,
                'status_message' => $error->getMessage(),
                'status' => false,
                'items' => $banner,
            );
    
        }
        return response()->json(['status'=>$status,'message'=>$message],200);
    }

}
