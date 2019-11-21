<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Product;
use Marketplace\ProductPhoto;
use Marketplace\ProductComment;
use Marketplace\Wishlist;
use Marketplace\User;
use Marketplace\TransactionProduct;
use Marketplace\Option;
use Marketplace\Kabupaten;
use Marketplace\ProductReply;
use Marketplace\Notification;
use Marketplace\Merchant;
use Marketplace\VoucherTransaction;

use DB;
use Auth;
use Image;
use Carbon\Carbon;
use Validator;
use RajaOngkir;

class ProductController extends Controller
{
	public function detail(Request $request)
	{
		// Initliazation
        $pageTitle = 'Jual';
		$slug = str_slug($request->slug);
		$wishlist = null;
		$seo_image = null;

		// Check
		$product = Product::where('slug', $slug)
			->where('status', 1)
			->first();

		if (empty($product)) {
			return redirect('/');
		}

		if (Auth::check()) {
			$wishlist = Wishlist::where('product_id', $product->id)
				->where('user_id', Auth::user()->id)
				->first();
		}

		$pageTitle = $pageTitle.' '.$product->name.' oleh '.$product->user->name;
		$seo_image = asset('uploads/products/large-'.$product->productphoto[0]->photo);

		// Lists
		$sold = TransactionProduct::where('product_id', $product->id)
			->where('status', 5)
			->sum('unit');

		$reviews = TransactionProduct::where('product_id', $product->id)
			->where('status', 5)
			->get();

		$preorders = TransactionProduct::where('product_id', $product->id)
			->where('preorder', 1)
			->where('status', '>=', 1)
			->where('status', '<=', 5)
			->get()
			->count();
			
		$recomendations = Product::where('status', 1)
			->where('stock', '>', 0)
			->where('category_id', $product->category_id)
			->inRandomOrder()
			->limit(6)
			->get();

		$comments = ProductComment::where('product_id', $product->id)
			->orderBy('created_at', 'DESC')
			->get();

		$locations = Kabupaten::orderBy('province_id', 'asc')
			->get();
		$now =  Carbon::now();

		$max = 0;
		$sum_voucher_unit=0;
		$created_at='';
		$user='';

		if(!empty(Auth::user()->id)){
			$userId = Auth::user()->id; 
			$user =Auth::user();
			// if($user->activated == 2){
			// 	return redirect()->back()
			// 	->with('danger', 'Maaf akun anda kami blokir sementara karena terindikasi melakukan kecurangan. Jika anda tidak melakukan kecurangan, silahkan hubungi customer service MSP Mall.');
			// }
			
			$sum_voucher_unit = VoucherTransaction:: where('status', 1)
				->where('user_id', $userId)
				->where('product_id', $product->id)
				->whereDate('created_at', '=', Carbon::today()->toDateString())
				->sum('unit');
			$created_at = VoucherTransaction:: where('status', 1)
				->where('user_id', $userId)
				->where('product_id', $product->id)
				->whereDate('created_at', '=', Carbon::today()->toDateString())
				->get();
			if($product->stock <= $product->max_amount_per_days){
				$max = $product->stock;
			}
			else{
				if(!empty($sum_voucher_unit)){
					if($sum_voucher_unit <= $product->max_amount_per_days ){
						$max = $product->max_amount_per_days-$sum_voucher_unit;
					}
				}
				else{
					$max = $product->max_amount_per_days;
				}
			}
		}

		// if($bulan[0]->created_at->toDateString("%d-%b-%Y") == today()->addDays(-1)->toDateString("%d-%b-%Y") ){
		// 	dd("Sama");
		// }
		// else{
		// 	dd("Beda");
		// }
			
		// dd(
		// 	$now->toDateString("%d-%b-%Y"),
 		// 	$created_at,
		// 	$product,
		// 	$recomendations,
		// 	$userId,
		// 	"jumlah penjualan voucher hari ini = " .$sum_voucher_unit,
		// 	"max pembelian hari ini = ".$max,
		// 	"max amount per days = ".$product->max_amount_per_days
		// );

		// Return View
		return view('product.detail')->with([
			'pageTitle' => $pageTitle,
			'seo_image' => $seo_image,
			'images' => $product->productphoto[0]->photo,
			'product' => $product,
			'wishlist' => $wishlist,
			'sold' => $sold,
			'preorders' => $preorders,
			'reviews' => $reviews,
			'comments' => $comments,
			'recomendations' => $recomendations,
			'locations' => $locations,
			'sum_voucher_unit' =>$sum_voucher_unit,
			'created_at' => $created_at,
			'max' => $max,
			'user'=>$user
		]);
    }

	public function add(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

		// Initialization
		$type = $request->type;
		$userId = Auth::user()->id;
		
		// Clean Photo History
		//DB::beginTransaction();
		
		$productPhoto = ProductPhoto::whereNull('product_id')
			->where('user_id', $userId)
			->get();
		DB::beginTransaction();
		try {
			foreach ($productPhoto as $photo) {
				$fileName = $photo->photo;

				// Unlink
				$fileDelete = $public.'uploads/products/'.$fileName;
				$fileDeleteLarge = $public.'uploads/products/large-'.$fileName;
				$fileDeleteMedium = $public.'uploads/products/medium-'.$fileName;
				$fileDeleteSmall = $public.'uploads/products/small-'.$fileName;

				if (file_exists($fileDelete)) { unlink($fileDelete); }
				if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
				if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
				if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }
				
				// Delete
				$productPhotoDelete = ProductPhoto::where('photo', $fileName)
					->delete();
			}
			DB::commit();
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}

		// Type Product
		switch ($type) {
			case 2:
				// Initialization
				$pageTitle = 'Jual E-Voucher';
				
				// Return View
				return view('voucher.add', ['type' => 2])->with([
					'sidebar' => 'voucher-add',
					'headTitle' => true,
					'pageTitle' => $pageTitle,
				]);
				break;
			
			default:
				// Initialization
				$pageTitle = 'Jual Produk';
				
				// Return View
				return view('product.add', ['type' => 1])->with([
					'sidebar' => 'product-add',
					'headTitle' => true,
					'pageTitle' => $pageTitle,
				]);
				break;
		}
		
	}
	
	public function store(Request $request)
	{
		// Validation
		$this->validator($request->all())->validate();

		// Initialization
		$slug = str_slug($request->name);
		$userId = Auth::user()->id;
		$preorder_target = null;
		$preorder_expired = null;

		// Photo Validation
        $productPhoto = ProductPhoto::whereNull('product_id')
			->where('user_id', $userId)
			->first();
			
        if (empty($productPhoto)) {
			return redirect()
				->route('product.add', ['type' => 1])
            	->with('warning', 'Foto Produk harus terisi minimal 1 Foto')
            	->withInput($request->all());
        }

		// Point Validation
		$max_point = Option::where('type', 'max-point')->first();
		$max_point = $max_point->content;

		if (!empty($request->point))
		{
			if ($request->point > $max_point) {
				return redirect()
					->route('product.add', ['type' => 1])
					->with('warning', 'Persentase Maksimal Point adalah '.$max_point.'%')
					->withInput($request->all());
			}
		}

		if (!empty($request->discount))
		{
			// if ($request->discount < 15000)
			// {
			// 	return redirect()
			// 		->route('product.add', ['type' => 1])
			// 		->with('warning', 'Harga Diskon minimal adalah Rp 15.000')
			// 		->withInput($request->all());
			// }
			if ($request->discount > $request->price)
			{
				return redirect()
					->route('product.add', ['type' => 1])
					->with('warning', 'Harga Diskon harus Lebih Kecil dari Harga Satuan')
					->withInput($request->all());
			}
		}

		if (!empty($request->preorder))
		{
			// Validation
			$validated = $request->validate([
				'preorder' => 'required|integer',
				'preorder_target' => 'required|numeric|min:1',
				'preorder_expired' => 'required|date',
			]);
			
			if ($request->preorder_target < 1)
			{
				return redirect()
					->route('product.add', ['type' => 1])
					->with('warning', 'Target Group Buy minimal adalah 1 Buah')
					->withInput($request->all());
			}
			
			if ($request->preorder_expired < Carbon::now()->addHour()->format('Y-m-d'))
			{
				return redirect()
					->route('product.add', ['type' => 1])
					->with('warning', 'Batas Waktu Group Buy minimal adalah 1 Jam dari Sekarang')
					->withInput($request->all());
			}

			$preorder_target = $request->preorder_target;
			$preorder_expired = $request->preorder_expired;
		}

		
		// Transaction
		//DB::beginTransaction();
		
		// Price & Discount
		$price = $request->price;
		$discount = $request->discount;

		if (!empty($request->discount))
		{
			$price = $request->discount;
			$discount = $request->price;
		}

		// Slug Validation
		$productCheck = Product::where('slug', $slug)
			->first();

		if (!empty($productCheck)) {
			$slug = $slug.'-'.time();
		}

		// Initialization
		DB::beginTransaction();
        try {
			$typeId = 1;
			$categoryId = $request->category;
			$conditionId = $request->condition;
			$name = $request->name;
			$weight = $request->weight;
			$stock = $request->stock;
			$description = $request->description;
			$point = $request->point;

			$preorder = $request->preorder;

			// Insert
			$product = new Product;
			$product->user_id = $userId;
			$product->type_id = $typeId;
			$product->category_id = $categoryId;
			$product->condition_id = $conditionId;
			$product->name = $name;
			$product->slug = $slug;
			$product->weight = $weight;
			$product->price = $price;
			$product->stock = $stock;
			$product->description = $description;
			$product->discount = $discount;
			$product->point = $point;
			$product->status = 0;
			$product->action_id = 1;

			$product->preorder = $preorder;
			$product->preorder_target = $preorder_target;
			$product->preorder_expired = $preorder_expired;
			
			$product->save();

			$productId = $product->id;
			
			$productPhotoUpdate = ProductPhoto::whereNull('product_id')
				->where('user_id', $userId)->update([
					'product_id' => $productId,
			]);
			DB::commit();
            // Return Redirect Insert Success
            return redirect()
				->route('product')
				->with('status', 'Selamat! Produk Anda telah berhasil ditambahkan.');
		}
		catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }
	public function storeVoucher(Request $request)
	{
		// Validation
		$this->validatorVoucher($request->all())->validate();

		// Initialization
		$slug = str_slug($request->name);
		$userId = Auth::user()->id;
		$preorder_target = null;
		$preorder_expired = null;

		// Photo Validation
        $productPhoto = ProductPhoto::whereNull('product_id')
			->where('user_id', $userId)
			->first();
			
        if (empty($productPhoto)) {
			return redirect()
				->route('product.add', ['type' => 2])
            	->with('warning', 'Foto Produk harus terisi minimal 1 Foto')
            	->withInput($request->all());
        }

		if (!empty($request->discount))
		{
			// if ($request->discount < 15000)
			// {
			// 	return redirect()
			// 		->route('product.add', ['type' => 2])
			// 		->with('warning', 'Harga Diskon minimal adalah Rp 15.000')
			// 		->withInput($request->all());
			// }
			if ($request->discount > $request->price)
			{
				return redirect()
					->route('product.add', ['type' => 2])
					->with('warning', 'Harga Diskon harus Lebih Kecil dari Harga Satuan')
					->withInput($request->all());
			}
		}
		
		// Transaction
		//DB::beginTransaction();
		
		// Price & Discount
		$price = $request->price;
		$discount = $request->discount;
		$max_amount_per_days = $request->max_amount_per_days;

		if (!empty($request->discount))
		{
			$price = $request->discount;
			$discount = $request->price;
		}

		if (!empty($request->max_amount_per_days))
		{
			$max_amount_per_days = $request->max_amount_per_days;
		}

		// Slug Validation
		$productCheck = Product::where('slug', $slug)
			->first();

		if (!empty($productCheck)) {
			$slug = $slug.'-'.time();
		}

		// Initialization
		$typeId = 2;
		$categoryId = 12;
		$conditionId = 1;
		$name = $request->name;
		$weight = 0;
		$stock = $request->stock;
		$description = $request->description;
		$voucher_expired = $request->voucher_expired;
		$point = null;

		$preorder = 0;

		// Insert
		DB::beginTransaction();
		try {
			$product = new Product;
			$product->user_id = $userId;
			$product->type_id = $typeId;
			$product->category_id = $categoryId;
			$product->condition_id = $conditionId;
			$product->name = $name;
			$product->slug = $slug;
			$product->weight = $weight;
			$product->price = $price;
			$product->stock = $stock;
			$product->max_amount_per_days=$max_amount_per_days;
			$product->description = $description;
			$product->discount = $discount;
			$product->point = $point;
			$product->status = 0;
			$product->action_id = 1;
			$product->preorder = $preorder;
			$product->preorder_target = $preorder_target;
			$product->preorder_expired = $preorder_expired;
			$product->voucher_expired = $voucher_expired;
			$product->save();

			$productId = $product->id;
			$productPhotoUpdate = ProductPhoto::whereNull('product_id')
				->where('user_id', $userId)->update([
					'product_id' => $productId,
			]);

			DB::commit();
			return redirect()
				->route('product')
				->with('status', 'Selamat! Produk E-Voucher Anda telah berhasil ditambahkan.');	
		}
		catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }

	protected function validator(array $data)
	{
		return Validator::make($data, [
	   		'name' => 'required|max:255',
			'condition' => 'required|integer',
			'category' => 'required|integer',
			'weight' => 'required|integer|min:1',
			'stock' => 'required|integer|min:1',
			// 'price' => 'required|integer|min:15000',
			'price' => 'required|integer|min:0',
			'description' => 'required',
		]);
   	}
	protected function editValidator(array $data)
	{
		return Validator::make($data, [
	   		'name' => 'required|max:255',
			'condition' => 'required|integer',
			'category' => 'required|integer',
			'weight' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
			'price' => 'required|integer|min:0',
			// 'price' => 'required|integer|min:15000',
			'description' => 'required',
		]);
	   }
	   
	protected function validatorVoucher(array $data)
	{
		return Validator::make($data, [
	   		'name' => 'required|max:255',
			'stock' => 'required|integer|min:1',
			// 'price' => 'required|integer|min:15000',
			'price' => 'required|integer|min:0',
			'description' => 'required',
			'voucher_expired' => 'required|date',
		]);
   	}
	protected function editValidatorVoucher(array $data)
	{
		return Validator::make($data, [
	   		'name' => 'required|max:255',
            'stock' => 'required|integer|min:0',
			// 'price' => 'required|integer|min:15000',
			'price' => 'required|integer|min:0',
			'description' => 'required',
			'voucher_expired' => 'required|date',
		]);
   	}

	public function addPhoto(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

		// Validation
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		
		// Initialization
		$userId = Auth::user()->id;
		$username = Auth::user()->username;

		DB::beginTransaction();
		try {
			// Upload
			$imageName = md5(str_random(10).$userId.$username.$request->file->getClientOriginalName()).'.'.$request->file->getClientOriginalExtension();
			$imagePath = $public.'uploads/products/'.$imageName;
			$request->file->move($public.'uploads/products', $imageName);

			$imageLarge = Image::make($imagePath)->fit(400, 400);
			$imageLarge->save($public.'uploads/products/large-'.$imageName);

			$imageMedium = Image::make($imagePath)->fit(225, 225);
			$imageMedium->save($public.'uploads/products/medium-'.$imageName);

			$imageSmall = Image::make($imagePath)->fit(135, 135);
			$imageSmall->save($public.'uploads/products/small-'.$imageName);
			
			// Insert
			$productPhoto = new ProductPhoto;
			$productPhoto->user_id = $userId;
			$productPhoto->photo = $imageName;
			$productPhoto->save();
			DB::commit();
			return $imageName;
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}
	}
	public function addEditPhoto(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

		// Validation
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		
		// Initialization
		$productId = session('product-id');
		$userId = Auth::user()->id;
		$username = Auth::user()->username;

		DB::beginTransaction();
		try {
			// Upload Image
			$imageName = md5(str_random(10).$userId.$username.$request->file->getClientOriginalName()).'.'.$request->file->getClientOriginalExtension();
			$imagePath = $public.'uploads/products/'.$imageName;
			$request->file->move($public.'uploads/products', $imageName);

			$imageLarge = Image::make($imagePath)->fit(400, 400);
			$imageLarge->save($public.'uploads/products/large-'.$imageName);

			$imageMedium = Image::make($imagePath)->fit(225, 225);
			$imageMedium->save($public.'uploads/products/medium-'.$imageName);

			$imageSmall = Image::make($imagePath)->fit(135, 135);
			$imageSmall->save($public.'uploads/products/small-'.$imageName);

			// Insert
			$insert = new ProductPhoto;
			$insert->product_id = $productId;
			$insert->user_id = $userId;
			$insert->photo = $imageName;
			$insert->save();
			
			// Update
			$update = Product::where('id', $productId)->update([
				'status' => 0,
				'action_id' => 2,
			]);
			DB::commit();

			// Return Image
			return $imageName;
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}
	}

	public function deletePhoto(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
		
		// Initialization
		$fileName = $request->file_name;
		DB::beginTransaction();
		try {
			// Unlink
			$fileDelete = $public.'uploads/products/'.$fileName;
			$fileDeleteLarge = $public.'uploads/products/large-'.$fileName;
			$fileDeleteMedium = $public.'uploads/products/medium-'.$fileName;
			$fileDeleteSmall = $public.'uploads/products/small-'.$fileName;

			if (file_exists($fileDelete)) { unlink($fileDelete); }
			if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
			if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
			if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }
			
			// Delete
			$productPhotoDelete = ProductPhoto::where('photo', $fileName)
				->delete();
			DB::commit();
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}

	}
	public function deleteEditPhoto(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

		// Initialization
		$fileName = $request->file_name;

		// Validation
		$productPhotoCheck = ProductPhoto::where('photo', $fileName)
			->first();

		if (empty($productPhotoCheck)) {
			// Return Redirect
			return redirect('/');
		}

		$productId = $productPhotoCheck->product_id;

		$productPhotoCount = ProductPhoto::where('product_id', $productId)
			->get()
			->count();

		if ($productPhotoCount <= 1) {
			// Return Redirect
			return redirect()
				->route('product.edit', ['slug' => $productPhotoCheck->product->slug])
				->with('warning', 'Foto Tidak Dapat Dihapus!! Minimal harus terdapat 1 Foto Produk.');
		}

		DB::beginTransaction();
		try {
			// Unlink
			$fileDelete = $public.'uploads/products/'.$fileName;
			$fileDeleteLarge = $public.'uploads/products/large-'.$fileName;
			$fileDeleteMedium = $public.'uploads/products/medium-'.$fileName;
			$fileDeleteSmall = $public.'uploads/products/small-'.$fileName;

			if (file_exists($fileDelete)) { unlink($fileDelete); }
			if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
			if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
			if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }

			// Delete
			$productPhotoDelete = ProductPhoto::where('photo', $fileName)
				->delete();

			// Update
			$update = Product::where('id', $productId)->update([
				'status' => 0,
				'action_id' => 2,
			]);
			DB::commit();

			// Redirect
			if (!empty($request->product)) {
				$product = Product::where('id', $request->product)
					->where('user_id', Auth::user()->id)
					->first();

				return redirect()
					->route('product.edit', ['slug' => $product->slug]);
			}
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}
	}

	public function storeComment(Request $request)
	{
		// Validation
		if (empty($request->content)) {
        	return '';
		}

		// Initialization
		$productId = $request->id;
		$userId = Auth::user()->id;
		$content = $request->content;

		// Check
		$check = Product::where('id', $productId)
			->first();
		
		if (empty($check)) {
			return '';
		}

		DB::beginTransaction();
		try {

			// Create
			$insert = new ProductComment;
			$insert->user_id = $userId;
			$insert->product_id = $productId;
			$insert->content = $content;
			$insert->save();

			$commentId = $insert->id;
			$item = ProductComment::where('id', $commentId)
				->first();
			DB::commit();

			// Return View
			return view('layouts.list-comment')->with([
				'item' => $item,
			]);
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}
	}
	public function deleteComment(Request $request)
	{
		// Validation
		if (empty($request->id)) {
        	return '';
		}

		// Initialization
		$commentId = $request->id;
		$userId = Auth::user()->id;

		// Check
		$check = ProductComment::where('id', $commentId)
			->first();
		
		if (empty($check)) {
			return '';
		}

		// Delete
		if ($check->user->id == $userId) {
			$comment = ProductComment::where('id', $commentId)
				->delete();
		}
	}

	public function addReply(Request $request)
	{
		// Validation
		if (empty($request->content)) {
        	return redirect('/');
		}

		// Initialization
		$commentId = $request->dataid;
		$content = $request->content;

		// Check
		$productComment = ProductComment::where('id', $commentId)
			->first();

		if (empty($productComment)) {
			return redirect('/');
		}

		$productId = $productComment->product_id;

		DB::beginTransaction();
		try {
			// Create
			$postcomment_create = new ProductReply;
			$postcomment_create->user_id = Auth::user()->id;
			$postcomment_create->product_id = $productId;
			$postcomment_create->comment_id = $commentId;
			$postcomment_create->content = $content;

			if ($productComment->user->id != Auth::user()->id) {
				$notif_post_comment = new Notification;
				$notif_post_comment->sender_id = Auth::user()->id;
				$notif_post_comment->receiver_id = $productComment->user->id;
				$notif_post_comment->type = 'product_comment_reply';
				$notif_post_comment->product_id = $productId;
				$notif_post_comment->comment_id = $commentId;
				$notif_post_comment->save();
			}

			if (!empty($request->mention)) {
				$mentions = json_decode($request->mention);
				foreach ($mentions as $mention) {
					$userCheck = User::where('id', $mention->id)
						->first();
					if (!empty($userCheck)) {
						if ($mention->id != Auth::user()->id) {
							$notif_post_comment_mention = new Notification;
							$notif_post_comment_mention->sender_id = Auth::user()->id;
							$notif_post_comment_mention->receiver_id = $mention->id;
							$notif_post_comment_mention->type = 'product_comment_reply_mention';
							$notif_post_comment_mention->product_id = $productId;
							$notif_post_comment_mention->comment_id = $commentId;
							$notif_post_comment_mention->save();
						}
					}
				}
			}

			$postcomment_create->save();
			DB::commit();
			$replyId = $postcomment_create->id;
			$reply = ProductReply::where('id', $replyId)
				->first();

			// Return View
			return view('layouts.sharecommentreply')->with([
				'reply' => $reply
			]);

		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}
	}
	public function deleteReply(Request $request)
	{
		// Initializatio 
		$replyId = $request->id;

		// Check
		$replyCheck = ProductReply::where('id', $replyId)
			->where('user_id', Auth::user()->id)
			->first();

		// Delete
		if (!empty($replyCheck)) {
			$reply = ProductReply::where('id', $replyId)
				->where('user_id', Auth::user()->id)
				->delete();
		}
	}

	public function update(Request $request)
	{
		// Validation
		$this->editValidator($request->all())->validate();

		// Initialization
		$productId = $request->product;
		$userId = Auth::user()->id;
		$preorder_target = null;
		$preorder_expired = null;

		// Check
		$product = Product::where('id', $productId)
			->where('user_id', $userId)
			->first();

		if (empty($product)) {
			return redirect('/');
		}

		$data = $request->all();

		// Point
		$max_point = Option::where('type', 'max-point')->first();
		$max_point = $max_point->content;

		if (!empty($request->point))
		{
			if ($request->point > $max_point) {
				return redirect()
					->route('product.edit', ['slug' => $product->slug])
					->with('warning', 'Persentase Maksimal Point adalah '.$max_point.'%')
					->withInput($request->all());
			}
		}

		if (!empty($request->discount))
		{
			// if ($request->discount < 15000)
			// {
			// 	return redirect()
			// 		->route('product.edit', ['slug' => $product->slug])
			// 		->with('warning', 'Harga Diskon minimal adalah Rp 15.000')
			// 		->withInput($request->all());
			// }
			if ($request->discount > $request->price)
			{
				return redirect()
					->route('product.edit', ['slug' => $product->slug])
					->with('warning', 'Harga Diskon harus Lebih Kecil dari Harga Satuan')
					->withInput($request->all());
			}
		}

		if (!empty($request->preorder))
		{
			// Validation
			$validated = $request->validate([
				'preorder' => 'required|integer',
				'preorder_target' => 'required|numeric|min:1',
				'preorder_expired' => 'required|date',
			]);
			
			if ($request->preorder_target < 1)
			{
				return redirect()
					->route('product.edit', ['slug' => $product->slug])
					->with('warning', 'Target Group Buy minimal adalah 1 Buah')
					->withInput($request->all());
			}

			if ($request->preorder_expired < Carbon::now()->addHour()->format('Y-m-d H:i:s'))
			{
				return redirect()
					->route('product.edit', ['slug' => $product->slug])
					->with('warning', 'Batas Waktu Group Buy minimal adalah 1 Jam dari Sekarang')
					->withInput($request->all());
			}
			
			$preorder_target = $request->preorder_target;
			$preorder_expired = $request->preorder_expired;
		}
		
		// Transaction
		//DB::beginTransaction();
		
		// Price & Discount
		$price = $request->price;
		$discount = $request->discount;

		if (!empty($request->discount))
		{
			$price = $request->discount;
			$discount = $request->price;
		}

		// Initialization
		$typeId = 1;
		$categoryId = $request->category;
		$conditionId = $request->condition;
		$name = $request->name;
		$weight = $request->weight;
		$stock = $request->stock;
		$description = $request->description;
		$point = $request->point;

		$preorder = $request->preorder;

		DB::beginTransaction();
		try {
			// Update
			$productUpdate = Product::find($productId);
			$productUpdate->type_id = $typeId;
			$productUpdate->category_id = $categoryId;
			$productUpdate->condition_id = $conditionId;
			$productUpdate->weight = $weight;
			$productUpdate->description = $description;
			$productUpdate->point = $point;
			$productUpdate->stock = $stock;

			$productUpdate->preorder = $preorder;
			$productUpdate->preorder_target = $preorder_target;
			$productUpdate->preorder_expired = $preorder_expired;

			$productUpdate->name = $name;
			$productUpdate->price = $price;
			$productUpdate->discount = $discount;
			
			$productUpdate->save();

			DB::commit();

			// Return Redirect
			return redirect()
				->route('product.edit', ['slug' => $product->slug])
				->with('status', 'Selamat!! Pembaruan Informasi Produk telah berhasil disimpan.');
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}

	}
	public function updateVoucher(Request $request)
	{
		// Validation
		$this->editValidatorVoucher($request->all())->validate();

		// Initialization
		$productId = $request->product;
		$userId = Auth::user()->id;
		$preorder_target = null;
		$preorder_expired = null;

		// Check
		$product = Product::where('id', $productId)
			->where('user_id', $userId)
			->first();

		if (empty($product)) {
			return redirect('/');
		}

		$data = $request->all();

		if (!empty($request->discount))
		{
			// if ($request->discount < 15000)
			// {
			// 	return redirect()
			// 		->route('product.edit', ['slug' => $product->slug])
			// 		->with('warning', 'Harga Diskon minimal adalah Rp 15.000')
			// 		->withInput($request->all());
			// }
			if ($request->discount > $request->price)
			{
				return redirect()
					->route('product.edit', ['slug' => $product->slug])
					->with('warning', 'Harga Diskon harus Lebih Kecil dari Harga Satuan')
					->withInput($request->all());
			}
		}

		// Transaction
		//DB::beginTransaction();
		
		// Price & Discount
		$price = $request->price;
		$discount = $request->discount;

		if (!empty($request->discount))
		{
			$price = $request->discount;
			$discount = $request->price;
		}

		// Initialization
		$typeId = 2;
		$categoryId = 12;
		$conditionId = 1;
		$name = $request->name;
		$weight = 0;
		$stock = $request->stock;
		$max_amount_per_days=$request->max_amount_per_days;
		$description = $request->description;
		$voucher_expired = $request->voucher_expired;
		$point = null;

		$preorder = 0;

		// Update
		DB::beginTransaction();
		try {
			$productUpdate = Product::find($productId);
			$productUpdate->type_id = $typeId;
			$productUpdate->category_id = $categoryId;
			$productUpdate->condition_id = $conditionId;
			$productUpdate->weight = $weight;
			$productUpdate->max_amount_per_days = $max_amount_per_days;
			$productUpdate->description = $description;
			$productUpdate->point = $point;
			$productUpdate->stock = $stock;

			$productUpdate->preorder = $preorder;
			$productUpdate->preorder_target = $preorder_target;
			$productUpdate->preorder_expired = $preorder_expired;

			$productUpdate->name = $name;
			$productUpdate->price = $price;
			$productUpdate->discount = $discount;

			$productUpdate->voucher_expired = $voucher_expired;
			$productUpdate->save();
			DB::commit();
			return redirect()
			->route('product.edit', ['slug' => $product->slug])
			->with('status', 'Selamat!! Pembaruan Informasi Produk E-Voucher telah berhasil disimpan.');
		}
		catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
	}

	public function edit(Request $request, $slug)
	{
		// Initialization
		$slug = str_slug($request->slug);
		$userId = Auth::user()->id;

		// Check
		$product = Product::where('slug', $slug)
			->where('user_id', $userId)
			->first();

		if (empty($product)) {
			return redirect('/');
		}

		$type = $product->type_id;

		// Session
		session(['product-id' => $product->id]);

		// Lists
		$productPhoto = ProductPhoto::where('product_id', $product->id)
			->get();

		$cartCheck = TransactionProduct::where('product_id', $product->id)
			->get()
			->count();
	
		// Price & Discount
		$price = $product->price;
		$discount = $product->discount;

		if (!empty($product->discount))
		{
			$product->discount = $price;
			$product->price = $discount;
		}

		// Type Product
		switch ($type) {
			case 2:
				// Initialization
				$pageTitle = 'Ubah E-Voucher';
				
				// Return View
				return view('voucher.edit')->with([
					'headTitle' => true,
					'pageTitle' => $pageTitle,
					'product' => $product,
					'cartCheck' => $cartCheck,
					'productPhoto' => $productPhoto,
				]);
				break;
			
			default:
				// Initialization
				$pageTitle = 'Ubah Produk';
				
				// Return View
				return view('product.edit')->with([
					'headTitle' => true,
					'pageTitle' => $pageTitle,
					'product' => $product,
					'cartCheck' => $cartCheck,
					'productPhoto' => $productPhoto,
				]);
				break;
		}
    }

	public function delete(Request $request)
	{
		// Initialization
		$productId = $request->product;
		$userId = Auth::user()->id;

		// Check
		$product = Product::where('id', $productId)
			->where('user_id', $userId)
			->first();

		if (empty($product)) {
			return redirect('/');
		}

		$productId = $product->id;

		// Transaction
		//DB::beginTransaction();
		DB::beginTransaction();
		try {

			// Delete
			$update = Product::where('id', $productId)->update([
				'status' => 0,
				'action_id' => 3,
			]);
			DB::commit();
			// Return Redirect
			return redirect()
				->route('product')
				->with('status', 'Selamat!! Produk telah berhasil dihapus.');
		}
		catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
		}
	}
	
	public function product(Request $request)
	{
		// Initialization
		$pageTitle = 'Daftar Produk';
		$userId = Auth::user()->id;
		
		// Lists
		$productReady = Product::where('user_id', $userId)
			->where('stock', '>', 0)
			->orderBy('updated_at', 'desc')
			->simplePaginate(20);

		// Return View
		return view('product.index')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'tab' => 'ready',
			'sidebar' => 'product',
			'productReady' => $productReady,
		]);
	}
	
	public function productStockless(Request $request)
	{
		// Initialization
		$pageTitle = 'Daftar Produk';
		$userId = Auth::user()->id;
		
		// Lists
		$productSoldout = Product::where('user_id', $userId)
			->where('stock', '<=', 0)
			->orderBy('updated_at', 'desc')
			->simplePaginate(20);

		// Return View
		return view('product.stockless')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'tab' => 'ready',
			'sidebar' => 'product',
			'productSoldout' => $productSoldout,
		]);
	}
	public function json(Request $request)
	{
		// Initialization
    	$items = array();

		// Check Location
		$location = Kabupaten::where('id', $request->location)
			->first();

        if (empty($location)) {
			// Return Redirect
        	return redirect('/');
		}

		// Check Product
		$product = Product::where('id', $request->id)
			->where('status', 1)
			->first();

		if (empty($product)) {
			return redirect('/');
		}

        // Check Merchant
        $merchant = Merchant::where('user_id', $product->user_id)
            ->first();

		if (empty($merchant)) {
			return redirect('/');
		}

		// Shipping Input
		$kotaAsal = $product->user->kabupaten->name;
		$kotaTujuan = $location->name;
		$shippingweightCount = $product->weight;

		// Shipping Method Check
		// Check Kota Asal
		$dataKota = str_replace('Kab.','Kab',$kotaAsal);
		$dataKota = str_replace('Kabupaten','Kab',$dataKota);
		$dataNamaKota = trim(str_replace('Kota','', str_replace('Kab','',$dataKota)));
		$dataNamaType = str_replace(' ','', str_replace($dataNamaKota,'',$dataKota));

		$kotaAsal = RajaOngkir::Kota()
			->search('city_name', $dataNamaKota)
			->search('type', $dataNamaType)
			->get();

		// Check Kota Tujuan
		$dataKota = str_replace('Kab.','Kab',$kotaTujuan);
		$dataKota = str_replace('Kabupaten','Kab',$dataKota);
		$dataNamaKota = trim(str_replace('Kota','', str_replace('Kab','',$dataKota)));
		$dataNamaType = str_replace(' ','', str_replace($dataNamaKota,'',$dataKota));

		$kotaTujuan = RajaOngkir::Kota()
			->search('city_name', $dataNamaKota)
			->search('type', $dataNamaType)
			->get();

		// Check Cost - JNE
		$dataOngkir = RajaOngkir::Cost([
			'origin'        => $kotaAsal[0]['city_id'], // id kota asal
			'destination'   => $kotaTujuan[0]['city_id'], // id kota tujuan
			'weight'        => $shippingweightCount, // berat satuan gram
			'courier'       => 'jne', // kode kurir pengantar ( jne / tiki / pos )

			'originType'       => 'city', // cakupan lokasi ( city / subdistrict )
			'destinationType'       => 'city', // cakupan lokasi ( city / subdistrict )
		])->get();

		$ongkirJne = array();
		if ($merchant->shipping_jne == 1)
		{
			if (!empty($dataOngkir))
			{
				foreach ($dataOngkir as $ongkirCheck) {
					foreach ($ongkirCheck['costs'] as $ongkir) {
						$ongkirName = $ongkirCheck['name'];
						$ongkirService = $ongkir['service'];
						$ongkirCostPrice = $ongkir['cost'][0]['value'];
						$ongkirCostTime = $ongkir['cost'][0]['etd'];

						$ongkirDescription = 'JNE '.$ongkirService.' ';

						$ongkirDuration = null;
						if (!empty($ongkirCostTime)) {
							$ongkirDescription .= '('.$ongkirCostTime.' Hari Kerja)';
							$ongkirDuration = $ongkirCostTime.' Hari Kerja';
						}

						$ongkirDescription .= ' - Rp '.number_format($ongkirCostPrice,0,",",".");

						$ongkirJne[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription);

						// Save to Array
						$data = array(
							'name' => 'JNE '.$ongkirService,
							'duration' => $ongkirDuration,
							'price' => 'Rp '.number_format($ongkirCostPrice,0,',','.'),
						);
						
						$items[] = $data;
					}
				}
			}
		}

		// Check Cost - POS
		$dataOngkir = RajaOngkir::Cost([
			'origin'        => $kotaAsal[0]['city_id'], // id kota asal
			'destination'   => $kotaTujuan[0]['city_id'], // id kota tujuan
			'weight'        => $shippingweightCount, // berat satuan gram
			'courier'       => 'pos', // kode kurir pengantar ( jne / tiki / pos )

			'originType'       => 'city', // cakupan lokasi ( city / subdistrict )
			'destinationType'       => 'city', // cakupan lokasi ( city / subdistrict )
		])->get();

		$ongkirPos = array();
		if ($merchant->shipping_pos == 1)
		{
			if (!empty($dataOngkir))
			{
				foreach ($dataOngkir as $ongkirCheck) {
					foreach ($ongkirCheck['costs'] as $ongkir) {
						$ongkirName = $ongkirCheck['name'];
						$ongkirService = $ongkir['service'];
						$ongkirCostPrice = $ongkir['cost'][0]['value'];
						$ongkirCostTime = $ongkir['cost'][0]['etd'];

						$ongkirDescription = 'POS '.$ongkirService.' ';

						$ongkirDuration = null;
						if (!empty($ongkirCostTime)) {
							$ongkirDescription .= '('.$ongkirCostTime.' Hari Kerja)';
							$ongkirDuration = $ongkirCostTime.' Hari Kerja';
						}

						$ongkirDescription .= ' - Rp '.number_format($ongkirCostPrice,0,",",".");

						$ongkirPos[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription);

						// Save to Array
						$data = array(
							'name' => 'POS '.$ongkirService,
							'duration' => $ongkirDuration,
							'price' => 'Rp '.number_format($ongkirCostPrice,0,',','.'),
						);
						
						$items[] = $data;
					}
				}
			}
		}

		// Check Cost - TIKI
		$dataOngkir = RajaOngkir::Cost([
			'origin'        => $kotaAsal[0]['city_id'], // id kota asal
			'destination'   => $kotaTujuan[0]['city_id'], // id kota tujuan
			'weight'        => $shippingweightCount, // berat satuan gram
			'courier'       => 'tiki', // kode kurir pengantar ( jne / tiki / pos )

			'originType'       => 'city', // cakupan lokasi ( city / subdistrict )
			'destinationType'       => 'city', // cakupan lokasi ( city / subdistrict )
		])->get();

		$ongkirTiki = array();
		if ($merchant->shipping_tiki == 1)
		{
			if (!empty($dataOngkir))
			{
				foreach ($dataOngkir as $ongkirCheck) {
					foreach ($ongkirCheck['costs'] as $ongkir) {
						$ongkirName = $ongkirCheck['name'];
						$ongkirService = $ongkir['service'];
						$ongkirCostPrice = $ongkir['cost'][0]['value'];
						$ongkirCostTime = $ongkir['cost'][0]['etd'];

						$ongkirDescription = 'TIKI '.$ongkirService.' ';

						$ongkirDuration = null;
						if (!empty($ongkirCostTime)) {
							$ongkirDescription .= '('.$ongkirCostTime.' Hari Kerja)';
							$ongkirDuration = $ongkirCostTime.' Hari Kerja';
						}

						$ongkirDescription .= ' - Rp '.number_format($ongkirCostPrice,0,",",".");

						$ongkirTiki[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription);

						// Save to Array
						$data = array(
							'name' => 'POS '.$ongkirService,
							'duration' => $ongkirDuration,
							'price' => 'Rp '.number_format($ongkirCostPrice,0,',','.'),
						);
						
						$items[] = $data;
					}
				}
			}
		}
		////////////////////////
		
		// Return Json
		return response()->json($items, 200);
	}
}
