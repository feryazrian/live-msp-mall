<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
use Marketplace\Live;

class InhouseController extends Controller
{
	public function live(Request $request)
	{
		// Initialization
		$pageTitle = 'Live Streaming';
		$cDate = date('Y-m-d');
		$cDateTime = date('Y-m-d H:i:s');
		// Get List
		$liveHistory = Live::where('show', 1)
				->where(function ($query) use ($cDate) {
					$query->whereDate('start_time', '<=', $cDate)
						  ->orWhereDate('created_at', '<=', $cDate);
				})
				->orderBy('start_time', 'DESC')
				->get();
		$liveUpcoming = Live::where(function ($query) use ($cDateTime) 
				{
					$query->where('start_time', '>=', $cDateTime)
						  ->orWhere('end_time', '>=', $cDateTime);
				})
				->orderBy('start_time')
				->first();
		if (!$liveUpcoming || $liveUpcoming === null) {
			$liveUpcoming = Live::orderBy('start_time', 'DESC')->first();
		}

		// Return View
		return view('streaming.index')->with([
		'headTitle' => true,
		'pageTitle' => $pageTitle,
		'history'   => $liveHistory->slice(1),
		'upcoming'  => $liveUpcoming
		]);
	}

	public function listener()
	{
		$code = 200;
		$message = false;
		$liveUpcoming = Live::where('start_time', '>=', date('Y-m-d H:i:s'))->orderBy('start_time')->first();
		if ($liveUpcoming) {
			$code = 200;
			$message = true;
		}
		$responses = array(
    		'status_code' => $code,
    		'status_message' => $message,
    		'items' => $liveUpcoming,
    	);

        return response()->json($responses, $responses['status_code']);
	}
}
