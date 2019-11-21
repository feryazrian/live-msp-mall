<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;
use File;
use Carbon\Carbon;
use Marketplace\BalanceWithdraw;
use Marketplace\Product;
use Marketplace\User;
use Marketplace\Category;
use Marketplace\Merchant;
use Marketplace\Slide;
use Marketplace\Page;
use Marketplace\Option;

class DashboardController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Dashboard';
        $page = 'dashboard';

        // Count Total
        $productTotal = Product::where('status', 1)
            ->count();

        $userTotal = User::count();

        $categoryTotal = Category::count();
        $slideTotal = Slide::where('status', 1)->count();
        $pageTotal = Page::count();
        $optionTotal = Option::count();

        // Count Today
        $productToday = Product::where('status', 1)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $userToday = User::whereDate('created_at', Carbon::today())
            ->count();

        // Return View
        return view('admin.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,

            'productTotal' => $productTotal,
            'userTotal' => $userTotal,
            'categoryTotal' => $categoryTotal,
            'slideTotal' => $slideTotal,
            'pageTotal' => $pageTotal,
            'optionTotal' => $optionTotal,

            'productToday' => $productToday,
            'userToday' => $userToday,
        ]);
    }

    public function getBadges(Request $request)
    {
        $withdraw = BalanceWithdraw::where('status', 0)->count();
        $product = Product::where('status', 0)->count();
        $merchant = Merchant::where('status', 3)->count();

        $data = [
            'withdraw'      => $withdraw,
            'product'       => $product,
            'merchant'      => $merchant
        ];
        return response()->api(200, 'Fetching data success', $data);
    }
}
