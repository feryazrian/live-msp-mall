<?php

namespace Marketplace\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Marketplace\LifePoint;
use Marketplace\LifePointTransaction;
use Marketplace\Transaction;
use Marketplace\TransactionShipping;
use Marketplace\TransactionProduct;
use Marketplace\TransactionPromo;
use Marketplace\TransactionPayment;
use Marketplace\TransactionGateway;
use Marketplace\User;
use Auth;

class LifePointController extends Controller
{
   
    public function create_new($user)
    {   
        $createLifePoint = LifePoint::create(['total_point'=>0,'user_id'=>$user->id]);
        return $createLifePoint;
    }
    public function get_life_point($user)
    {
        $lifepoint = LifePoint::where('user_id',$user->id)->first();
        if (!$lifepoint) {
            $lifepoint = $this->create_new($user);
        }
        return $lifepoint->total_point;
    }
    public function get_life_data($user)
    {
        $lifepoint = LifePoint::where('user_id',$user->id)->first();
        return $lifepoint;
    }
    public function update_life_point($id,$data)
    {
        $lifepoint = LifePoint::where('id',$id)->update($data);
        return $lifepoint;
    }
    public function create_life_point_transaction($data)
    {
        $lifepointTransaction = LifePointTransaction::create($data); 
        return $lifepointTransaction;  
    }
    public function add_life_point_transaction($data)
    {
        $user = User::where('id',$data['user_id'])->first();
        $lifePointData = $this->get_life_data($user);
        $dataUpdateLifePoint = [
            "total_point"=>$lifePointData->total_point + $data["transaction_point"]
        ];
        $dataLifePointTransaction = [
            "transaction_point"=>$data["transaction_point"],
            "life_point_id"=>$lifePointData->id,
            "point_operator"=>1,
            "status"=>1,        
            "transaction_id"=>$data["transaction_id"],
            "point_transaction_type_id"=>2,
            "user_id"=>$data["user_id"],
            "description"=>$data["description"]

        ];
        $lifepointTransaction = LifePointTransaction::create($dataLifePointTransaction); 
        $lifepoint = LifePoint::where('id',$lifePointData->id)->update($dataUpdateLifePoint);
        return response("OK",200);
           
    }
    public function checkout(Request $request)
    {
        $transaction_id = $request->id;
        $user = Auth::user();
        $transaction = Transaction::where('id', $transaction_id)->first();
        $lifePointData = $this->get_life_data($user);
		if (empty($transaction)) {
			return redirect()
				->route('gateway')
				->with('warning', 'Transaksi Tidak di Temukan');
        }

        $paymentMethod = TransactionGateway::where("id",$request->gateway_id)->first();
        
        // Transaction Shipping
        $transactionShippingList = TransactionShipping::where('transaction_id', $transaction_id)->get();
        $shippingAmount = $transactionShippingList->sum('price');
        $shippingName = $transactionShippingList->implode('description', ' + ');

        $transactionProductList = TransactionProduct::where('transaction_id', $transaction_id)->get();
        $transactionProduct = TransactionProduct::getCalculateTransactionProductsByTransId($transaction_id);
        $transactionProductPrice = (int) $transactionProduct->total;
        $discountAmount = (int) $transactionProduct->point_price;
        $discountName = ($discountAmount > 0) ? 'Penggunaan MSP Point - Rp.' . $discountAmount  . ' + ' : '' ;
        
        $transactionPromo = TransactionPromo::where('transaction_id', $transaction_id)->first();
        if ($transactionPromo) {
            $transactionPromoName = $transactionPromo->name . ' - Rp ' . (int) $transactionPromo->price;
            $discountAmount += (int) $transactionPromo->price;
            $discountItems = [
                "id" => 'discount',
                "name" => $discountName . $transactionPromoName,
                "price" => $discountAmount,
                "quantity" => 1
            ];
        }
        $totalTransaction = $transactionProductPrice+$shippingAmount-$discountAmount;
        $saldo = $lifePointData->total_point;

        if ($saldo < $totalTransaction) {
			return redirect()
				->route('gateway')
				->with('warning', 'Maaf, saldo anda tidak mencukupi untuk melakukan pencairan dengan nominal yang anda masukkan.');
        }
        //data payment 
        $dataPayment = [
            "user_id"=>$user->id,
            "gateway_id"=>$paymentMethod->id,
            "status_code"=>200,
            "status_message"=>"Success",
            "transaction_id"=>$transaction_id,
            "order_id"=>$transaction_id,
            "gross_amount"=>$totalTransaction,
            "payment_type"=>$paymentMethod->slug,
            "transaction_time"=>now(),
            "transaction_status"=>"settlement",
            "fraud_status"=>"accept",
            "finish_redirect_url"=>"",
            "result"=>"",
        ];
        //data Life Point Transaction 
        $dataLifePointTransaction = [
            "transaction_point"=>$totalTransaction,
            "life_point_id"=>$lifePointData->id,
            "point_operator"=>0, //0=minus or positif=1
            "status"=>1,//1 berhasil
            "transaction_id"=>$transaction_id,
            "point_transaction_type_id"=>4, //4 = transaction point
            "user_id"=>$user->id,
            "description"=>"Pembelian Produk"
        ];
        DB::beginTransaction();
        try {

            //create life point transacition 
            $lifepointTransaction = $this->create_life_point_transaction($dataLifePointTransaction);
            
            //calculate life point 
            $dataUpdateLifePoint = [
                "total_point"=>$lifePointData->total_point - $totalTransaction
            ];
            //update total point int table life point
            $lifePointUpdate = $this->update_life_point($lifePointData->id,$dataUpdateLifePoint);
            
            //create transaction payment
            $payment = TransactionPayment::create($dataPayment);
            //update Transaction 
            $transactionUpdate = Transaction::where('id',$transaction_id)
                ->where('user_id',$user->id)
                ->update(['payment_id'=>$payment->id]);

            // Transaction Update Status
            $transactionProductStatus = TransactionProduct::where('transaction_id', $transaction_id)
                ->where('status', '0')
                ->update([
                    'status' => '1'
                ]);
            DB::commit();
            return redirect()
			->route('transaction.detail', ['id' => $transaction->product[0]->id])
			->with('status', 'Selamat! Pembayaran Berhasil');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
				->route('gateway')
				->with('warning', 'Maaf, Terjadi Kesalahan Sistem. '.$e->getMessage());

        }

    }
}
