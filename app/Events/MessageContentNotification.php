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
use Marketplace\MessageList;

class MessageContentNotification implements ShouldBroadcast
{
    use SerializesModels;
 
    public $message;
    public $sender;
    public $receiver;
 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sender, $receiver)
    {
		// Initialization
		$items = array();
		$senderId = $sender;
		$receiverId = $receiver;

		// Check Message
		$message = Message::where('receiver_id', $receiverId)
			->where('sender_id', $senderId)
			->orWhere('receiver_id', $senderId)
			->where('sender_id', $receiverId)
			->first();
		
		// Update
		if (!empty($message))
		{
			// $messageOpen = MessageList::where('sender_id', $receiverId)
			// 	->where('receiver_id', $senderId)
			// 	->update([
			// 		'open' => 1,
			// ]);

			// if ($message->receiver_id == $receiverId)
			// {
			// 	$messageView = Message::where('id', $message->id)
			// 		->update([
			// 			'sender_view' => 0,
			// 	]);
			// }

			// if ($message->sender_id == $receiverId)
			// {
			// 	$messageView = Message::where('id', $message->id)
			// 		->update([
			// 			'receiver_view' => 0,
			// 	]);
			// }
		}
		
		// Lists
    	$lists = MessageList::where('sender_id', $senderId)
			->where('receiver_id', $receiverId)
			->orWhere('sender_id', $receiverId)
			->where('receiver_id', $senderId)
			->orderBy('created_at', 'ASC')
			->get();
	
		foreach ($lists as $item)
		{
			if ($item->sender->id == $senderId)
			{
				$class = 'right';
			}

			if ($item->sender->id != $senderId)
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
        $this->message = $items;
        $this->sender = $sender;
        $this->receiver = $receiver;
    }
 
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('message.content.'.$this->sender.'.'.$this->receiver);
    }
}
