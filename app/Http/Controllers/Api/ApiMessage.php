<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use Marketplace\Message;
use Marketplace\MessageList;
use Marketplace\User;
use Marketplace\Option;

class ApiMessage extends Controller
{
	public function index(Request $request)
	{
		// Initialization
    	$items = array();

		// Take & Skip
        $take = config('app.take');
        $skip = config('app.skip');

        if (!empty($request->take))
        {
            $take = $request->take;
        }

        if (!empty($request->skip))
        {
            $skip = $request->skip;
        }

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
		]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
        }

		// Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

		// Success
        if (empty($responses))
        {
			// Lists
			$lists = Message::where('receiver_id', $user_id)
				->orWhere('sender_id', $user_id)
				->orderBy('updated_at', 'desc')
				->take($take)
				->skip($skip)
				->get();

			foreach ($lists as $item)
			{
				// Sender Profile
				if ($user_id == $item->receiver_id)
				{
					$user = $item->sender;
					$status = $item->receiver_view;
				}
				if ($user_id == $item->sender_id)
				{
					$user = $item->receiver;
					$status = $item->sender_view;
				}

				// Location
				$location = null;
				if (!empty($user->kabupaten))
				{
					$location = $user->kabupaten->name;
				}

				$data = array(
					'id' => $item->id,
					'user' => array(
						'id' => $user->id,
						'name' => $user->name,
						'username' => $user->username,
						'photo' => asset('uploads/photos/medium-'.$user->photo),
						'location' => $location,
					),
					'content' => $item->content,
					'status' => $status,
				);
	
				$created = array(
					'human' => $item->created_at->diffForHumans(),
					'millisecond' => strtotime($item->created_at) * 1000,
					'created_at' => $item->created_at,
				);
				$updated = array(
					'human' => $item->updated_at->diffForHumans(),
					'millisecond' => strtotime($item->updated_at) * 1000,
					'updated_at' => $item->updated_at,
				);
				$data = array_add($data, 'created', $created);
				$data = array_add($data, 'updated', $updated);
	
				$items[] = $data;
			}

			$responses = array(
				'status_code' => 200,
				'status_message' => 'OK',
				'items' => $items,
			);
        }

        return response()->json($responses, $responses['status_code']);
	}

	public function detail(Request $request)
	{
		// Initialization
    	$items = array();

		// Take & Skip
        $take = config('app.take');
        $skip = config('app.skip');

        if (!empty($request->take))
        {
            $take = $request->take;
        }

        if (!empty($request->skip))
        {
            $skip = $request->skip;
        }

		// Validation
        $validator = Validator::make($request->all(), [
			'sender_id' => 'required|integer',
			'receiver_id' => 'required|integer',
		]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
		}

		// Check
		$senderId = $request->sender_id;
		$receiverId = $request->receiver_id;

		$user = User::where('id', $receiverId)
			->first();

		if (empty($responses) AND empty($user))
		{
			$responses = array(
				'status_code' => 203,
				'status_message' => 'Not Found',
				'items' => $items,
			);
		}

		if (empty($responses) AND $senderId == $receiverId)
		{
			$responses = array(
				'status_code' => 203,
				'status_message' => 'Not Found',
				'items' => $items,
			);
		}

		// Success
        if (empty($responses))
        {
			// Check Message
			$message = Message::where('receiver_id', $receiverId)
				->where('sender_id', $senderId)
				->orWhere('receiver_id', $senderId)
				->where('sender_id', $receiverId)
				->first();
			
			// Update
			if (!empty($message))
			{
				$messageOpen = MessageList::where('sender_id', $receiverId)
					->where('receiver_id', $senderId)
					->where('open', 0)
					->update([
						'open' => 1,
				]);

				if ($message->receiver_id == $receiverId)
				{
					$messageView = Message::where('receiver_id', $receiverId)
						->where('sender_id', $senderId)
						->update([
							'sender_view' => 0,
					]);
				}

				if ($message->sender_id == $receiverId)
				{
					$messageView = Message::where('sender_id', $receiverId)
						->where('receiver_id', $senderId)
						->update([
							'receiver_view' => 0,
					]);
				}
			}
			
			// Lists
			$lists = MessageList::where('sender_id', $senderId)
				->where('receiver_id', $receiverId)
				->orWhere('sender_id', $receiverId)
				->where('receiver_id', $senderId)
				->orderBy('created_at', 'desc')
				->take($take)
				->skip($skip)
				->get();
		
			foreach ($lists as $item)
			{
				// Position
				if ($item->sender->id == $senderId)
				{
					$user = $item->sender;
					$position = 'right';
				}

				if ($item->sender->id != $senderId)
				{
					$user = $item->receiver;
					$position = 'left';
				}

				// Location
				$location = null;
				if (!empty($user->kabupaten))
				{
					$location = $user->kabupaten->name;
				}

				$data = array(
					'id' => $item->id,
					'user' => array(
						'id' => $user->id,
						'name' => $user->name,
						'username' => $user->username,
						'photo' => asset('uploads/photos/medium-'.$user->photo),
						'location' => $location,
					),
					'content' => nl2br(strip_tags($item->content)),
					'position' => $position,
				);
	
				$created = array(
					'human' => $item->created_at->diffForHumans(),
					'millisecond' => strtotime($item->created_at) * 1000,
					'created_at' => $item->created_at,
				);
				$updated = array(
					'human' => $item->updated_at->diffForHumans(),
					'millisecond' => strtotime($item->updated_at) * 1000,
					'updated_at' => $item->updated_at,
				);
				$data = array_add($data, 'created', $created);
				$data = array_add($data, 'updated', $updated);
	
				$items[] = $data;
			}

        	$responses = array(
        		'status_code' => 200,
        		'status_message' => 'OK',
        		'items' => $items,
        	);
		}

        return response()->json($responses, $responses['status_code']);
	}

	public function create(Request $request)
	{
		// Initialization
		$items = array();
		
		// Validation
        $validator = Validator::make($request->all(), [
			'sender_id' => 'required|integer',
			'receiver_id' => 'required|integer',
			'content' => 'required',
		]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
		}

		// Check
		$senderId = $request->sender_id;
		$receiverId = $request->receiver_id;
		$content = $request->content;

		$user = User::where('id', $receiverId)
			->first();

		if (empty($responses) AND empty($user))
		{
			$responses = array(
				'status_code' => 203,
				'status_message' => 'Not Found',
				'items' => $items,
			);
		}

		// Success
        if (empty($responses))
        {
			// Check
    		$message = Message::where('receiver_id', $receiverId)
    			->where('sender_id', $senderId)
    			->orWhere('receiver_id', $senderId)
    			->where('sender_id', $receiverId)
				->first();
			
			if (!empty($message))
			{
				$messageId = $message->id;
			}

			// Insert Message
    		if (empty($message))
    		{
    			$message = New Message;
    			$message->sender_id = $senderId;
    			$message->receiver_id = $receiverId;
    			$message->content = $content;
    			$message->save();

    			$messageId = $message->id;
    		}

			// Insert Message Content
    		$insert = New MessageList;
    		$insert->message_id = $messageId;
    		$insert->sender_id = $senderId;
    		$insert->receiver_id = $receiverId;
    		$insert->content = $content;
    		$insert->save();

			// Update Message
    		if (!empty($message))
    		{
				$messageCount = MessageList::where('receiver_id', $receiverId)
					->where('sender_id', $senderId)
					->where('open', 0)
					->get()
					->count();
					
        		if ($message->receiver_id == $receiverId)
        		{
    		    	$messageView = Message::where('receiver_id', $receiverId)
    		    		->where('sender_id', $senderId)
    		    		->update([
    		    			'content' => $content,
    		    			'receiver_view' => $messageCount,
    		    		]);
        		}
        		if ($message->sender_id == $receiverId)
        		{
    		    	$messageView = Message::where('sender_id', $receiverId)
    		    		->where('receiver_id', $senderId)
    		    		->update([
    		    			'content' => $content,
    		    			'sender_view' => $messageCount,
    		    		]);
        		}
			}
			
			// Data
			$item = $message;

			// Position
			if ($item->sender->id == $senderId)
			{
				$user = $item->sender;
				$position = 'right';
			}

			if ($item->sender->id != $senderId)
			{
				$user = $item->receiver;
				$position = 'left';
			}

			// Location
			$location = null;
			if (!empty($user->kabupaten))
			{
				$location = $user->kabupaten->name;
			}

			$data = array(
				'id' => $item->id,
				'user' => array(
					'id' => $user->id,
					'name' => $user->name,
					'username' => $user->username,
					'photo' => asset('uploads/photos/medium-'.$user->photo),
					'location' => $location,
				),
				'content' => nl2br(strip_tags($item->content)),
				'position' => $position,
			);

			$created = array(
				'human' => $item->created_at->diffForHumans(),
				'millisecond' => strtotime($item->created_at) * 1000,
				'created_at' => $item->created_at,
			);
			$updated = array(
				'human' => $item->updated_at->diffForHumans(),
				'millisecond' => strtotime($item->updated_at) * 1000,
				'updated_at' => $item->updated_at,
			);
			$data = array_add($data, 'created', $created);
			$data = array_add($data, 'updated', $updated);

			$items[] = $data;

        	$responses = array(
        		'status_code' => 201,
        		'status_message' => 'Created',
        		'items' => $items,
        	);
		}

        return response()->json($responses, $responses['status_code']);
	}
    public function delete(Request $request)
    {
		// Initialization
		$items = array();
		
		// Validation
        $validator = Validator::make($request->all(), [
			'sender_id' => 'required|integer',
			'receiver_id' => 'required|integer',
		]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
		}

		// Check
		$senderId = $request->sender_id;
		$receiverId = $request->receiver_id;

		$item = Message::where('receiver_id', $receiverId)
			->where('sender_id', $senderId)
			->orWhere('receiver_id', $senderId)
			->where('sender_id', $receiverId)
			->first();

		if (empty($responses) AND empty($item))
		{
			$responses = array(
				'status_code' => 203,
				'status_message' => 'Not Found',
				'items' => $items,
			);
		}

		// Success
        if (empty($responses))
        {
			//DB::beginTransaction();

			$message = Message::where('receiver_id', $receiverId)
				->where('sender_id', $senderId)
				->orWhere('receiver_id', $senderId)
				->where('sender_id', $receiverId)
				->delete();

			$messageList = MessageList::where('receiver_id', $receiverId)
				->where('sender_id', $senderId)
				->orWhere('receiver_id', $senderId)
				->where('sender_id', $receiverId)
				->delete();

			//DB::commit();

        	$responses = array(
        		'status_code' => 202,
        		'status_message' => 'Deleted',
        		'items' => $items,
        	);
		}

		return response()->json($responses, $responses['status_code']);
    }
}
