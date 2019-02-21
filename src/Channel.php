<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush;

use JPush\Client as JPushClient;
use Illuminate\Notifications\Notification;

class Channel
{
    /**
     * @var \JPush\Client
     */
    protected $client;

    /**
     * Create the JPush Notification channel.
     * @param \JPush\Client $client
     */
    public function __construct(JPushClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! ($to = $notifiable->routeNotificationFor('jpush', $notification)) instanceof Sender) {
            return;
        }

        $message = $notification->toJpush($notifiable);
        if (is_string($message)) {
            $message = new Message($message);
        }

        $payload = new PushPayload($this->client->push());
        $payload->make($to);
        $payload->make($message);

        try {
            $payload->send();
        } catch (\Throwable $th) {
            return;
        }
    }
}
