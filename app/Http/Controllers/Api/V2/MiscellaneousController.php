<?php

namespace Marketplace\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use Marketplace\Category;
use Marketplace\Footer;
use Marketplace\Http\Controllers\Controller;

/**
 * @group Miscellaneous List
 *
 * API untuk mengakses beraneka macam list.
 * 
 */

class MiscellaneousController extends Controller
{
    /**
	 * Category List
	 * Menampilkan semua kategori produk.
	 * 
     * @responseFile responses/category-list.get.json
	 */
    public function categoryList()
    {
        $query = Category::with('child')->get();

        if (!$query) {
            return response()->api(404, 'List kategori tidak ditemukan');
        }

        $query->makeHidden('user_id');
        $category = [];
        foreach ($query as $key => $item) {
            if ($item->parent_id === null) {
                $item->background = public_path('uploads/categories'.$item->background);
                $item->cover = public_path('uploads/categories'.$item->cover);
                $item->icon = public_path('uploads/categories'.$item->icon);
                array_push($category, $item);
            }
        }

        return response()->api(200, 'Data berhasil ditampilkan', $category);
    }

    /**
	 * Footer List
	 * Menampilkan semua data footer berupa link.
	 * 
     * @responseFile responses/footer-list.get.json
	 */
    public function footerList()
    {
        $footer = Footer::where('position_id', 1)->with('page')->get();

        if (!$footer) {
            return response()->api(404, 'List kategori tidak ditemukan');
        }

        foreach ($footer as $key => $item) {
            $links = [];
            switch ($item->id) {
                case '1':
                    $first = ['name' => 'Beriklan Sekarang', 'url'  => route('ads.request')];
                    $second = ['name' => 'MSP Forum', 'url'  => 'http://forum.mymspmall.id'];
                    array_push($links, $first);
                    array_push($links, $second);
                    break;
                case '3':
                    $first = ['name' => 'Menjadi Merchant', 'url'  => route('merchant.join')];
                    array_push($links, $first);
                    break;
                case '4':
                    $first = ['name' => 'Reset Password', 'url'  => route('password.request')];
                    array_push($links, $first);
                    break;
            }
            foreach ($item->page as $key => $val) {
                $link = [
                    'name' => $val->name,
                    'url'  => url('page/'.$val->slug)
                ];
                array_push($links, $link);
            }
            $item->links = $links;
        }
        $footer->makeHidden('page');

        return response()->api(200, 'Data berhasil ditampilkan', $footer);
    }
}
