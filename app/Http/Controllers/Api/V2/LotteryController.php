<?php

namespace Marketplace\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Marketplace\Lottery;

class LotteryController extends Controller
{
    public function list(Request $request)
    {
        // Lottery 
        $lists = Lottery::join('users', 'lottery.user_id', '=', 'users.id')
            ->select(
                'lottery.id',
                'users.id AS user_id',
                'users.name',
                'users.username',
                'users.email',
                'users.photo',
                'users.phone',
                'lottery.status AS lottery_status'
            )
            ->where('users.activated', '=', '1')
            // ->where('lottery.status','=','0')
            ->orderBy('lottery.id', 'DESC')
            ->get();

        // Return Array
        return response()->api(200, 'Data list peserta lottery berhasil ditampilkan', $lists);
    }


    public function update_lottery(Request $request)
    {
        $user_id = $request->user_id;

        // Update Lottery
        $lists = Lottery::where('user_id', $user_id)->first();
        // dd($lists);
        if ($lists->status == 0) {
            $update = Lottery::where('user_id', $user_id)->update([
                'status' =>  1,
            ]);
        } else {
            return response()->api(400, 'User telah masuk ke dalam list pemenang sebelumnya');
        }

        $lists = Lottery::join('users', 'lottery.user_id', '=', 'users.id')
            ->select(
                'lottery.id',
                'users.id AS user_id',
                'users.name',
                'users.username',
                'users.email',
                'users.photo',
                'users.phone',
                'lottery.status AS lottery_status'
            )
            ->where('lottery.user_id', '=', $request->user_id)
            ->first();

        // Return Array
        return response()->api(200, 'User telah masuk ke dalam list pemenang', $lists);
    }
}