<?php

namespace Marketplace\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use Marketplace\AuthAccessToken;
use Marketplace\Category;
use Marketplace\Http\Controllers\Controller;
use Marketplace\Product;
use Marketplace\Option;
use Marketplace\Season;
use Marketplace\Transaction;

/**
 * @group Products
 *
 * API untuk mengakses list data produk terbaru, trpopuler, produk promo, promo musiman dan sebagainya.
 * 
 */

class ProductController extends Controller
{
    private $hiddenStrict = ['user_id', 'id', 'address_id', 'created_at', 'updated_at'];

    /**
	 * Flash Sale
	 * Menampilkan semua produk yang sedang flash sale.
	 *
     * @queryParam limit default 12 data yang ditampilkan. Example: 16
	 * @queryParam page default hanya menampilkan halaman pertama. Example: 1
	 * 
     * @responseFile responses/flash-sale.get.json
	 */
    public function flashSale(Request $request){
        $options = Option::find([13, 33]);
        $countdownSale = $options->where('type', 'countdown-flashsale')->first();
        $imageSale = $options->where('type', 'bg-flashsale')->first();

        if ($countdownSale->content < now()) {
            return response()->api(400, 'Flash sale tidak tersedia');
        }

        $limit = ($request->limit) ? $request->limit : 12;

        $productSale = Product::where('status', 1)
            ->where('sale', 1)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->with([
                'category',
                'productphoto' => function ($qy) {
                    $qy->inRandomOrder();
                },
                'user' => function ($qy) {
                    $qy->with(['kabupaten', 'merchant' => function($qm){
                        $qm->with(['address' => function ($qa){
                            $qa->with('kabupaten');
                        }]);
                    }]);
                }
            ])
            ->paginate($limit);

        $productSale = $this->defaultProductPayload($productSale);

        $items = collect([
            'countdown' => [
                'expiry_at' => $countdownSale->content,
                'expiry_timestamp' => strtotime($countdownSale->content)
            ],
            'data' => $productSale,
            'image_path' => $imageSale->content
        ]);

        return response()->api(200, 'Data berhasil ditampilkan', $items);
    }

    /**
	 * Group Buy Promo
	 * Menampilkan semua produk yang ada dalam group buy promo.
	 *
     * @queryParam limit default 12 data yang ditampilkan. Example: 16
	 * @queryParam page default hanya menampilkan halaman pertama. Example: 1
	 * 
     * @responseFile responses/group-buy-promo.get.json
	 */
    public function groupBuyPromo(Request $request){
        $limit = ($request->limit) ? $request->limit : 12;
        $imageSale = Option::find(34);

        $productPreorder = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->where('preorder', 1)
            ->where('preorder_expired', '>', date('Y-m-d H:i:s'))
            ->inRandomOrder()
            ->with([
                'category',
                'productphoto' => function ($qy) {
                    $qy->inRandomOrder();
                },
                'user' => function ($qy) {
                    $qy->with(['kabupaten', 'merchant' => function($qm){
                        $qm->with(['address' => function ($qa){
                            $qa->with('kabupaten');
                        }]);
                    }]);
                }
            ])
            ->paginate($limit);

        $productPreorder = $this->defaultProductPayload($productPreorder);

        $items = collect([
            'data' => $productPreorder,
            'image_path' => $imageSale->content
        ]);
        // $items = $items->merge($productPreorder);

        return response()->api(200, 'Data berhasil ditampilkan', $items);
    }

    /**
	 * Category Highlight
	 * Menampilkan semua produk kategori yang di tampilkan di halaman home.
	 *
     * @queryParam limit default 12 data yang ditampilkan per kategori. Example: 16
	 * 
     * @responseFile responses/category-highlight.get.json
	 */
    public function categoryHighlight(Request $request){
        $limit = ($request->limit) ? $request->limit : 12;

        $categoryHighlight = Category::where('highlight', 1)
            ->inRandomOrder()
            ->get();

        $categoryHighlight = $this->categoryProductPayload($categoryHighlight, $limit);

        return response()->api(200, 'Data berhasil ditampilkan', $categoryHighlight);
    }

    /**
	 * Category Product Detail
	 * Menampilkan semua produk detail produk berdasarkan kategori produk.
	 *
     * @queryParam limit default 12 data yang ditampilkan per kategori. Example: 16
     * @queryParam page default hanya menampilkan halaman pertama. Example: 1
	 * 
     * @responseFile responses/category-product-slug.get.json
	 */
    public function categoryProductSlug($slug, Request $request){
        $limit = ($request->limit) ? $request->limit : 12;

        $categoryProduct = Category::where('slug', $slug)
            ->inRandomOrder()
            ->paginate($limit);

        $categoryProduct = $this->categoryProductPayload($categoryProduct, $limit);

        return response()->api(200, 'Data berhasil ditampilkan', $categoryProduct);
    }

    /**
	 * Seasonal Promo
	 * Menampilkan semua produk Promo Musiman untuk ditampilkan di halaman home.
	 *
     * @queryParam limit default 12 data yang ditampilkan per promo. Example: 16
	 * 
     * @responseFile responses/seasonal-promo.get.json
	 */
    public function seasonalPromo(Request $request){
        $limit = ($request->limit) ? $request->limit : 12;

        $seasons = Season::where('expired', '>', date('Y-m-d H:i:s'))
            ->orderBy('expired', 'ASC')
            ->with('random_products')
            ->get();

        $seasons = $this->seasonalProductPayload($seasons, $limit);

        return response()->api(200, 'Data berhasil ditampilkan', $seasons);
    }

    /**
	 * Seasonal Promo Product Detail
	 * Menampilkan semua detail produk promo musiman berdasarkan kategori promo.
	 *
     * @queryParam limit default 12 data yang ditampilkan per kategori. Example: 16
     * @queryParam page default hanya menampilkan halaman pertama. Example: 1
	 * 
     * @responseFile responses/category-product-slug.get.json
	 */
    public function seasonalPromoSlug($slug, Request $request){
        $limit = ($request->limit) ? $request->limit : 12;

        $seasonsProduct = Season::where('slug', $slug)
            ->where('expired', '>', date('Y-m-d H:i:s'))
            ->orderBy('expired', 'ASC')
            ->with('random_products')
            ->paginate();

        $seasonsProduct = $this->seasonalProductPayload($seasonsProduct, $limit);

        return response()->api(200, 'Data berhasil ditampilkan', $seasonsProduct);
    }

    /**
	 * Recommendation
	 * Menampilkan semua produk rekomendasi untuk user login atau pun guest.
	 *
     * @queryParam limit default 30 data yang ditampilkan per kategori. Example: 24
	 * 
     * @responseFile responses/category-product-slug.get.json
	 */
    public function recommendProduct(Request $request){
        $limit = ($request->limit) ? $request->limit : 30;
        $token = $request->bearerToken();
        $category = [];
        // Checking if token exist
        if ($token) {
            $decoded = AuthAccessToken::jwtDecode($token);
            if (!empty($decoded->jti)) {
                $user_id = decrypt($decoded->jti);
    
                $getTransaction = Transaction::where('user_id', $user_id)
                    ->whereNotNull('payment_id')
                    ->whereNotNull('address_id')
                    ->with(['product' => function ($q) {
                        $q
                        ->with('product');
                    }])
                    ->get();
                // push category
                foreach ($getTransaction as $key => $trans) {
                    foreach ($trans->product as $key => $value) {
                        $cat = $value->product->category_id;
                        if(!in_array($cat, $category, true)){
                            array_push($category, $cat);
                        }
                    }
                }
            }
        }

        $productRecommend = Product::where('status', 1)
            ->whereNotNull('discount')
            ->whereIn('category_id', $category)
            ->where('stock', '>', 0)
            ->where('sold', '>=', 0)
            ->where('sale', 0)
            ->where('preorder', 0)
            ->orWhere('rating', '>', 2)
            ->with([
                'category',
                'productphoto' => function ($qy) {
                    $qy->inRandomOrder();
                },
                'user' => function ($qy) {
                    $qy->with(['kabupaten', 'merchant' => function($qm){
                        $qm->with(['address' => function ($qa){
                            $qa->with('kabupaten');
                        }]);
                    }]);
                }
            ])
            ->inRandomOrder()
            ->take($limit)
            ->get();

        $productRecommend = $this->defaultProductPayload($productRecommend);

        return response()->api(200, 'Data berhasil ditampilkan', $productRecommend);
    }

    /**
     * Default Product payload response
     *
     * @param object $payload
     * @return object
     */
	public function defaultProductPayload($payload){
        foreach ($payload as $kd => $vd) {
            $photoFirst = $vd->productphoto->first();
            $seller = $vd->user->merchant;
            if ($seller != null) {
                $seller->merchant = $vd->user->merchant->address;
                $seller->merchant->address = $vd->user->merchant->address->kabupaten->name;
                $payload[$kd]->photo = $photoFirst->photo;
                $payload[$kd]->seller = $seller->makeHidden($this->hiddenStrict);
                // Unset and hidden some object value
                unset($payload[$kd]->productphoto, $payload[$kd]->user, $payload[$kd]->seller->address, $payload[$kd]->seller->merchant->kabupaten);
                $payload[$kd]->makeHidden($this->hiddenStrict);
                $payload[$kd]->category->makeHidden($this->hiddenStrict);
                $payload[$kd]->seller->makeHidden($this->hiddenStrict);
                $payload[$kd]->seller->merchant->makeHidden($this->hiddenStrict);
            } else {
                // Unset payload if merchant detial not exist
                unset($payload[$kd]);
            }
        }
        // Re Index payload data
        $payload = $payload->values();

        return $payload;
    }

    /**
     * Category Default Product payload response
     *
     * @param object $payload
     * @param string $limit
     * @return object
     */
	public function categoryProductPayload($payload, $limit){
        foreach ($payload as $key => $value) {
            $data=[];
            foreach ($payload[$key]->random_product as $k => $v) {
                if ($payload[$key]->random_product[$k] && count($data) < $limit) {
                    $product = $payload[$key]->random_product[$k]->makeHidden($this->hiddenStrict);
                    array_push($data, $product);
                }
            }
            foreach ($data as $kd => $vd) {
                $photoFirst = $vd->productphoto->first();
                $seller = $vd->user->merchant;
                $seller->merchant = $vd->user->merchant->address;
                $seller->merchant->address = $vd->user->merchant->address->kabupaten->name;
                $data[$kd]->photo = $photoFirst->photo;
                $data[$kd]->seller = $seller->makeHidden($this->hiddenStrict);
                // Unset and hidden some object value
                unset($data[$kd]->productphoto, $data[$kd]->user, $data[$kd]->seller->address, $data[$kd]->seller->merchant->kabupaten);
                $data[$kd]->makeHidden($this->hiddenStrict);
                $data[$kd]->seller->makeHidden($this->hiddenStrict);
                $data[$kd]->seller->merchant->makeHidden($this->hiddenStrict);
            }
            $payload[$key]->makeHidden($this->hiddenStrict);
            $payload[$key]->products = $data;
            unset($payload[$key]->random_product);
        }

        return $payload;
    }

    /**
     * Seasonal Promo Default Product payload response
     *
     * @param object $payload
     * @param string $limit
     * @return object
     */
	public function seasonalProductPayload($payload, $limit){
        foreach ($payload as $key => $val) {
            $data=[];
            foreach ($payload[$key]->random_products as $k => $v) {
                if ($payload[$key]->random_products[$k]->product && count($data) < $limit) {
                    $product = $payload[$key]->random_products[$k]->product->makeHidden($this->hiddenStrict);
                    array_push($data, $product);
                }
            }
            foreach ($data as $kd => $vd) {
                $photoFirst = $vd->productphoto->first();
                $seller = $vd->user->merchant;
                $seller->merchant = $vd->user->merchant->address;
                $seller->merchant->address = $vd->user->merchant->address->kabupaten->name;
                $data[$kd]->photo = $photoFirst->photo;
                $data[$kd]->seller = $seller->makeHidden($this->hiddenStrict);
                // Unset and hidden some object value
                unset($data[$kd]->productphoto, $data[$kd]->user, $data[$kd]->seller->address, $data[$kd]->seller->merchant->kabupaten);
                $data[$kd]->makeHidden($this->hiddenStrict);
                $data[$kd]->category->makeHidden($this->hiddenStrict);
                $data[$kd]->seller->makeHidden($this->hiddenStrict);
                $data[$kd]->seller->merchant->makeHidden($this->hiddenStrict);
            }
            $payload[$key]->makeHidden($this->hiddenStrict);
            $payload[$key]->products = $data;
            unset($payload[$key]->random_products);
        }

        return $payload;
    }
}
