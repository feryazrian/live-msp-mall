<?php

namespace Marketplace\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Marketplace\Slide;
use Marketplace\Banner;

/**
 * @group Banners
 *
 * API untuk menampilkan list banner.
 * 
 */

class BannerController extends Controller
{
    /**
	 * Banner List
	 * Menampilkan semua banner list untuk ditampilkan di halaman utama / beranda.
	 *
	 * 
	 * @responseFile responses/banner.get.json
	 */
    public function list(Request $request){
        // Lists
		$banner = Slide::where('status', 1)
            ->where('position_id', 1)
            ->inRandomOrder()
            ->select('id', 'position_id', 'name', 'photo', 'url')
            ->get();
        $items = [
            'list' => $banner,
            'image_path' => url('uploads/slides/')
        ];

        return response()->api(200, 'Data berhasil ditampilkan', $items);
    }
    /**
	 * Banner Digital
	 * Menampilkan semua banner digital untuk ditampilkan di bagian iklan digital.
	 *
	 * 
	 * @responseFile responses/bannerdigital.get.json
	 */
    public function digital(Request $request){
        // Digital
        $lists = Banner::where("flag", 1)->where("publish_date", "<=", now())->where("end_date", ">", now())->get();
                
        // Return Array
        return response()->api(200, 'Data berhasil ditampilkan', $lists);
    }
}
