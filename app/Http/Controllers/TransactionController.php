<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Marketplace\Events\CounterNotification;

use Marketplace\Option;
use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\UserAddress;
use Marketplace\Product;
use Marketplace\Transaction;
use Marketplace\TransactionAddress;
use Marketplace\TransactionProduct;
use Marketplace\TransactionCheckout;
use Marketplace\TransactionShipping;
use Marketplace\TransactionGateway;
use Marketplace\TransactionPayment;
use Marketplace\TransactionPaymentHistory;
use Marketplace\Balance;
use Marketplace\BalanceTransaction;
use Marketplace\Promo;
use Marketplace\PromoType;
use Marketplace\TransactionPromo;
use Marketplace\Merchant;
use Marketplace\LifePoint;

use Auth;
use Curl;
use Image;
use Validator;
use Carbon\Carbon;
use RajaOngkir;

class TransactionController extends Controller
{
	protected $balanceController;
	protected $pointController;
	public function __construct()
	{
		$this->balanceController = new BalanceController;
		$this->pointController = new LifePointController;
		// $myBalance = new BalanceController;
		// $myPoint = new LifePointController;
	}
	public function cart(Request $request)
	{
		// Initialization
		$pageTitle = 'Keranjang Belanja';
		$userId = Auth::user()->id;

		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			$transaction = new Transaction;
			$transaction->user_id = $userId;
			$transaction->save();
		}

		$transactionId = $transaction->id;

		// Transaction Product Check
		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', null)
			->where('status', 0)
			->get();

		// Transaction Product - Stock Check
		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)
				->where('status', 1)
				->first();

			if (!empty($products)) {
				if ($transaction->unit > $products->stock) {
					$productStockUpdate = TransactionProduct::where('transaction_id', $transactionId)
						->where('product_id', $products->id)
						->where('status', 0)
						->update([
							'unit' => $products->stock
						]);
				}

				if ($products->stock < 1) {
					$productDelete = TransactionProduct::where('transaction_id', $transactionId)
						->where('product_id', $products->id)
						->where('status', 0)
						->delete();
				}
			}

			if (empty($products)) {
				$productDelete = TransactionProduct::where('id', $transaction->id)
					->where('status', 0)
					->delete();
			}
		}

		// Transaction Product List
		$transactionProductNew = array();

		$transactionProductSeller = TransactionProduct::where('transaction_id', $transactionId)
			->where('status', 0)
			->groupBy('user_id')
			->get();

		foreach ($transactionProductSeller as $transactionSeller) {
			if ($transactionSeller->checkout_id > 0) {
				return redirect()
					->route('cart.timeout');
			}

			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('status', 0)
				->where('user_id', $transactionSeller->user_id)
				->get();

			$arrayAdd = array_add($transactionSeller, 'transactionproduct', $transactionProductList);

			$transactionProductNew[] = $arrayAdd;

			if (!empty($transactionSeller->checkout)) {
				if (Carbon::now() >= $transactionSeller->checkout->endtime) {
					return redirect()
						->route('cart.refresh');
				}
			}
		}

		// Return View
		return view('transaction.cart')->with([
			'headTitle' => true,
			'hideFooter' => true,
			'pageTitle' => $pageTitle,
			'transactionProduct' => $transactionProductNew,
		]);
	}

	public function checkout(Request $request)
	{

		// Initialization
		$pageTitle = 'Pengiriman';
		$user = Auth::user();
		$userId = $user->id;

		if ($user->activated == 2) {
			return redirect()->back()
				->with('danger', 'Maaf akun anda kami blokir sementara karena terindikasi melakukan kecurangan. Jika anda tidak melakukan kecurangan, silahkan hubungi customer service MSP Mall.');
		}

		//check life point 
		$lifePoint = LifePoint::where('user_id', $userId)->first();
		if ($lifePoint === null) {
			//if life null, create new life point 
			$lifePointCreate = $this->pointController->create_new($user);
		}


		// Validation
		if (empty(Auth::user()->kabupaten)) {
			return redirect()
				->route('cart');
		}

		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			return redirect()
				->route('cart');
		}

		$transactionId = $transaction->id;

		// Username Support
		$shipping_username = Option::where('type', 'shipping-username')
			->first();
		$shipping_username = $shipping_username->content;

		// Transaction Product - Checkout
		DB::beginTransaction();

		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', null)
			->where('status', 0)
			->get();

		if (count($transactionProduct) > 0) {
			$transactionCheckout = new TransactionCheckout;
			$transactionCheckout->transaction_id = $transactionId;
			$transactionCheckout->starttime = Carbon::now();
			$transactionCheckout->endtime = Carbon::now()->addHour(1);
			$transactionCheckout->save();

			$deleteShippingHistory = TransactionShipping::where('transaction_id', $transactionId)
				->delete();
		}

		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)
				->where('status', 1)
				->first();

			if (!empty($products)) {
				if ($transaction->unit > $products->stock) {
					// Return Redirect
					return redirect()
						->route('cart')
						->with('warning', 'Maaf, Stok Produk ' . $products->name . ' tidak mencukupi!! Hanya tersedia ' . $products->stock . ' Produk');
				}

				if ($products->stock < 1) {
					// Return Redirect
					return redirect()
						->route('cart')
						->with('warning', 'Maaf, Stok Produk ' . $products->name . ' sudah habis, silahkan memilih produk lainnya.');
				}

				$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
					->where('product_id', $transaction->product_id)
					->where('status', 0)
					->update([
						'checkout_id' => $transactionCheckout->id
					]);

				$productStock = ($products->stock - $transaction->unit);

				if ($productStock >= 0) {
					$productUpdate = Product::where('id', $transaction->product_id)
						->update([
							'stock' => $productStock
						]);
				}
			}

			if (empty($products)) {
				$productDelete = TransactionProduct::where('id', $transaction->id)
					->where('status', 0)
					->delete();
			}
		}

		DB::commit();

		// Transaction Product List
		$transactionProductNew = array();
		$ongkirMse = array();

		$transactionProductSeller = TransactionProduct::where('transaction_id', $transactionId)
			->where('status', 0)
			->groupBy('user_id')
			->get();

		foreach ($transactionProductSeller as $transactionSeller) {

			// Add Transaction Product to List
			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('status', 0)
				->where('user_id', $transactionSeller->user_id)
				->get();

			$arrayAdd1 = array_add($transactionSeller, 'transactionproduct', $transactionProductList);

			// Add Shipping Weight to List
			$shippingweightCount = 0;
			foreach ($transactionProductList as $transactionProductList) {
				if (!empty($transactionProductList->product)) {
					$shippingweightCount += ($transactionProductList->product->weight * $transactionProductList->unit);
				}
			}

			$arrayAdd2 = array_add($arrayAdd1, 'shippingweight', $shippingweightCount);

			// Add Shipping Method to List
			$kotaAsal = $transactionSeller->user->kabupaten->name;
			$kotaTujuan = Auth::user()->kabupaten->name;

			// Transaction Address
			$transactionAddressCheck = TransactionAddress::where('transaction_id', $transactionId)
				->first();

			if (!empty($transactionAddressCheck)) {
				$kotaTujuan = $transactionAddressCheck->kabupaten->name;
			}

			// Shipping Method Check
			// Check Kota Asal
			$dataKota = str_replace('Kab.', 'Kab', $kotaAsal);
			$dataKota = str_replace('Kabupaten', 'Kab', $dataKota);
			$dataNamaKota = trim(str_replace('Kota', '', str_replace('Kab', '', $dataKota)));
			$dataNamaType = str_replace(' ', '', str_replace($dataNamaKota, '', $dataKota));

			$kotaAsal = RajaOngkir::Kota()
				->search('city_name', $dataNamaKota)
				->search('type', $dataNamaType)
				->get();

			// Check Kota Tujuan
			if ($transactionSeller->user->kabupaten->name != $kotaTujuan) {
				$dataKota = str_replace('Kab.', 'Kab', $kotaTujuan);
				$dataKota = str_replace('Kabupaten', 'Kab', $dataKota);
				$dataNamaKota = trim(str_replace('Kota', '', str_replace('Kab', '', $dataKota)));
				$dataNamaType = str_replace(' ', '', str_replace($dataNamaKota, '', $dataKota));

				$kotaTujuan = RajaOngkir::Kota()
					->search('city_name', $dataNamaKota)
					->search('type', $dataNamaType)
					->get();
			}

			if ($transactionSeller->user->kabupaten->name == $kotaTujuan) {
				$kotaTujuan = $kotaAsal;
			}

			// Check Merchant
			$merchant = Merchant::where('user_id', $transactionSeller->user_id)
				->first();

			// Check Cost - Express
			if (!empty($transactionAddressCheck)) {

				if ($transactionSeller->user->username == $shipping_username) {
					$totalPrice = null;
					foreach ($transactionSeller->transactionproduct as $transaction) {
						$totalPrice += ($transaction->unit * $transaction->price);
					}

					$express = new Shipping\PricingController;

					$dataOngkir = $express->json(
						$transactionAddressCheck->kabupaten_id,
						$transactionAddressCheck->kecamatan_id,
						$transactionAddressCheck->postal_code,
						$totalPrice
					);

					if (!empty($dataOngkir['items'])) {
						foreach ($dataOngkir['items']['results'] as $ongkirCheck) {
							foreach ($ongkirCheck['costs'] as $ongkir) {
								$ongkirName = $ongkirCheck['name'];
								$ongkirService = $ongkir['service'];
								$ongkirCostPrice = $ongkir['value'];
								$ongkirCostTime = $ongkir['etd'];

								$ongkirDescription = $ongkirCheck['code'] . ' ' . $ongkirService . ' ';
								if (!empty($ongkirCostTime)) {
									$ongkirDescription .= '(' . $ongkirCostTime . ' Hari Kerja)';
								}
								$ongkirDescription .= ' - Rp ' . number_format($ongkirCostPrice, 0, ",", ".");

								$ongkirMse[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription, 'service' => $ongkirService);

								// Transaction Shipping Check
								if (!empty($transactionAddressCheck)) {
									$TransactionShippingCheck = TransactionShipping::where('transaction_id', $transactionId)
										->where('user_id', $transactionSeller->user_id)
										->first();

									// Transaction Choose Shipping Method
									if (!empty($TransactionShippingCheck)) {
										$transactionShippingDelete = TransactionShipping::where('transaction_id', $transactionId)
											->where('user_id', $transactionSeller->user_id)
											->delete();
									}

									$transactionShippingNew = new TransactionShipping;
									$transactionShippingNew->transaction_id = $transactionId;
									$transactionShippingNew->user_id = $transactionSeller->user_id;
									$transactionShippingNew->description = $ongkirDescription;
									$transactionShippingNew->price = $ongkirCostPrice;
									$transactionShippingNew->save();
								}
							}
						}
					}
				}
			}

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
			if ($merchant->shipping_jne == 1) {
				if (!empty($dataOngkir)) {
					foreach ($dataOngkir as $ongkirCheck) {
						foreach ($ongkirCheck['costs'] as $ongkir) {
							$ongkirName = $ongkirCheck['name'];
							$ongkirService = $ongkir['service'];
							$ongkirCostPrice = $ongkir['cost'][0]['value'];
							$ongkirCostTime = $ongkir['cost'][0]['etd'];

							$ongkirDescription = 'JNE ' . $ongkirService . ' ';
							if (!empty($ongkirCostTime)) {
								$ongkirDescription .= '(' . $ongkirCostTime . ' Hari Kerja)';
							}
							$ongkirDescription .= ' - Rp ' . number_format($ongkirCostPrice, 0, ",", ".");

							$ongkirJne[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription, 'service' => $ongkirService);

							// Transaction Shipping Check
							if (!empty($transactionAddressCheck)) {
								$TransactionShippingCheck = TransactionShipping::where('transaction_id', $transactionId)
									->where('user_id', $transactionSeller->user_id)
									->first();

								// Transaction Choose Shipping Method
								if (!empty($TransactionShippingCheck)) {
									$transactionShippingDelete = TransactionShipping::where('transaction_id', $transactionId)
										->where('user_id', $transactionSeller->user_id)
										->delete();
								}

								$transactionShippingNew = new TransactionShipping;
								$transactionShippingNew->transaction_id = $transactionId;
								$transactionShippingNew->user_id = $transactionSeller->user_id;
								$transactionShippingNew->description = $ongkirDescription;
								$transactionShippingNew->price = $ongkirCostPrice;
								$transactionShippingNew->save();
							}
						}
					}
				}
			}

			$arrayAdd3 = array_add($arrayAdd2, 'ongkirjne', $ongkirJne);

			$ongkirPos = array();

			if ($merchant->shipping_pos == 1) {
				if ($shippingweightCount <= 50000) {
					// Check Cost - POS
					$dataOngkir = RajaOngkir::Cost([
						'origin'        => $kotaAsal[0]['city_id'], // id kota asal
						'destination'   => $kotaTujuan[0]['city_id'], // id kota tujuan
						'weight'        => $shippingweightCount, // berat satuan gram
						'courier'       => 'pos', // kode kurir pengantar ( jne / tiki / pos )

						'originType'       => 'city', // cakupan lokasi ( city / subdistrict )
						'destinationType'       => 'city', // cakupan lokasi ( city / subdistrict )
					])->get();

					if (!empty($dataOngkir)) {
						foreach ($dataOngkir as $ongkirCheck) {
							foreach ($ongkirCheck['costs'] as $ongkir) {
								$ongkirName = $ongkirCheck['name'];
								$ongkirService = $ongkir['service'];
								$ongkirCostPrice = $ongkir['cost'][0]['value'];
								$ongkirCostTime = $ongkir['cost'][0]['etd'];

								$ongkirDescription = 'POS ' . $ongkirService . ' ';
								if (!empty($ongkirCostTime)) {
									$ongkirDescription .= '(' . $ongkirCostTime . ' Hari Kerja)';
								}
								$ongkirDescription .= ' - Rp ' . number_format($ongkirCostPrice, 0, ",", ".");

								$ongkirPos[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription, 'service' => $ongkirService);

								// Transaction Shipping Check
								if (!empty($transactionAddressCheck)) {
									$TransactionShippingCheck = TransactionShipping::where('transaction_id', $transactionId)
										->where('user_id', $transactionSeller->user_id)
										->first();

									// Transaction Choose Shipping Method
									if (empty($TransactionShippingCheck)) {
										$transactionShippingNew = new TransactionShipping;
										$transactionShippingNew->transaction_id = $transactionId;
										$transactionShippingNew->user_id = $transactionSeller->user_id;
										$transactionShippingNew->description = $ongkirDescription;
										$transactionShippingNew->price = $ongkirCostPrice;
										$transactionShippingNew->save();
									}
								}
							}
						}
					}
				}
			}

			$arrayAdd4 = array_add($arrayAdd3, 'ongkirpos', $ongkirPos);

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
			if ($merchant->shipping_tiki == 1) {
				if (!empty($dataOngkir)) {
					foreach ($dataOngkir as $ongkirCheck) {
						foreach ($ongkirCheck['costs'] as $ongkir) {
							$ongkirName = $ongkirCheck['name'];
							$ongkirService = $ongkir['service'];
							$ongkirCostPrice = $ongkir['cost'][0]['value'];
							$ongkirCostTime = $ongkir['cost'][0]['etd'];

							$ongkirDescription = 'TIKI ' . $ongkirService . ' ';
							if (!empty($ongkirCostTime)) {
								$ongkirDescription .= '(' . $ongkirCostTime . ' Hari Kerja)';
							}
							$ongkirDescription .= ' - Rp ' . number_format($ongkirCostPrice, 0, ",", ".");

							$ongkirTiki[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription, 'service' => $ongkirService);

							// Transaction Shipping Check
							if (!empty($transactionAddressCheck)) {
								$TransactionShippingCheck = TransactionShipping::where('transaction_id', $transactionId)
									->where('user_id', $transactionSeller->user_id)
									->first();

								// Transaction Choose Shipping Method
								if (empty($TransactionShippingCheck)) {
									$transactionShippingNew = new TransactionShipping;
									$transactionShippingNew->transaction_id = $transactionId;
									$transactionShippingNew->user_id = $transactionSeller->user_id;
									$transactionShippingNew->description = $ongkirDescription;
									$transactionShippingNew->price = $ongkirCostPrice;
									$transactionShippingNew->save();
								}
							}
						}
					}
				}
			}

			$arrayAdd5 = array_add($arrayAdd4, 'ongkirtiki', $ongkirTiki);

			// MSE Express
			$arrayAdd6 = array_add($arrayAdd5, 'ongkirmse', $ongkirMse);

			// Transaction Shipping Check
			$TransactionShipping = TransactionShipping::where('transaction_id', $transactionId)
				->where('user_id', $transactionSeller->user_id)
				->first();

			$arrayAdd = array_add($arrayAdd6, 'transactionshipping', $TransactionShipping);
			////////////////////////

			// Save All List to Array
			$transactionProductNew[] = $arrayAdd;

			// Check status Checkout Endtime
			if (!empty($transactionSeller->checkout)) {
				if (Carbon::now() >= $transactionSeller->checkout->endtime) {
					// Return Redirect
					return redirect()->route('cart.refresh');
				}
			}
		}

		// Transaction Address
		$transactionAddress = TransactionAddress::where('transaction_id', $transactionId)
			->first();

		// User Address
		$userAddress = UserAddress::where('user_id', $userId)
			->get();

		// Address Data
		$dataProvinsi = Provinsi::orderBy('name', 'asc')
			->get();

		// Return View
		return view('transaction.checkout')->with([
			'headTitle' => true,
			'hideFooter' => true,
			'pageTitle' => $pageTitle,

			'transactionId' => $transactionId,
			'userAddress' => $userAddress,
			'transactionAddress' => $transactionAddress,
			'transactionProduct' => $transactionProductNew,
			'dataProvinsi' => $dataProvinsi,

			'shipping_username' => $shipping_username,
		]);
	}

	public function gateway(Request $request)
	{
		// Initialization
		$pageTitle = 'Pembayaran';
		$user = Auth::user();
		$userId = Auth::user()->id;
		$kredivo = new KredivoController;
		$kredivoPaymentUrl = 'v2/payments';
		$kredivoPaymentTypes = null;
		$kredivoPaymentId = ($request->payment_type) ? $request->payment_type : '30_days';
		$totalPointPrice = 0;
		$totalShippingPrice = 0;
		$totalPromoPrice = 0;
		$paymentMessageStatus = null;
		$myBalance = 0;


		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();
		// Check and Calculate Promo code
		if (!empty($transaction->promo)) {
			$promo = Promo::getDetailPromoByCode($transaction->promo->code);
			if (!empty($promo)) {
				$totalShippingPrice = TransactionShipping::sumShippingPriceByTransId($transaction->id);
				$transactionProduct = TransactionProduct::getCalculateTransactionProductsByTransId($transaction->id);

				// Checking Promo Quota
				$quotaTotal = TransactionPromo::where('promo_id', $promo->id)
					->whereHas('transaction', function ($q) {
						$q->whereNotNull('payment_id');
					});
				$quotaUser = TransactionPromo::where('promo_id', $promo->id)
					->whereHas('transaction', function ($q) {
						$q->whereNotNull('payment_id');
					})
					->where('user_id', Auth::user()->id);
				$quota_total = $quotaTotal->count(); //4
				$quota_day = $quotaTotal->whereDate('updated_at', Carbon::today())->count(); //3
				$quota_user_total = $quotaUser->count(); //3
				$quota_user = $quotaUser->whereDate('updated_at', Carbon::today())->count(); // 2
				if ($quota_total >= $promo->total_quota || $quota_day >= $promo->quota || $quota_user_total >= $promo->quota_user_total || $quota_user >= $promo->quota_user_day) {
					//if quota tidak memenuhi syarat maka di hapus di transaki promo;
					TransactionPromo::deleteTransactionPromoByUserId($transaction->id, Auth::user()->id);
				}

				// Promo Validation
				if ($transactionProduct->total < $promo->transaction_min) {
					TransactionPromo::deleteTransactionPromoByUserId($transaction->id, Auth::user()->id);
				}

				// Calculate Promo code
				switch ($promo->type_id) {
					case 1:
						$total_transaction = $totalShippingPrice;
						break;
					case 2:
						$total_transaction = $transactionProduct->total;
						break;
					default:
						$total_transaction = 0;
						break;
				}

				// Promo Validation
				if ($promo->discount_price != null) {
					$totalPromoPrice = (int) $promo->discount_price;
					if ($total_transaction < $promo->discount_price) {
						$totalPromoPrice = (int) $total_transaction;
					}
				} else {
					$totalPromoPrice = $total_transaction * ($promo->discount_percent / 100);
					if ($totalPromoPrice > $promo->discount_max) {
						$totalPromoPrice = (int) $promo->discount_max;
					}
				}
				// Update Promo Amount
				$dataPromo = TransactionPromo::updateTransactionPromoPriceByTransId($transaction->id, $totalPromoPrice);
				if ($dataPromo) {
					$transaction->promo = $dataPromo;
				}
				$promoType = PromoType::find($promo->type_id);

				$promoItems = [
					"id"		=> "discount",
					"name"		=> $promoType->name,
					"price"		=> $totalPromoPrice,
					"quantity"	=> 1
				];
			} else {
				TransactionPromo::deleteTransactionPromoByUserId($transaction->id, Auth::user()->id);
			}
		}

		if (empty(Auth::user()->kabupaten)) {
			return redirect()
				->route('cart');
		}

		if (empty($transaction)) {
			return redirect()
				->route('cart');
		}

		$transactionData = $transaction;
		$transactionId = $transaction->id;
		$gatewayId = $transaction->gateway_id;

		// Transaction Product - Checkout
		DB::beginTransaction();

		/*
		$gatewayId = 1;
		$transactionUpdate = Transaction::where('id', $transactionId)
			->update([
				'gateway_id' => $gatewayId
		]);
		*/

		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', null)
			->where('status', 0)
			->get();

		if (count($transactionProduct) > 0) {
			$transactionCheckout = new TransactionCheckout;
			$transactionCheckout->transaction_id = $transactionId;
			$transactionCheckout->starttime = Carbon::now();
			$transactionCheckout->endtime = Carbon::now()->addHour(1);
			$transactionCheckout->save();
		}

		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)
				->where('status', 1)
				->first();

			if (!empty($products)) {
				if ($transaction->unit > $products->stock) {
					// Return Redirect
					return redirect()
						->route('cart')
						->with('warning', 'Maaf, Stok Produk ' . $products->name . ' tidak mencukupi!! Hanya tersedia ' . $products->stock . ' Produk');
				}

				if ($products->stock < 1) {
					// Return Redirect
					return redirect()
						->route('cart')
						->with('warning', 'Maaf, Stok Produk ' . $products->name . ' sudah habis, silahkan memilih produk lainnya.');
				}

				$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
					->where('product_id', $transaction->product_id)
					->where('status', 0)
					->update(['checkout_id' => $transactionCheckout->id]);

				$productStock = ($products->stock - $transaction->unit);

				if ($productStock >= 0) {
					$productUpdate = Product::where('id', $transaction->product_id)
						->update([
							'stock' => $productStock
						]);
				}
			}

			if (empty($products)) {
				$productDelete = TransactionProduct::where('id', $transaction->id)
					->where('status', 0)
					->delete();
			}
		}

		DB::commit();

		// Transaction Product List
		$transactionProductNew = array();

		$transactionProductSeller = TransactionProduct::where('transaction_id', $transactionId)
			->where('status', 0)
			->groupBy('user_id')
			->get();

		$productItems = []; // Init product items list
		foreach ($transactionProductSeller as $transactionSeller) {
			// Add Transaction Product to List
			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('status', 0)
				->where('user_id', $transactionSeller->user_id)
				->get();

			$arrayAdd1 = array_add($transactionSeller, 'transactionproduct', $transactionProductList);

			// Transaction Shipping Check
			$TransactionShipping = TransactionShipping::where('transaction_id', $transactionId)
				->where('user_id', $transactionSeller->user_id)
				->first();

			$arrayAdd1x = array_add($arrayAdd1, 'transactionshipping', $TransactionShipping);

			// Add Shipping Weight to List
			$shippingweightCount = 0;
			$itemCount = count($transactionProductList); // Init items count
			foreach ($transactionProductList as $transactionProduct) {
				$shippingweightCount += ($transactionProduct->product->weight * $transactionProduct->unit);

				$totalPointPrice = ($transactionProduct->point * $transactionProduct->point_price);

				// Initialize product items for kredivo
				$items = [
					"id" => $transactionProduct->product->id,
					"quantity" => $transactionProduct->unit,
					"price" => $transactionProduct->price + (ceil(($TransactionShipping->price / $itemCount)) / $transactionProduct->unit),
					"name" => $transactionProduct->product->name,
					"type" => $transactionProduct->product->category->name,
					"url" => route('product.detail', ['slug' => $transactionProduct->product->slug]),
					"image_url" => url('uploads/products/medium-' . $transactionProduct->product->productphoto[0]->photo)
				];


				// Push all items
				array_push($productItems, $items);

				// Point
				if ($transactionProduct->point > 0) {
					$point_used = ($transactionProduct->point * $transactionProduct->point_price);
					$items = [
						"id" => 'POINT' . $transactionProduct->id,
						"name" => 'MSP Point',
						"price" => -$point_used,
						"quantity" => $transactionProduct->point
					];
					// Push all items
					array_push($productItems, $items);
				}
			}
			$totalShippingPrice += $transactionProduct->price;

			$arrayAdd2 = array_add($arrayAdd1x, 'shippingweight', $shippingweightCount);

			// Save All List to Array
			$transactionProductNew[] = $arrayAdd2;

			// Check status Checkout Endtime
			if (Carbon::now() >= $transactionSeller->checkout->endtime) {
				// Return Redirect
				return redirect()->route('cart.refresh');
			}
		}

		// Transaction Gateway
		$transactionGateway = TransactionGateway::where('status', 1)->orderBy('id', 'ASC')->get();
		// Push if promoItems exists
		if (!empty($promoItems)) {
			array_push($productItems, $promoItems);
		}
		if ($gatewayId === 4) {
			// Checking if kredivo payment ready on database
			$kredivoGatewayExist = $transactionGateway->filter(function ($el) use ($gatewayId) {
				return $el->id === $gatewayId;
			})->first();

			if ($kredivoGatewayExist) {
				try {
					// Initialize body data
					$bodyData = [
						'amount' 		=> $transaction->total,
						'items' 		=> $productItems
					];
					// Fetch Payment Types
					$post = $kredivo->post($kredivoPaymentUrl, $bodyData);

					// Return payment type listed
					if ($post && $post->status == 'OK') {
						$kredivoPaymentTypes = collect($post->payments)->sortBy('tenure');
					} else {
						$gatewayId = 1;
						Transaction::where('id', $transactionId)->update(['gateway_id' => $gatewayId]);
						$paymentMessageStatus = 'Metode pembayaran kredivo sedang mengalami gangguan! Silahkan gunakan metode pembayaran yang lain.';
					}
				} catch (Exception $e) {
					//throw $e;
					report($e);
					return redirect()->route('cart.refresh');
				}
			} else {
				$gatewayId = 1;
				Transaction::where('id', $transactionId)->update(['gateway_id' => $gatewayId]);
			}
		} else if ($gatewayId === 3) {
			$myBalance = $this->pointController->get_life_point($user);
		} else if ($gatewayId === 2) {
			$myBalance = $this->balanceController->myBalance();;
		}

		// Current detail transaction gateway
		$gatewayDetail = $transactionGateway->filter(function ($el) use ($gatewayId) {
			return $el->id == $gatewayId;
		})->first();

		// Check Balance

		//$myBalance = $this->myBalance();	

		// Return View
		return view('transaction.gateway')->with([
			'headTitle' => true,
			'hideFooter' => true,
			'pageTitle' => $pageTitle,

			'kredivoPaymentTypes' => $kredivoPaymentTypes,
			'kredivoPaymentId' => $kredivoPaymentId,
			'gatewayId' => $gatewayId,
			'gatewayDetail' => $gatewayDetail,
			'transactionId' => $transactionId,
			'transactionData' => $transactionData,
			'transactionProduct' => $transactionProductNew,
			'transactionGateway' => $transactionGateway,
			'myBalance' => $myBalance,
			'paymentMessageStatus' => $paymentMessageStatus
		]);
	}

	public function refresh(Request $request)
	{
		// Initialization
		$userId = Auth::user()->id;

		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			// Return Redirect
			return redirect()
				->route('cart');
		}

		$transactionId = $transaction->id;

		// Transaction Product - Checkout
		DB::beginTransaction();

		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', '>', 0)
			->where('status', 0)
			->get();

		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)->first();

			if (Carbon::now() >= $transaction->checkout->endtime) {
				$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
					->where('product_id', $transaction->product_id)
					->where('status', 0)
					->update([
						'checkout_id' => null
					]);

				if (!empty($products)) {
					$productStock = ($products->stock + $transaction->unit);

					$productUpdate = Product::where('id', $transaction->product_id)
						->update([
							'stock' => $productStock
						]);
				}
			}
		}

		$checkoutList = TransactionCheckout::get();
		foreach ($checkoutList as $checkout) {
			$transactionProduct = TransactionProduct::where('checkout_id', $checkout->id)
				->first();

			if (empty($transactionProduct)) {
				$transactionCheckoutDelete = TransactionCheckout::where('id', $transaction->checkout_id)
					->delete();
			}
		}

		DB::commit();

		// Return Redirect
		return redirect()
			->route('cart');
	}

	public function timeout(Request $request)
	{
		// Initialization
		$userId = Auth::user()->id;

		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			// Return Redirect
			return redirect()
				->route('cart');
		}

		$transactionId = $transaction->id;

		DB::beginTransaction();

		// Transaction Product - Checkout
		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', '>', 0)
			->where('status', 0)
			->get();

		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)->first();

			$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
				->where('product_id', $transaction->product_id)
				->where('status', 0)
				->update([
					'checkout_id' => null
				]);

			if (!empty($products)) {
				$productStock = ($products->stock + $transaction->unit);

				$productUpdate = Product::where('id', $transaction->product_id)
					->update([
						'stock' => $productStock
					]);
			}
		}

		$checkoutList = TransactionCheckout::get();
		foreach ($checkoutList as $checkout) {
			$transactionProduct = TransactionProduct::where('checkout_id', $checkout->id)
				->first();

			if (empty($transactionProduct)) {
				$transactionCheckoutDelete = TransactionCheckout::where('id', $transaction->checkout_id)
					->delete();
			}
		}

		DB::commit();

		// Return Redirect
		return redirect()
			->route('cart');
	}

	public function summaryCart(Request $request)
	{
		// Initialization
		$totalStore = 0;
		$totalProduct = 0;
		$totalPrice = 0;
		$totalProductPrice = 0;
		$totalShippingPrice = 0;
		$userId = Auth::user()->id;

		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		// Return Empty
		if (empty($transaction)) {
			return '';
		}

		$transactionId = $transaction->id;

		// Transaction Product List
		$transactionProductNew = array();

		$transactionProductSeller = TransactionProduct::where('transaction_id', $transactionId)
			->where('status', 0)
			->groupBy('user_id')
			->get();

		foreach ($transactionProductSeller as $transactionSeller) {

			$totalStore += 1;

			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('status', 0)
				->where('user_id', $transactionSeller->user_id)
				->get();

			foreach ($transactionProductList as $transactionProduct) {
				$totalProduct += $transactionProduct->unit;
				$totalProductPrice += ($transactionProduct->unit * $transactionProduct->price);

				// Point
				//$totalProductPrice -= ($transactionProduct->point * $transactionProduct->point_price);
			}
		}

		$transactionShippingList = TransactionShipping::where('transaction_id', $transactionId)
			->get();

		foreach ($transactionShippingList as $shippingList) {
			$totalShippingPrice += $shippingList->price;
		}

		DB::beginTransaction();

		$totalPrice = ($totalProductPrice + $totalShippingPrice);
		$transactionUpdate = Transaction::where('id', $transactionId)
			->where('user_id', $userId)
			->update([
				'total' => $totalPrice
			]);

		DB::commit();

		// Return Price
		return 'Rp ' . number_format($totalProductPrice, 0, ",", ".");
	}

	public function summaryCheckout(Request $request)
	{
		// Initialization
		$totalStore = 0;
		$totalProduct = 0;
		$totalPrice = 0;
		$totalProductPrice = 0;
		$totalShippingPrice = 0;
		$totalPromoPrice = 0;
		$position = $request->position;
		$userId = Auth::user()->id;

		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			return '';
		}

		$transactionId = $transaction->id;

		// Check Promo
		if (!empty($transaction->promo)) {
			$promo = Promo::where('code', $transaction->promo->code)
				->where('expired', '>', Carbon::now()->format('Y-m-d H:i:s'))
				->first();

			if (empty($promo)) {
				$delete = TransactionPromo::where('transaction_id', $transaction->id)
					->where('user_id', Auth::user()->id)
					->delete();
			}
		}

		// Transaction Product List
		$transactionProductNew = array();

		$transactionProductSeller = TransactionProduct::where('transaction_id', $transactionId)
			->where('status', 0)
			->groupBy('user_id')
			->get();

		foreach ($transactionProductSeller as $transactionSeller) {
			$totalStore += 1;

			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('status', 0)
				->where('user_id', $transactionSeller->user_id)
				->get();

			foreach ($transactionProductList as $transactionProduct) {
				$totalProduct += $transactionProduct->unit;
				$totalProductPrice += ($transactionProduct->unit * $transactionProduct->price);

				if ($position != 'checkout') {
					// Point
					$totalProductPrice -= ($transactionProduct->point * $transactionProduct->point_price);
				}
			}
		}

		$transactionShippingList = TransactionShipping::where('transaction_id', $transactionId)
			->get();

		foreach ($transactionShippingList as $shippingList) {
			/*
			// Promo Shipping
			if (!empty($transaction->promo))
			{
				if ($promo->type_id == 1)
				{
					$shippingCode = substr($shippingList->description, 0, 3);
					//if ($shippingCode == $promo->shipping_code)
					//{
						$totalShippingProductPrice = 0;

						foreach ($shippingList->transaction->product as $shippingProduct)
						{
							$totalShippingProductPrice += ($shippingProduct->unit * $shippingProduct->price);
			
							if ($position != 'checkout') {
								// Point
								$totalShippingProductPrice -= ($shippingProduct->point * $shippingProduct->point_price);
							}
						}
						
						// Promo
						$shippingPromoPrice = $promo->discount_price;

						if ($shippingList->price < $promo->discount_price)
						{
							$shippingPromoPrice = $shippingList->price;
						}
						
						$totalPromoPrice += $shippingPromoPrice;
					//}

					//if ($shippingCode != $promo->shipping_code)
					//{
						// Delete
					//	$delete = TransactionPromo::where('transaction_id', $transactionId)
					//		->where('user_id', Auth::user()->id)
					//		->delete();
					//}
				}
			}
			*/

			// Total Shipping
			$totalShippingPrice += $shippingList->price;
		}

		// Promo
		if (!empty($transaction->promo)) {
			if (!empty($promo)) {
				// Checking Promo Quota
				$quotaTotal = TransactionPromo::where('promo_id', $promo->id)
					->whereHas('transaction', function ($q) {
						$q->whereNotNull('payment_id');
					});
				$quotaUser = TransactionPromo::where('promo_id', $promo->id)
					->whereHas('transaction', function ($q) {
						$q->whereNotNull('payment_id');
					})
					->where('user_id', Auth::user()->id);
				$quota_total = $quotaTotal->count(); //4
				$quota_day = $quotaTotal->whereDate('updated_at', Carbon::today())->count(); //3
				$quota_user_total = $quotaUser->count(); //3
				$quota_user = $quotaUser->whereDate('updated_at', Carbon::today())->count(); // 2

				if ($quota_total >= $promo->total_quota || $quota_day >= $promo->quota || $quota_user_total >= $promo->quota_user_total || $quota_user >= $promo->quota_user_day) {
					//if quota tidak memenuhi syarat maka di hapus di transaki promo;
					TransactionPromo::deleteTransactionPromoByUserId($transaction->id, Auth::user()->id);
				}
				// Promo Validation
				if ($totalProductPrice < $promo->transaction_min) {
					$delete = TransactionPromo::where('transaction_id', $transaction->id)
						->where('user_id', Auth::user()->id)
						->delete();
				}
				// Promo Shipping
				if ($promo->type_id == 1) {
					$shippingPromoPrice = $totalShippingPrice;

					if ($totalShippingPrice > $promo->discount_price) {
						$shippingPromoPrice = $promo->discount_price;
					}

					$totalPromoPrice = $shippingPromoPrice;
				}

				// Promo Transaction
				if ($promo->type_id == 2) {
					$totalBeforePromo = ($totalProductPrice + $totalShippingPrice);

					$totalPromoPrice = $totalProductPrice * ($promo->discount_percent / 100);

					if ($totalPromoPrice > $promo->discount_max) {
						$totalPromoPrice = $promo->discount_max;
					}
				}
			}
		}

		DB::beginTransaction();

		$totalPrice = ($totalProductPrice + $totalShippingPrice - $totalPromoPrice);
		$transactionUpdate = Transaction::where('id', $transactionId)
			->where('user_id', Auth::user()->id)
			->update([
				'total' => $totalPrice
			]);

		DB::commit();

		// Return Price
		return 'Rp ' . number_format($totalPrice, 0, ",", ".");
	}

	public function point(Request $request)
	{
		// Initialization
		$id = $request->id;
		$point = $request->point;

		// Validation
		$validator = Validator::make($request->all(), [
			'point' => 'required|integer',
			'id' => 'required',
		]);

		if ($validator->fails()) {
			$response = array_add(['success' => true], 'content', 'Maaf, Jumlah Point harus berupa Angka');
			return json_encode($response);
		}

		// Point Price
		$point_price = Option::where('type', 'point-price')->first();
		$point_price = $point_price->content;

		// Point Api
		$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
		$operation = 'check_mspoint';
		$mypoint = 0;

		$username = Auth::user()->username;

		// Check Point
		$response = new MsplifeController;
		$response = $response->check_mspoint($operation, $username);

		// Point
		if (!empty($response->status)) {
			if ($response->status == 1) {
				$mypoint = $response->point;
			}
		}

		// Check
		$transactionProduct = TransactionProduct::where('id', $id)
			->first();

		if (empty($transactionProduct)) {
			// Return Json
			$response = array_add(['success' => true], 'content', 'Produk Tidak Ditemukan');
			return json_encode($response);
		}

		$transactionId = $transactionProduct->transaction_id;

		if ($transactionProduct->transaction->user_id == Auth::user()->id) {

			// Product Data Check
			$products = Product::where('id', $transactionProduct->product_id)
				->first();

			// Counting
			$mspPoint = $transactionProduct->product->point / 100;
			$mspPrice = $transactionProduct->unit * $transactionProduct->price;

			$mspMax = $mspPoint * $mspPrice;

			$msp = $mspMax / $point_price;

			// Floor Point & Min 1
			$msp_before = $msp;
			$msp = floor($msp);
			if ($msp == 0) {
				if ($msp_before > 0 and $msp_before < 1) {
					$msp = 1;
				}
			}
			$msp_price = $msp * $point_price;

			$mspTotal = $mspPrice - $msp_price;

			// Maxium Point Used
			if ($point > $msp) {
				// Return Json
				$response = array_add(['success' => true], 'content', 'Maaf, Maksimal anda hanya dapat menggunakan ' . $msp . ' point saja');
				return json_encode($response);
			}

			// Maxiumum My Point
			if ($point > $mypoint) {
				// Return Json
				$response = array_add(['success' => true], 'content', 'Maaf, point anda tidak cukup!! Hanya tersedia ' . $mypoint . ' point saja');
				$response = array_add($response, 'point', ' ');

				return json_encode($response);
			}

			// 
			$transactionProductUpdate = TransactionProduct::where('id', $id)
				->update([
					'point' => $point,
					'point_price' => $point_price
				]);

			// Product Point
			$transactionProductPointPrice = 0;

			$transactionProductPoint = TransactionProduct::whereHas('transaction', function ($q) {
				$q->where('user_id', Auth::user()->id);
			})
				->where('status', '<=', 1)
				->sum('point');

			if ($transactionProductPoint > $mypoint) {
				$transactionProductUpdate = TransactionProduct::where('id', $id)
					->update([
						'point' => 0,
						'point_price' => 0
					]);

				// Return Json
				$response = array_add(['success' => true], 'content', 'Maaf, point anda tidak cukup!! Hanya tersedia ' . $mypoint . ' point saja');
				$response = array_add($response, 'point', ' ');

				return json_encode($response);
			}

			// Product Price & Point Price
			$storeId = $transactionProduct->user_id;
			$transactionProductPrice = 0;
			$transactionId = $transactionProduct->transaction_id;

			// Product Price Count
			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('user_id', $storeId)
				->get();

			foreach ($transactionProductList as $productList) {
				$transactionProductPrice += ($productList->unit * $productList->price);

				// Point Price
				$transactionProductPointPrice += ($productList->point * $productList->point_price);
			}

			// Transaction Shipping
			$transactionShipping = TransactionShipping::where('transaction_id', $transactionId)
				->where('user_id', $storeId)
				->first();

			if (!empty($transactionShipping)) {
				$transactionProductPrice += $transactionShipping->price;
			}

			// Product Response Success
			$response = array_add(['success' => true], 'content', 'Rp ' . number_format(($transactionProductPrice - $transactionProductPointPrice), 0, ',', '.'));
			$response = array_add($response, 'point', '- Rp ' . number_format($transactionProductPointPrice, 0, ',', '.'));

			// Return Json
			return json_encode($response);
		}

		// Return Json
		$response = array_add(['success' => true], 'content', 'Maaf, Anda Tidak Memiliki Otoritas');
		return json_encode($response);
	}
	public function unit(Request $request)
	{
		// Initialization
		$id = $request->id;
		$unit = $request->unit;
		$userId = Auth::user()->id;

		// Validation
		$validator = Validator::make($request->all(), [
			'unit' => 'required|integer',
			'id' => 'required',
		]);

		if ($validator->fails()) {
			$response = array_add(['success' => true], 'content', 'Maaf, Jumlah Produk harus berupa Angka');
			return json_encode($response);
		}

		// Validation Unit
		if ($unit > 0) {
			// Check Product
			$transactionProduct = TransactionProduct::where('id', $id)
				->first();

			if (empty($transactionProduct)) {
				// Response Not Found
				$response = array_add(['success' => true], 'content', 'Produk Tidak Ditemukan');

				// Return Json
				return json_encode($response);
			}

			$transactionId = $transactionProduct->transaction_id;

			// Transaction User Validation
			if ($transactionProduct->transaction->user_id == $userId) {

				// Product Stock Refresh
				if ($transactionProduct->checkout_id > 0) {

					// Product Stock Update
					DB::beginTransaction();

					$transactionProductRefresh = TransactionProduct::where('transaction_id', $transactionId)
						->where('checkout_id', '>', 0)
						->where('status', 0)
						->get();

					foreach ($transactionProductRefresh as $transaction) {
						$products = Product::where('id', $transaction->product_id)
							->first();

						$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
							->where('product_id', $transaction->product_id)
							->where('status', 0)
							->update([
								'checkout_id' => null
							]);

						$productStock = ($products->stock + $transaction->unit);

						$productUpdate = Product::where('id', $transaction->product_id)
							->update([
								'stock' => $productStock
							]);
					}

					$checkoutList = TransactionCheckout::get();

					foreach ($checkoutList as $checkout) {
						$transactionProductRefresh = TransactionProduct::where('checkout_id', $checkout->id)
							->first();

						if (empty($transactionProductRefresh)) {
							$transactionCheckoutDelete = TransactionCheckout::where('id', $transaction->checkout_id)
								->delete();
						}
					}

					DB::commit();
				}

				// Product Data Check
				$products = Product::where('id', $transactionProduct->product_id)
					->first();

				// Product Stock
				if ($unit > 1) {
					// Flash Sale
					if ($products->sale == 1) {
						// Response Maximum Item
						$response = array_add(['success' => true], 'content', 'Maaf, Anda hanya dapat Membeli Maksimal 1 Produk Sale');

						// Return Json
						return json_encode($response);
					}

					// Preorder
					if ($products->preorder == 1) {
						// Response Maximum Item
						$response = array_add(['success' => true], 'content', 'Maaf, Anda hanya dapat Membeli Maksimal 1 Produk Group Buy');

						// Return Json
						return json_encode($response);
					}
				}

				// Product Stock Not Available
				if ($unit > $products->stock) {
					// Response Minimum Item
					$response = array_add(['success' => true], 'content', 'Maaf, stok produk tidak cukup!! Hanya tersedia ' . $products->stock . ' produk saja');

					// Return Json
					return json_encode($response);
				}

				// Product Stock Update Unit
				$transactionProductUpdate = TransactionProduct::where('id', $id)
					->where('status', 0)
					->update([
						'unit' => $unit
					]);

				$storeId = $transactionProduct->user_id;
				$transactionProductPrice = 0;
				$transactionId = $transactionProduct->transaction_id;

				// Product Price Count
				$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
					->where('user_id', $storeId)
					->get();

				foreach ($transactionProductList as $productList) {
					$transactionProductPrice += ($productList->unit * $productList->price);

					// Point
					//$transactionProductPrice -= ($productList->point * $productList->point_price);
				}

				// Broadcast
				event(new CounterNotification($userId));

				// Product Response Success
				$response = array_add(['success' => true], 'content', 'Rp ' . number_format($transactionProductPrice, 0, ',', '.'));

				// Return Json
				return json_encode($response);
			}
		}

		// Response Validation
		$response = array_add(['success' => true], 'content', 'Maaf, Anda hanya dapat Membeli Minimal 1 Produk');

		// Return Json
		return json_encode($response);
	}
	public function notes(Request $request)
	{
		// Initialization
		$id = $request->id;
		$notes = $request->notes;
		$user_id = Auth::user()->id;

		// Validation
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);

		if ($validator->fails()) {
			return 'false';
		}

		// Null
		if (empty($notes)) {
			$notes = null;
		}

		// Check
		$transactionProduct = TransactionProduct::where('id', $id)->first();

		if (!empty($transactionProduct)) {
			// Update
			if ($transactionProduct->transaction->user_id == $user_id) {
				$transactionProductUpdate = TransactionProduct::where('id', $id)->update(['notes' => $notes]);
			}
		}

		return 'true';
	}
	public function address(Request $request)
	{
		// Initialization
		$data = $request->data;
		$address = $request->address;
		$userId = Auth::user()->id;

		// Validation
		if ($address == "") {
			$address = null;
		}

		// Check
		$userAddress = UserAddress::where('id', $address)
			->first();

		if (empty($userAddress)) {
			// Return Boolean
			return 'false';
		}

		if ($userAddress->user_id == $userId) {

			$transactionAddressCheck = TransactionAddress::where('transaction_id', $data)
				->first();

			if (!empty($transactionAddressCheck)) {
				$transactionAddressUpdate = TransactionAddress::where('transaction_id', $data)
					->update([
						'address_id' => $address,
						'address_name' => $userAddress->address_name,
						'first_name' => $userAddress->first_name,
						'last_name' => $userAddress->last_name,
						'phone' => $userAddress->phone,
						'provinsi_id' => $userAddress->provinsi_id,
						'kabupaten_id' => $userAddress->kabupaten_id,
						'kecamatan_id' => $userAddress->kecamatan_id,
						'address' => $userAddress->address,
						'postal_code' => $userAddress->postal_code
					]);

				$transactionAddressId = $transactionAddressCheck->id;

				$transactionUpdate = Transaction::where('id', $data)
					->where('user_id', $userId)
					->update([
						'address_id' => $transactionAddressId
					]);

				// Return Boolean
				return 'true';
			}

			$transactionAddress = new TransactionAddress;
			$transactionAddress->transaction_id = $data;
			$transactionAddress->address_id = $address;
			$transactionAddress->address_name = $userAddress->address_name;
			$transactionAddress->first_name = $userAddress->first_name;
			$transactionAddress->last_name = $userAddress->last_name;
			$transactionAddress->phone = $userAddress->phone;
			$transactionAddress->provinsi_id = $userAddress->provinsi_id;
			$transactionAddress->kabupaten_id = $userAddress->kabupaten_id;
			$transactionAddress->kecamatan_id = $userAddress->kecamatan_id;
			$transactionAddress->address = $userAddress->address;
			$transactionAddress->postal_code = $userAddress->postal_code;
			$transactionAddress->save();

			$transactionAddressId = $transactionAddress->id;

			$transactionUpdate = Transaction::where('id', $data)
				->where('user_id', $userId)
				->update([
					'address_id' => $transactionAddressId
				]);

			// Return Boolean
			return 'true';
		}
	}

	public function balancePayment(Request $request)
	{
		// dd($request);

		// Initialization
		$transactionId = $request->id;

		// Check
		$transaction = Transaction::where('id', $transactionId)
			->first();

		if (empty($transaction)) {
			// Return Redirect
			return redirect()
				->route('gateway')
				->with('warning', 'Transaksi Tidak di Temukan');
		}

		// Transaction Product ////////////////////////////////////////////////////////////////////////////////////////

		// Populate items
		$items = null;

		// Transaction Shipping - Total Price
		$transactionShippingPrice = 0;
		$transactionShippingList = TransactionShipping::where('transaction_id', $transactionId)
			->get();

		foreach ($transactionShippingList as $shippingList) {
			$transactionShippingPrice += $shippingList->price;
			$shippingDescription = str_replace(' - Rp ' . number_format($shippingList->price, 0, ",", "."), '', $shippingList->description);

			$shippingName = $shippingDescription . ' - dari (' . $shippingList->user->name . ')';

			$items[] = array('id' => 'MALLSHIPPING' . $shippingList->id, 'price' => $shippingList->price, 'quantity' => 1, 'name' => str_limit($shippingName, 40));
		}

		// Transaction Product - Total Price
		$transactionProductPrice = 0;
		$transactionProductPoint = 0;
		$transactionProductPointPrice = 0;
		$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)->get();

		foreach ($transactionProductList as $productList) {
			// Product
			$items[] = array('id' => $productList->product_id, 'price' => $productList->price, 'quantity' => $productList->unit, 'name' => str_limit($productList->product->name, 40));

			$transactionProductPrice += ($productList->price * $productList->unit);

			// Point
			if ($productList->point > 0) {
				$transactionProductPoint += $productList->point;
				$transactionProductPointPrice += ($productList->point * $productList->point_price);

				$items[] = array('id' => 'POINT' . $productList->product_id, 'price' => -$productList->point_price, 'quantity' => $productList->point, 'name' => 'MSP Point');
			}
		}


		// Transaction Promo - Total Price
		$transactionPromoPrice = 0;
		$transactionPromoList = TransactionPromo::where('transaction_id', $transactionId)
			->get();

		foreach ($transactionPromoList as $promoList) {
			$transactionPromoPrice += $promoList->price;

			$items[] = array('id' => 'PROMO' . $promoList->id, 'price' => -$promoList->price, 'quantity' => 1, 'name' => str_limit($promoList->name, 40));
		}

		// Transaction ID - Total Price
		$orderId = $transactionId;

		$gross_amount = ($transactionProductPrice + $transactionShippingPrice - $transactionProductPointPrice - $transactionPromoPrice);
		$resultContent = '';
		$now = Carbon::now();
		$now = $now->toDateTimeString();

		$array = array(
			'transaction_id' => $transactionId,
			'gateway_id' => 2,
			'status_code' => 200,
			'status_message' => 'Success',
			'gross_amount' => $gross_amount,
			'transaction_time' => $now,
			'transaction_status' => 'settlement',
			'payment_type' => 'balance',
			'fraud_status' => 'accept',
			'finish_redirect_url' => '',
		);

		$result = (object) $array;

		// Transaction Payment ////////////////////////////////////////////////////////////////////////////////////////

		// Transaction Payment - Check Availability
		$transactionPaymentCheck = TransactionPayment::where('user_id', Auth::user()->id)
			->where('order_id', $orderId)
			->first();

		// Begin Transaction
		DB::beginTransaction();

		// Transaction Payment - Create
		if (empty($transactionPaymentCheck)) {
			// Transaction Payment History
			$transactionPaymentHistory = new TransactionPaymentHistory;

			$transactionPaymentHistory->user_id = Auth::user()->id;
			$transactionPaymentHistory->gateway_id = $result->gateway_id;
			$transactionPaymentHistory->status_code = $result->status_code;
			$transactionPaymentHistory->status_message = $result->status_message;
			$transactionPaymentHistory->transaction_id = $transactionId;
			$transactionPaymentHistory->order_id = $orderId;
			$transactionPaymentHistory->gross_amount = $result->gross_amount;

			$transactionPaymentHistory->payment_type = $result->payment_type;
			$transactionPaymentHistory->transaction_time = $result->transaction_time;
			$transactionPaymentHistory->transaction_status = $result->transaction_status;

			if (!empty($result->fraud_status)) {
				$transactionPaymentHistory->fraud_status = $result->fraud_status;
			}

			$transactionPaymentHistory->finish_redirect_url = $result->finish_redirect_url;
			$transactionPaymentHistory->result = $resultContent;
			$transactionPaymentHistory->save();

			// Transaction Payment
			$transactionPayment = new TransactionPayment;

			$transactionPayment->user_id = Auth::user()->id;
			$transactionPayment->gateway_id = $result->gateway_id;
			$transactionPayment->status_code = $result->status_code;
			$transactionPayment->status_message = $result->status_message;
			$transactionPayment->transaction_id = $transactionId;
			$transactionPayment->order_id = $orderId;
			$transactionPayment->gross_amount = $result->gross_amount;

			$transactionPayment->payment_type = $result->payment_type;
			$transactionPayment->transaction_time = $result->transaction_time;
			$transactionPayment->transaction_status = $result->transaction_status;

			if (!empty($result->fraud_status)) {
				$transactionPayment->fraud_status = $result->fraud_status;
			}

			$transactionPayment->finish_redirect_url = $result->finish_redirect_url;
			$transactionPayment->result = $resultContent;
			$transactionPayment->save();

			// Transaction Payment ID
			$transactionPaymentId = $transactionPayment->id;
		}

		// Transaction Payment - Update
		if (!empty($transactionPaymentCheck)) {
			// Transaction Payment History
			$transactionPaymentHistory = new TransactionPaymentHistory;

			$transactionPaymentHistory->user_id = Auth::user()->id;
			$transactionPaymentHistory->gateway_id = $result->gateway_id;
			$transactionPaymentHistory->status_code = $result->status_code;
			$transactionPaymentHistory->status_message = $result->status_message;
			$transactionPaymentHistory->transaction_id = $transactionId;
			$transactionPaymentHistory->order_id = $orderId;
			$transactionPaymentHistory->gross_amount = $result->gross_amount;

			$transactionPaymentHistory->payment_type = $result->payment_type;
			$transactionPaymentHistory->transaction_time = $result->transaction_time;
			$transactionPaymentHistory->transaction_status = $result->transaction_status;

			if (!empty($result->fraud_status)) {
				$transactionPaymentHistory->fraud_status = $result->fraud_status;
			}

			$transactionPaymentHistory->finish_redirect_url = $result->finish_redirect_url;
			$transactionPaymentHistory->result = $resultContent;
			$transactionPaymentHistory->save();

			// Transaction Payment
			$transactionPayment = TransactionPayment::where('user_id', Auth::user()->id)
				->where('order_id', $orderId)
				->update([
					'status_code' => $result->status_code,
					'status_message' => $result->status_message,
					'transaction_id' => $result->transaction_id,
					'gross_amount' => $result->gross_amount,
					'payment_type' => $result->payment_type,
					'transaction_time' => $result->transaction_time,
					'transaction_status' => $result->transaction_status,
					'finish_redirect_url' => $result->finish_redirect_url,
					'result' => $resultContent
				]);

			// Transaction Payment ID
			$transactionPaymentId = $transactionPaymentCheck->id;
		}

		// Status Payment /////////////////////////////////////////////////////////////////////////////////////////////

		// Transaction - Update Transaction Payment ID
		$transactionUpdate = Transaction::where('id', $orderId)
			->where('user_id', Auth::user()->id)
			->update([
				'payment_id' => $transactionPaymentId
			]);

		// Transaction Update Status
		$transactionProductStatus = TransactionProduct::where('transaction_id', $orderId)
			->where('status', '0')
			->update([
				'status' => '1'
			]);

		// Balance Payment ////////////////////////////////////////////////////////////////////////////////////////////

		// Check Balance
		$myBalance = new BalanceController;
		$myBalance = $myBalance->myBalance();
		// $myBalance = $this->myBalance();

		if ($myBalance < $gross_amount) {
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, Saldo Anda Tidak Mencukupi untuk Melakukan Pembayaran');
		}

		$buyerId = Auth::user()->id;

		// Balance Transaction Create Plus
		$balanceTransaction = new BalanceTransaction;
		$balanceTransaction->transaction_id = $transactionId;
		$balanceTransaction->user_id = $buyerId;
		$balanceTransaction->seller_id = null;
		$balanceTransaction->status = 0;
		$balanceTransaction->save();

		$balanceTransactionId = $balanceTransaction->id;

		$balanceNew = new Balance;
		$balanceNew->user_id = $buyerId;
		$balanceNew->transaction_id = $balanceTransactionId;
		$balanceNew->notes = 'Pembelian Produk';
		$balanceNew->save();

		DB::commit();

		// // Initialization
		// $transactionTitle = "Transaksi Sukses";
		// $transactionMessage = "Transaksi dengan Kode: <b>" . $orderId . "</b> telah berhasil menggunakan <b>Saldo</b>";
		// $transactionView = 'transaction.success';

		// // Transaction Payment View
		// return view($transactionView)->with([
		// 	'pageTitle' => $transactionTitle,
		// 	'transactionCode' => $orderId,
		// 	'transactionTotal' => $gross_amount,
		// 	'transactionMessage' => $transactionMessage
		// ]);

		// Return Redirect
		return redirect()
			->route('transaction.detail', ['id' => $transaction->product[0]->id])
			->with('status', 'Selamat! Pembayaran Berhasil');
	}

	// public function myBalance()
	// {
	// 	// Initialization
	// 	$myBalance = 0;

	// 	// Lists
	// 	$balanceData = Balance::where('user_id', Auth::user()->id)
	// 		->orderBy('created_at','asc')
	// 		->get();

	// 	foreach ($balanceData as $balance) {
	// 		if (!empty($balance->deposit)) {
	// 			if ($balance->deposit->status == 1) {
	// 				$transactionPayment = TransactionPayment::where('order_id', $balance->deposit->transaction_id)
	// 					->first();

	// 				if (!empty($transactionPayment)) {
	// 					$myBalance += $transactionPayment->gross_amount;
	// 				}
	// 			}
	// 		}

	// 		if (!empty($balance->withdraw)) {
	// 			$myBalance -= $balance->withdraw->balance;
	// 		}

	// 		if (!empty($balance->ads)) {
	// 			$myBalance -= $balance->ads->balance;
	// 		}

	// 		if (!empty($balance->voucher)) {
	// 			$myBalance += $balance->voucher->price;
	// 		}

	// 		if (!empty($balance->transaction)) {
	// 			if ($balance->transaction->status == 1) {
	// 				// Transaction Product
	// 				$transactionProduct = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
	// 					->where('user_id', $balance->transaction->seller_id)
	// 					->get();

	// 				$totalTransaction = 0;
	// 				foreach ($transactionProduct as $transaction) {
	// 					$totalProduct = ($transaction->unit * $transaction->price);
	// 					$totalTransaction += $totalProduct;
	// 				}

	// 				// Transaction Shipping
	// 				$transactionShipping = TransactionShipping::where('transaction_id', $balance->transaction->transaction_id)
	// 					->where('user_id', $balance->transaction->seller_id)
	// 					->first();

	// 				if (!empty($transactionShipping))
	// 				{
	// 					$totalTransaction += $transactionShipping->price;
	// 				}

	// 				// Transaction Point
	// 				$transactionProductPoint = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
	// 					->where('user_id', $balance->transaction->seller_id)
	// 					->get();

	// 				$totalTransactionPoint = 0;
	// 				foreach ($transactionProductPoint as $transactionPoint) {
	// 					$totalTransactionPoint += ($transactionPoint->point * $transactionPoint->point_price);
	// 				}

	// 				$totalTransaction -= $totalTransactionPoint;

	// 				// Status
	// 				$myBalance += $totalTransaction;
	// 			}

	// 			if ($balance->transaction->status == 0) {
	// 				// Transaction Product
	// 				$transactionProduct = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
	// 					->get();

	// 				$totalTransaction = 0;
	// 				foreach ($transactionProduct as $transaction) {
	// 					$totalProduct = ($transaction->unit * $transaction->price);
	// 					$totalTransaction += $totalProduct;
	// 				}

	// 				// Transaction Shipping
	// 				$transactionShipping = TransactionShipping::where('transaction_id', $balance->transaction->transaction_id)
	// 					->get();

	// 				foreach ($transactionShipping as $shipping) {
	// 					$totalTransaction += $shipping->price;
	// 				}

	// 				// Transaction Point
	// 				$transactionProductPoint = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
	// 					->get();

	// 				$totalTransactionPoint = 0;
	// 				foreach ($transactionProductPoint as $transactionPoint) {
	// 					$totalTransactionPoint += ($transactionPoint->point * $transactionPoint->point_price);
	// 				}

	// 				$totalTransaction -= $totalTransactionPoint;

	// 				// Status
	// 				$myBalance -= $totalTransaction;
	// 			}
	// 		}
	// 	}

	// 	// Return Integer
	// 	return $myBalance;
	// }

	public function chooseGateway(Request $request)
	{
		// Initialization
		$id = $request->id;
		$gateway = $request->gateway;
		$userId = Auth::user()->id;

		// Validation
		if (empty($gateway)) {
			// Return Json
			$response = array_add(['success' => false], 'content', 'Pilihan Metode Pembayaran Tidak di Temukan');
			return json_encode($response);
		}

		// Check
		$transaction = Transaction::where('id', $id)
			->first();

		if (empty($transaction)) {
			// Return Json
			$response = array_add(['success' => false], 'content', 'Transaksi Tidak di Temukan');
			return json_encode($response);
		}

		$transactionUpdate = Transaction::where('id', $id)
			->where('user_id', $userId)
			->update([
				'gateway_id' => $gateway
			]);

		// Return Json
		$response = array_add(['success' => true], 'content', $gateway);
		return json_encode($response);
	}
	public function shipping(Request $request)
	{
		// Initialization
		$transaction_id = $request->data;
		$store = $request->store;
		$courier = strtolower($request->courier);
		$service = strtolower($request->service);
		$courierList = ["jne", "tiki", "pos", "mse"];

		if (!in_array($courier, $courierList)) {
			$response = array_add(['success' => false], 'content', 'Courier not available. Please reload the page.');
			return json_encode($response);
		}
		
		// Check
		$getTransactionProduct = TransactionProduct::where('transaction_id', $transaction_id)->where('user_id', $store)->get();
		$transactionProduct = $getTransactionProduct->first();
		$transactionAddress = TransactionAddress::where('transaction_id', $transaction_id)->first();

		if (!$transactionProduct || !$transactionAddress) {
			$response = array_add(['success' => false], 'content', 'Something wrong with the transaction. Please reload the page.');
			return json_encode($response);
		}

		// Add Shipping Method to List
		$shippingweightCount = 0;
		foreach ($getTransactionProduct as $key => $transProduct) {
			$shippingweightCount += ($transProduct->product->weight * $transProduct->unit);
		}
		// Initial Price & Description Value
		$price = 0;
		$description = '';
		$serviceAvailable = false;

		if ($courier == 'mse') {
			$summary = TransactionProduct::getCalculateTransactionProductsByTransIdAndSellerId($transaction_id, $store);
			$express = new Shipping\PricingController;
			$dataOngkir = $express->json(
				$transactionAddress->kabupaten_id,
				$transactionAddress->kecamatan_id,
				$transactionAddress->postal_code,
				$summary->total
			);
			$dataOngkir = $dataOngkir['items']['results'];
			foreach ($dataOngkir as $key => $value) {
				if (strtolower($value['code']) == $courier) {
					foreach ($value['costs'] as $key => $val) {
						if (strtolower($val['service']) == $service) {
							$serviceAvailable = true;
							$price = $val['value'];
							$description = 'MSE ' . $val['service'] . ' ';
							if (!empty($ongkirCostTime)) {
								$description .= '(' . $val['etd'] . ' Hari Kerja)';
							}
							$description .= ' - Rp ' . number_format($price, 0, ",", ".");
						}
					}
				}
			}
		} else {
			try {
				// Shipping Method Check
				$kotaAsal = $transactionProduct->user->kabupaten->name;
				$kotaTujuan = $transactionAddress->kabupaten->name;

				// Check Origin
				$dataKota = str_replace('Kab.', 'Kab', $kotaAsal);
				$dataKota = str_replace('Kabupaten', 'Kab', $dataKota);
				$dataNamaKota = trim(str_replace('Kota', '', str_replace('Kab', '', $dataKota)));
				$dataNamaType = str_replace(' ', '', str_replace($dataNamaKota, '', $dataKota));
		
				$origin = RajaOngkir::Kota()
					->search('city_name', $dataNamaKota)
					->search('type', $dataNamaType)
					->get();
		
				// Check Destination
				$dataKota = str_replace('Kab.', 'Kab', $kotaTujuan);
				$dataKota = str_replace('Kabupaten', 'Kab', $dataKota);
				$dataNamaKota = trim(str_replace('Kota', '', str_replace('Kab', '', $dataKota)));
				$dataNamaType = str_replace(' ', '', str_replace($dataNamaKota, '', $dataKota));
		
				$destination = RajaOngkir::Kota()
					->search('city_name', $dataNamaKota)
					->search('type', $dataNamaType)
					->get();
		
				// Check Cost - JNE
				$dataOngkir = RajaOngkir::Cost([
					'origin'        => $origin[0]['city_id'], // id kota asal
					'destination'   => $destination[0]['city_id'], // id kota tujuan
					'weight'        => $shippingweightCount, // berat satuan gram
					'courier'       => $courier, // kode kurir pengantar ( jne / tiki / pos )
		
					'originType'		=> 'city', // cakupan lokasi ( city / subdistrict )
					'destinationType'	=> 'city', // cakupan lokasi ( city / subdistrict )
				])->get();

				foreach ($dataOngkir as $key => $value) {
					if (strtolower($value['code']) == $courier) {
						foreach ($value['costs'] as $key => $val) {
							if (strtolower($val['service']) == $service) {
								$serviceAvailable = true;
								$price = $val['cost'][0]['value'];
								$description = strtoupper($courier) . ' ' . $val['service'] . ' ';
								if (!empty($ongkirCostTime)) {
									$description .= '(' . $val['etd'] . ' Hari Kerja)';
								}
								$description .= ' - Rp ' . number_format($price, 0, ",", ".");
							}
						}
					}
				}
			} catch (\Throwable $th) {
				$response = array_add(['success' => false], 'content', $th->getMessage());
				return json_encode($response);
			}
		}

		// Checking if service available
		if (!$serviceAvailable) {
			$response = array_add(['success' => false], 'content', 'Courier service not available. Please reload the page.');
			return json_encode($response);
		}

		if ($transactionProduct->transaction->user_id == Auth::user()->id) {

			$TransactionShipping = TransactionShipping::where('transaction_id', $transaction_id)
				->where('user_id', $store)
				->first();

			if (!empty($TransactionShipping)) {
				$transactionShippingUpdate = TransactionShipping::where('transaction_id', $transaction_id)
					->where('user_id', $store)
					->update([
						'description' => $description,
						'price' => $price
					]);

				$storeId = $store;
				$transactionProductPrice = 0;
				$transactionId = $transaction_id;

				// Product Price Count
				$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
					->where('user_id', $storeId)
					->get();
				foreach ($transactionProductList as $productList) {
					$transactionProductPrice += ($productList->unit * $productList->price);

					// Point
					// $transactionProductPrice -= ($productList->point * $productList->point_price);
				}

				// Return Json
				$response = array_add(['success' => true], 'content', 'Rp ' . number_format(($transactionProductPrice + $price), 0, ',', '.'));
				return json_encode($response);
			}

			$transactionShippingNew = new TransactionShipping;
			$transactionShippingNew->transaction_id = $transaction_id;
			$transactionShippingNew->user_id = $store;
			$transactionShippingNew->description = $description;
			$transactionShippingNew->price = $price;
			$transactionShippingNew->save();

			$storeId = $store;
			$transactionProductPrice = 0;
			$transactionId = $transaction_id;

			// Product Price Count
			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('user_id', $storeId)
				->get();

			foreach ($transactionProductList as $productList) {
				$transactionProductPrice += ($productList->unit * $productList->price);

				// Point
				// $transactionProductPrice -= ($productList->point * $productList->point_price);
			}

			// Return Json
			$response = array_add(['success' => true], 'content', 'Rp ' . number_format(($transactionProductPrice + $price), 0, ',', '.'));
			return json_encode($response);
		}
	}

	public function deleteCart(Request $request)
	{
		// Initialization
		$id = $request->id;
		$userId = Auth::user()->id;

		DB::beginTransaction();
		// Transaction Product Check
		$transactionProduct = TransactionProduct::where('id', $id)
			->first();

		// Validation
		if (empty($transactionProduct)) {
			return redirect('/');
		}

		// Transaction User Check
		if ($transactionProduct->transaction->user_id == $userId) {

			if (!empty($transactionProduct->product_id)) {
				$productId = $transactionProduct->product_id;

				// Product Stock Restore
				if ($transactionProduct->checkout_id > 0) {
					$products = Product::where('id', $productId)
						->first();

					$productStock = ($products->stock + $transactionProduct->unit);

					$productUpdate = Product::where('id', $productId)
						->update([
							'stock' => $productStock
						]);
				}
			}

			// Transaction Product Delete
			$deleteCart = TransactionProduct::where('id', $id)
				->delete();
		}
		DB::commit();

		// Broadcast
		event(new CounterNotification($userId));

		// Return Redirect
		return redirect()
			->route('cart')
			->with('status', 'Produk telah berhasil di Hapus dari Keranjang Belanja anda.');
	}
	public function addCart(Request $request)
	{
		// Initialization
		$productId = $request->id;
		$redirect = $request->redirect;
		$userId = Auth::user()->id;

		// Check
		$productCheck = Product::where('id', $productId)
			->where('status', 1)
			->first();

		if (empty($productCheck)) {
			// Return Redirect
			return redirect('/');
		}

		if ($productCheck->stock < 1) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Stok Produk tidak mencukupi.');
		}

		if ($productCheck->user_id == $userId) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Anda tidak dapat membeli Produk Anda sendiri.');
		}

		// Transaction
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			$transaction = new Transaction;
			$transaction->user_id = $userId;
			$transaction->save();
		}

		$transactionId = $transaction->id;

		DB::beginTransaction();

		// Transaction Product - Checkout
		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', '>', 0)
			->where('status', 0)
			->get();

		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)->first();

			$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
				->where('product_id', $transaction->product_id)
				->where('status', 0)
				->update([
					'checkout_id' => null
				]);

			if (!empty($products)) {
				$productStock = ($products->stock + $transaction->unit);

				$productUpdate = Product::where('id', $transaction->product_id)
					->update([
						'stock' => $productStock
					]);
			}
		}

		$checkoutList = TransactionCheckout::get();
		foreach ($checkoutList as $checkout) {
			$transactionProduct = TransactionProduct::where('checkout_id', $checkout->id)
				->first();

			if (empty($transactionProduct)) {
				$transactionCheckoutDelete = TransactionCheckout::where('id', $transaction->checkout_id)
					->delete();
			}
		}

		DB::commit();

		// Transaction Product Unit Update /////////////////////////////////////////////////////
		// Transaction Product Check
		$product = TransactionProduct::where('transaction_id', $transactionId)
			->where('product_id', $productId)
			->first();

		// Stock Update
		if (!empty($product)) {
			// Sale Limit
			if ($product->product->sale == 1) {
				// Return Redirect
				return redirect()
					->route('product.detail', ['slug' => $productCheck->slug])
					->with('warning', 'Maaf, Anda hanya dapat Membeli Maksimal 1 Produk Sale.');
			}

			// Stock Limit
			$productStock = ($product->unit + 1);

			if ($productStock > $productCheck->stock) {
				// Return Redirect
				return redirect()
					->route('product.detail', ['slug' => $productCheck->slug])
					->with('warning', 'Maaf, Stok Produk tidak mencukupi untuk ditambahkan.');
			}

			// Stock Update
			$update = TransactionProduct::where('transaction_id', $transactionId)
				->where('product_id', $productId)
				->where('status', 0)
				->update([
					'unit' => $productStock
				]);

			// Broadcast
			event(new CounterNotification($userId));

			// Return Redirect
			if (!empty($redirect)) {
				return redirect()
					->route('cart')
					->with('status', 'Selamat!! Stok Produk telah berhasil di tambahkan pada keranjang belanja.');
			}

			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('status', 'Selamat!! Stok Produk telah berhasil di tambahkan pada keranjang belanja.');
		}

		// Transaction Product Insert //////////////////////////////////////////////////////////
		// Sale Transaction Limit
		$productTransaction = TransactionProduct::where('transaction_id', '!=', $transactionId)
			->whereHas('transaction', function ($q) {
				$q->where('user_id', Auth::user()->id);
			})
			->where('product_id', $productId)
			->first();

		if (!empty($productTransaction)) {
			if ($productTransaction->product->sale == 1) {
				// Return Redirect
				return redirect()
					->route('product.detail', ['slug' => $productCheck->slug])
					->with('warning', 'Maaf, Anda hanya dapat Membeli Maksimal 1 Kali Produk Sale.');
			}
		}

		// Insert Product
		if (empty($product)) {
			$insert = new TransactionProduct;
			$insert->transaction_id = $transactionId;
			$insert->user_id = $productCheck->user_id;
			$insert->product_id = $productId;
			$insert->unit = 1;
			$insert->name = $productCheck->name;
			$insert->price = $productCheck->price;
			$insert->save();
		}

		// Broadcast
		event(new CounterNotification($userId));

		// Return Redirect
		if (!empty($redirect)) {
			return redirect()
				->route('cart')
				->with('status', 'Selamat!! Produk telah berhasil di tambahkan pada keranjang belanja.');
		}

		return redirect()
			->route('product.detail', ['slug' => $productCheck->slug])
			->with('status', 'Selamat!! Produk telah berhasil di tambahkan pada keranjang belanja.');
	}
	public function buyCart(Request $request)
	{
		// Initialization
		$productId = $request->id;
		$userId = Auth::user()->id;

		// Check
		$productCheck = Product::where('id', $productId)
			->where('status', 1)
			->first();

		if (empty($productCheck)) {
			// Return Redirect
			return redirect('/');
		}

		if ($productCheck->stock < 1) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Stok Produk tidak mencukupi.');
		}

		if ($productCheck->user_id == $userId) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Anda tidak dapat membeli Produk Anda sendiri.');
		}

		// Transaction
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			$transaction = new Transaction;
			$transaction->user_id = $userId;
			$transaction->save();
		}

		$transactionId = $transaction->id;

		DB::beginTransaction();

		// Transaction Product - Checkout
		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', '>', 0)
			->where('status', 0)
			->get();

		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)->first();

			$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
				->where('product_id', $transaction->product_id)
				->where('status', 0)
				->update([
					'checkout_id' => null
				]);

			if (!empty($products)) {
				$productStock = ($products->stock + $transaction->unit);

				$productUpdate = Product::where('id', $transaction->product_id)
					->update([
						'stock' => $productStock
					]);
			}
		}

		$checkoutList = TransactionCheckout::get();
		foreach ($checkoutList as $checkout) {
			$transactionProduct = TransactionProduct::where('checkout_id', $checkout->id)
				->first();

			if (empty($transactionProduct)) {
				$transactionCheckoutDelete = TransactionCheckout::where('id', $transaction->checkout_id)
					->delete();
			}
		}

		DB::commit();

		// Transaction Product Delete
		$product = TransactionProduct::where('transaction_id', $transactionId)
			->delete();

		// Transaction Product Insert //////////////////////////////////////////////////////////
		// Sale Transaction Limit
		$productTransaction = TransactionProduct::where('transaction_id', '!=', $transactionId)
			->whereHas('transaction', function ($q) {
				$q->where('user_id', Auth::user()->id);
			})
			->where('product_id', $productId)
			->first();

		if (!empty($productTransaction)) {
			if ($productTransaction->product->sale == 1) {
				// Return Redirect
				return redirect()
					->route('product.detail', ['slug' => $productCheck->slug])
					->with('warning', 'Maaf, Anda hanya dapat Membeli Maksimal 1 Kali Produk Sale.');
			}
		}

		// Insert Product
		$insert = new TransactionProduct;
		$insert->transaction_id = $transactionId;
		$insert->user_id = $productCheck->user_id;
		$insert->product_id = $productId;
		$insert->unit = 1;
		$insert->name = $productCheck->name;
		$insert->price = $productCheck->price;
		$insert->save();

		// Broadcast
		event(new CounterNotification($userId));

		// Return Redirect
		return redirect()
			->route('checkout');
	}
	public function preorderCart(Request $request)
	{
		// Initialization
		$productId = $request->id;
		$userId = Auth::user()->id;

		// Check
		$productCheck = Product::where('id', $productId)
			->where('status', 1)
			->whereNotNull('preorder')
			->first();

		if (empty($productCheck)) {
			// Return Redirect
			return redirect('/');
		}

		if ($productCheck->stock < 1) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Stok Produk tidak mencukupi.');
		}

		if ($productCheck->preorder_expired < Carbon::now()->format('Y-m-d')) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Masa Group Buy Telah Habis.');
		}

		if ($productCheck->user_id == $userId) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Anda tidak dapat membeli Produk Anda sendiri.');
		}

		// Transaction
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			$transaction = new Transaction;
			$transaction->user_id = $userId;
			$transaction->save();
		}

		$transactionId = $transaction->id;

		DB::beginTransaction();

		// Transaction Product - Checkout
		$transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
			->where('checkout_id', '>', 0)
			->where('status', 0)
			->get();

		foreach ($transactionProduct as $transaction) {
			$products = Product::where('id', $transaction->product_id)->first();

			$transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionId)
				->where('product_id', $transaction->product_id)
				->where('status', 0)
				->update([
					'checkout_id' => null
				]);

			if (!empty($products)) {
				$productStock = ($products->stock + $transaction->unit);

				$productUpdate = Product::where('id', $transaction->product_id)
					->update([
						'stock' => $productStock
					]);
			}
		}

		$checkoutList = TransactionCheckout::get();
		foreach ($checkoutList as $checkout) {
			$transactionProduct = TransactionProduct::where('checkout_id', $checkout->id)
				->first();

			if (empty($transactionProduct)) {
				$transactionCheckoutDelete = TransactionCheckout::where('id', $transaction->checkout_id)
					->delete();
			}
		}

		DB::commit();

		// Transaction Product Delete
		$product = TransactionProduct::where('transaction_id', $transactionId)
			->delete();

		// Transaction Product Check
		$transactionProductCheck = TransactionProduct::whereHas('transaction', function ($q) {
			$q->where('user_id', Auth::user()->id);
		})
			->where('product_id', $productCheck->id)
			->first();

		if (!empty($transactionProductCheck)) {
			// Return Redirect
			return redirect()
				->route('product.detail', ['slug' => $productCheck->slug])
				->with('warning', 'Maaf, Anda hanya dapat membeli Produk Group Buy 1 Kali saja.');
		}

		// Insert Product
		$insert = new TransactionProduct;
		$insert->transaction_id = $transactionId;
		$insert->user_id = $productCheck->user_id;
		$insert->product_id = $productId;
		$insert->unit = 1;
		$insert->name = $productCheck->name;
		$insert->price = $productCheck->price;
		$insert->preorder = 1;
		$insert->save();

		// Broadcast
		event(new CounterNotification($userId));

		// Return Redirect
		return redirect()
			->route('checkout');
	}
	public function promo(Request $request)
	{
		// Initialization
		$totalStore = 0;
		$totalProduct = 0;
		$totalPrice = 0;
		$totalProductPrice = 0;
		$totalShippingPrice = 0;

		$code = $request->code;
		$userId = Auth::user()->id;
		$totalPromoPrice = 0;

		// Check
		$promo = Promo::where('code', $code)
			->where('expired', '>', Carbon::now()->format('Y-m-d H:i:s'))
			// ->where(function($q) {
			// 	$q->where('type_id', 1)
			// 	  ->orWhere('type_id', 2);
			// })
			->first();
		if (empty($promo)) {
			// Return Redirect
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, Kode Promo yang anda masukkan Sudah Tidak Tersedia.');
		}


		// Transaction Available
		$transaction = Transaction::where('user_id', $userId)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
			->first();

		if (empty($transaction)) {
			// Return Redirect
			return redirect('/');
		}

		$transactionId = $transaction->id;

		// Transaction Product List
		$transactionProductNew = array();

		$transactionProductSeller = TransactionProduct::where('transaction_id', $transactionId)
			->where('status', 0)
			->groupBy('user_id')
			->get();

		//check promo product type
		if (count($promo->product_type) < 1) {

			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, Kode Promo yang anda masukkan Tidak Tersedia untuk product tersebut.');
		} else {
			$promoType = $promo->product_type->whereIn('id', [$transactionProductSeller[0]->product->type->id]);
			if (count($promoType) < 1) {
				return redirect()
					->route('gateway')
					->with('warning', 'Maaf, Kode Promo yang anda masukkan Tidak Tersedia untuk product tersebut.');
			}
			if ($promoType[0]->id != $transactionProductSeller[0]->product->type->id) {
				return redirect()
					->route('gateway')
					->with('warning', 'Maaf, Kode Promo yang anda masukkan Tidak Tersedia untuk product tersebut.');
			}
		}

		foreach ($transactionProductSeller as $transactionSeller) {
			$totalStore += 1;

			$transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
				->where('status', 0)
				->where('user_id', $transactionSeller->user_id)
				->get();

			foreach ($transactionProductList as $transactionProduct) {
				$totalProduct += $transactionProduct->unit;
				$totalProductPrice += ($transactionProduct->unit * $transactionProduct->price);

				// Point
				$totalProductPrice -= ($transactionProduct->point * $transactionProduct->point_price);
			}
		}

		$transactionShippingList = TransactionShipping::where('transaction_id', $transactionId)
			->get();

		foreach ($transactionShippingList as $shippingList) {
			/*
			// Promo Shipping
			if ($promo->type_id == 1)
			{
				$shippingCode = substr($shippingList->description, 0, 3);
				//if ($shippingCode == $promo->shipping_code)
				//{
					$totalShippingProductPrice = 0;

					foreach ($shippingList->transaction->product as $shippingProduct)
					{
						$totalShippingProductPrice += ($shippingProduct->unit * $shippingProduct->price);

						// Point
						$totalShippingProductPrice -= ($shippingProduct->point * $shippingProduct->point_price);
					}
					
					// Promo
					$shippingPromoPrice = $promo->discount_price;

					if ($shippingList->price < $promo->discount_price)
					{
						$shippingPromoPrice = $shippingList->price;
					}
					
					$totalPromoPrice += $shippingPromoPrice;
				//}

				//if ($shippingCode != $promo->shipping_code)
				//{
					// Delete
				//	$delete = TransactionPromo::where('transaction_id', $transactionId)
				//		->where('user_id', Auth::user()->id)
				//		->delete();

					// Return Redirect
				//	return redirect()
				//		->route('gateway')
				//		->with('warning', 'Maaf, Kode Promo hanya dapat digunakan jika anda menggunakan metode pengiriman MSP Express');
				//}
			}
			*/

			// Total Shipping
			$totalShippingPrice += $shippingList->price;
		}

		// Promo Validation
		if ($totalProductPrice < $promo->transaction_min) {
			// Return Redirect
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, Kode Promo hanya dapat digunakan dengan Transaksi Minimal Rp ' . number_format($promo->transaction_min, 0, ',', '.'));
		}

		$quotaTotal = TransactionPromo::where('promo_id', $promo->id)
			->whereHas('transaction', function ($q) {
				$q->whereNotNull('payment_id');
			});
		$quotaUser = TransactionPromo::where('promo_id', $promo->id)
			->whereHas('transaction', function ($q) {
				$q->whereNotNull('payment_id');
			})
			->where('user_id', Auth::user()->id);
		$quota_total = $quotaTotal->count(); //4
		$quota_day = $quotaTotal->whereDate('updated_at', Carbon::today())->count(); //3
		$quota_user_total = $quotaUser->count(); //3
		$quota_user = $quotaUser->whereDate('updated_at', Carbon::today())->count(); // 2

		if ($quota_total >= $promo->total_quota) {
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, Kode promo telah melebihi kuota penggunaan');
		}

		if ($quota_day >= $promo->quota) {
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, Kode Promo telah melebihi Kuota penggunaan per Hari');
		}

		if ($quota_user_total >= $promo->quota_user_total) {
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, User anda telah melebihi Kuota penggunaan');
		}
		if ($quota_user >= $promo->quota_user_day) {
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, User anda telah melebihi Kuota penggunaan per Hari');
		}


		// Promo Shipping
		if ($promo->type_id == 1) {
			$shippingPromoPrice = $totalShippingPrice;

			if ($totalShippingPrice > $promo->discount_price) {
				$shippingPromoPrice = $promo->discount_price;
			}

			$totalPromoPrice = $shippingPromoPrice;
		}

		// Promo Transaction
		if ($promo->type_id == 2) {
			$totalBeforePromo = ($totalProductPrice + $totalShippingPrice);

			$totalPromoPrice = $totalProductPrice * ($promo->discount_percent / 100);

			if ($totalPromoPrice > $promo->discount_max) {
				$totalPromoPrice = $promo->discount_max;
			}
		}

		// Validation
		$transactionPromo = TransactionPromo::where('transaction_id', $transactionId)
			->where('user_id', Auth::user()->id)
			//->where('code', $code)
			->first();

		if (!empty($transactionPromo)) {
			$delete = TransactionPromo::where('id', $transactionPromo->id)
				->where('user_id', Auth::user()->id)
				->delete();
		}

		DB::beginTransaction();

		// Insert
		$insert = new TransactionPromo;
		$insert->transaction_id = $transactionId;
		$insert->user_id = $transaction->user_id;
		$insert->promo_id = $promo->id;
		$insert->type = $promo->type->name;
		$insert->name = $promo->name;
		$insert->code = $promo->code;
		$insert->expired = $promo->expired;
		$insert->price = $totalPromoPrice;
		$insert->save();

		$promoId = $insert->id;

		// Update
		$totalPrice = ($totalProductPrice + $totalShippingPrice - $totalPromoPrice);
		$transactionUpdate = Transaction::where('id', $transactionId)
			->where('user_id', Auth::user()->id)
			->update([
				'promo_id' => $promoId,
				'total' => $totalPrice
			]);

		DB::commit();

		// Return Redirect
		return redirect()
			->route('gateway')
			->with('success', 'Selamat, Kode Promo ' . $promo->name . ' telah berhasil di tambahkan.');
	}
}
