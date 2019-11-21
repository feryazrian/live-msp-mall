<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Message;
use Marketplace\MessageList;
use Marketplace\User;
use Marketplace\Option;

use Marketplace\Events\MessageNotification;
use Marketplace\Events\MessageContentNotification;
use Marketplace\Events\CounterNotification;

use Auth;

class MessageController extends Controller
{
	public function index()
	{
        // Initialization
        $pageTitle = 'Pesan Masuk';
        $user_id = Auth::user()->id;

		// Lists
    	$lists = Message::where('receiver_id', $user_id)
    		->orWhere('sender_id', $user_id)
    		->orderBy('updated_at', 'desc')
			->simplePaginate(20);
			
		// Return View
		return view('message.index')->with([
            'headTitle' => true,
            'contactHide' => true,
            'pageTitle' => $pageTitle,
            'lists' => $lists,
        ]);
	}

	public function detail(Request $request)
	{
		// Initialization
		$username = str_slug($request->username);
		$username = str_replace('-','_',$username);
        $pageTitle = 'Pesan Masuk';

		// Check User
		$user = User::where('username', $username)
			->first();

        if(empty($user)) {
			// Return Redirect
        	return redirect('/');
		}

		// Validation
        if(Auth::user()->username == $username) {
			// Return Redirect
        	return redirect()->route('message');
		}

		$senderId = Auth::user()->id;
		$receiverId = $user->id;
        $pageTitle = $user->name;

		// Message Status
		$this->status($senderId, $receiverId);
		
		// Lists
    	$lists = MessageList::where('sender_id', $senderId)
			->where('receiver_id', $receiverId)
			->orWhere('sender_id', $receiverId)
			->where('receiver_id', $senderId)
			->orderBy('created_at', 'ASC')
			->get();

		// Return View
		return view('message.detail')->with([
            'headTitle' => true,
            'contactHide' => true,
			'pageTitle' => $pageTitle,
			'receiverId' => $receiverId,
			'user' => $user,
			'lists' => $lists,
		]);
	}
	
	public function contact(Request $request)
	{	
		// Initialization
		$pageTitle = 'Pesan Masuk';
		
		// Username
		$username = Option::where('type', 'username-customercare')->first();
		$username = $username->content;

		// Check User
		$user = User::where('username', $username)
			->first();

        if(empty($user)) {
			// Return Redirect
        	return redirect('/');
		}

		// Validation
        if (Auth::user()->username == $username) {
			// Return Redirect
        	return redirect()->route('message');
		}

		$senderId = Auth::user()->id;
		$receiverId = $user->id;
        $pageTitle = $user->name;

		// Message Status
		$this->status($senderId, $receiverId);
		
		// Lists
    	$lists = MessageList::where('sender_id', $senderId)
			->where('receiver_id', $receiverId)
			->orWhere('sender_id', $receiverId)
			->where('receiver_id', $senderId)
			->orderBy('created_at', 'ASC')
			->get();

		// Return View
		return view('contact.index')->with([
            'headTitle' => true,
            'contactHide' => true,
			'pageTitle' => $pageTitle,
			'receiverId' => $receiverId,
			'user' => $user,
			'lists' => $lists,
		]);
	}

	public function json(Request $request)
	{
		// Initialization
    	$items = array();
		$username = str_slug($request->username);

		// Check User
		$user = User::where('username', $username)
			->first();

        if (empty($user)) {
			// Return Redirect
        	return redirect('/');
		}

		// Validation
        if (Auth::user()->username == $username) {
			// Return Redirect
        	return redirect()->route('message');
		}

		$senderId = Auth::user()->id;
		$receiverId = $user->id;

		// Message Status
		$this->status($senderId, $receiverId);
		
		// Lists
    	$lists = MessageList::where('sender_id', $senderId)
			->where('receiver_id', $receiverId)
			->orWhere('sender_id', $receiverId)
			->where('receiver_id', $senderId)
			->orderBy('created_at', 'ASC')
			->get();
	
		foreach ($lists as $item)
		{
			if ($item->sender->id == Auth::user()->id)
			{
				$class = 'right';
			}

			if ($item->sender->id != Auth::user()->id)
			{
				$class = 'left';
			}

			if ($item->created_at->format('Y-m-d') == date('Y-m-d'))
			{
				$timestamp = $item->created_at->format('H:i');
			}

			if ($item->created_at->format('Y-m-d') != date('Y-m-d'))
			{
				$timestamp = str_replace('yang lalu', '', $item->created_at->diffForHumans());
			}

    		$data = array(
                'id' => $item->id,
                'content' => nl2br(strip_tags($item->content)),
                'timestamp' => $timestamp,
                'class' => $class,
			);
			
			$items[] = $data;
		}
		
		// Return Json
		return response()->json($items, 200);
	}

	public function store(Request $request)
	{
		// Initialization
		$senderId = Auth::user()->id;
		$receiverId = $request->id;
		$content = $request->content;

		// Validation
        if (!empty($senderId) || !empty($receiverId))
        {
    		//DB::beginTransaction();

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
    		$newMessageList = New MessageList;
    		$newMessageList->message_id = $messageId;
    		$newMessageList->sender_id = $senderId;
    		$newMessageList->receiver_id = $receiverId;
    		$newMessageList->content = $content;
    		$newMessageList->save();

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
    		    	$messageView = Message::where('id', $message->id)
    		    		->update([
    		    			'content' => $content,
    		    			'receiver_view' => $messageCount,
    		    		]);
        		}
        		if ($message->sender_id == $receiverId)
        		{
    		    	$messageView = Message::where('id', $message->id)
    		    		->update([
    		    			'content' => $content,
    		    			'sender_view' => $messageCount,
    		    		]);
        		}
			}
			
			// Message Status
			$this->status($senderId, $receiverId);

			//DB::commit();

			// Broadcast
			event(new MessageNotification($senderId));
			event(new MessageNotification($receiverId));

			event(new MessageContentNotification($senderId, $receiverId));
			event(new MessageContentNotification($receiverId, $senderId));

			event(new CounterNotification($senderId));
			event(new CounterNotification($receiverId));
		}
	}
    public function delete(Request $request)
    {
		// Initialization
        $senderId = Auth::user()->id;
        $receiverId = $request->id;

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

		// Broadcast
		event(new MessageNotification($senderId));
		event(new MessageNotification($receiverId));

		event(new MessageContentNotification($senderId, $receiverId));
		event(new MessageContentNotification($receiverId, $senderId));

		event(new CounterNotification($senderId));
		event(new CounterNotification($receiverId));

		// Return Redirect
		return redirect()
			->route('message')
			->with('status', 'Selamat!! Pesan telah berhasil dihapus.');
    }
    public function status($senderId, $receiverId)
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
				$messageView = Message::where('id', $message->id)
					->update([
						'sender_view' => 0,
				]);
			}

			if ($message->sender_id == $receiverId)
			{
				$messageView = Message::where('id', $message->id)
					->update([
						'receiver_view' => 0,
				]);
			}
		}
    }
}
