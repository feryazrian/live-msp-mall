<?php

namespace Marketplace\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Auth;
use Marketplace\Message;

class MessageNotification implements ShouldBroadcast
{
    use SerializesModels;
 
    public $message;
    public $user;
 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
		// Initialization
    	$items = array();
        $user_id = $user_id;

		// Lists
    	$lists = Message::where('receiver_id', $user_id)
    		->orWhere('sender_id', $user_id)
    		->orderBy('updated_at', 'desc')
			->get();

		foreach ($lists as $item)
		{
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

			if ($item->updated_at->format('Y-m-d') == date('Y-m-d'))
			{
				$timestamp = $item->updated_at->format('H:i');
			}
			
			if ($item->updated_at->format('Y-m-d') != date('Y-m-d'))
			{
				$timestamp = str_replace('yang lalu', '', $item->updated_at->diffForHumans());
			}

			$data = array(
				'id' => $item->id,
				'url' => route('message.detail', ['username' => $user->username]),
				'photo' => asset('uploads/photos/small-'.$user->photo),
				'name' => $user->name,
				'content' => $item->content,
				'status' => $status,
				'timestamp' => $timestamp,
			);
			
			$items[] = $data;
		}
		
		// Return Json
        $this->message = $items;
        $this->user = $user_id;
    }
 
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('message.'.$this->user);
    }
}
