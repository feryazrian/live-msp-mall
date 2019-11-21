<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Slide;
use Marketplace\User;
use Marketplace\UserAuth;
use Marketplace\Product;
use Marketplace\Category;
use Marketplace\Season;

use Auth;
use Validator;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function vue() {
        $dir = '/assets/vue';
        $vue = public_path($dir);
        $css = $vue.'/css';
        $js = $vue.'/js';
        $jsApp = '';
        $cssApp = '';
        $jsChunk = '';
        $cssChunk = '';
        $APP = 'app.';
        $CHUNK = 'chunk-vendors.';
        foreach(\File::files($js) as $v) {
            if($v->getExtension() == 'js') {
                $name = $v->getFilename();
                if(strpos($name, $APP) === 0) {
                    $jsApp = $dir.'/js/'.$name;
                } else
                if(strpos($name, $CHUNK) === 0)
                    $jsChunk = $dir.'/js/'.$name;
            }
        }
        foreach(\File::files($css) as $v) {
            if($v->getExtension() == 'css') {
                $name = $v->getFilename();
                if(strpos($name, $APP) === 0) {
                    $cssApp = $dir.'/css/'.$name;
                } else
                if(strpos($name, $CHUNK) === 0)
                    $cssChunk = $dir.'/css/'.$name;
            }
        }
        return view('vue')->with([
            'jsApp' => $jsApp,
            'cssApp' => $cssApp,
            'jsChunkVendors' => $jsChunk,
            'cssChunkVendors' => $cssChunk
        ]);
    }

	public function index()
	{
		// Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 1)
			->inRandomOrder()
            ->get();
        
        $productSale = Product::where('status', 1)
            ->where('sale', 1)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();
            
        $productSold = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->orWhere('rating', '>', 2)
            ->orderBy('sold', 'DESC')
            // ->inRandomOrder()
            ->limit(6)
			->get();
            
		$productNew = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'DESC')
            // ->inRandomOrder()
            ->limit(6)
			->get();
            
		$productPreorder = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->where('preorder', 1)
            ->where('preorder_expired', '>', Carbon::now()->format('Y-m-d H:i:s'))
			->orderBy('created_at', 'DESC')
            ->limit(6)
			->get();
            
		$categoryHighlight = Category::where('highlight', 1)
			->orderBy('id', 'ASC')
            ->get();
            
		$seasons = Season::where('expired', '>', Carbon::now()->format('Y-m-d H:i:s'))
			->orderBy('expired', 'ASC')
            ->get();

        // Now
        $now = Carbon::now();

        // Return View
	    return view('index')->with([
			'slides' => $slides,
			'now' => $now,
			'productSold' => $productSold,
			'productNew' => $productNew,
			'productSale' => $productSale,
			'productPreorder' => $productPreorder,
			'categoryHighlight' => $categoryHighlight,
            'seasons' => $seasons,
        ]);
	}

    public function logout(Request $request)
    {
		// User Logout Log
        if (!empty(Auth::user()->id))
        {
            $userAuth = new UserAuth;
            $userAuth->type = 'logout';
            $userAuth->user_id = Auth::user()->id;
            $userAuth->user_ip = $request->ip();
            $userAuth->user_agent = $request->server('HTTP_USER_AGENT');
            $userAuth->save();
        }

		// Logout
        Auth::logout();

        $request->session()->flush();
        
        $request->session()->regenerate();

		// Return Redirect
        return redirect('/');
    }

    public function verify(Request $request)
    {
        $token = str_slug($request->token);

        $user = User::where('email_token', $token)
            ->first();

        if (empty($user)) {
            return redirect('/');
        }
        
        $userUpdate = User::where('email_token', $token)
            ->update([
                'activated' => 1
        ]);
        
        return redirect('login')->with([
            'status' => 'Selamat! Akun Anda telah berhasil di Aktivasi.',
        ]);
    }
}
