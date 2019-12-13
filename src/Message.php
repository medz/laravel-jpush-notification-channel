<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush;

class Message implements Contracts\PushPayloadMakeable
{
    const IOS = 'iOS';
    const ANDROID = 'Android';
    const WP = 'Windows Phone';

    /**
     * Simple alert.
     * @var string|array
     */
    protected $alert;

    /**
     * Custom Message
     * @var array
     */
    protected $message;

    /**
     * Notifications.
     * @var array
     */
    protected $notifications = [];

    /**
     * Push options.
     * @var array
     */
    protected $options = [];

    /**
     * Create a push message.
     * @param null|string|array $alert
     */
    public function __construct($alert = null)
    {
        if (is_string($alert) || is_array($alert)) {
            $this->setAlert($alert);
        }
    }

    /**
     * Set simple alert.
     * @param string|array $alert
     */
    public function setAlert(string $alert)
    {
        if (is_string($alert) || is_array($alert)) {
            $this->alert = $alert;
            return $this;
        }

        throw new \Exception('Alert 只允许字符串或者数组');
    }

    /**
     * Set custom message.
     * @param null|string $contents
     * @param array $options
     */
    public function setMessage(?string $contents = null, array $options) {
        $this->message = array_merge($options, [
            'msg_content' => $contents,
        ]);

        return $this;
    }

    /**
     * Set notifications.
     * @param string $platform static::<IOS|ANDROID|WP>
     * @param string|array $alert
     * @param array $options
     */
    public function setNotification(string $platform, $alert, array $options = [])
    {
        if (!is_string($alert) && !is_array($alert)) {
            throw new \Exception('Alert 只允许字符串或者数组');
        }

        $this->notifications[$platform] = [
            'alert' => $alert,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Set push options.
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Make send params.
     * @param \Medz\Laravel\Notifications\JPush\PushPayload $payload
     */
    public function make(PushPayload $payload)
    {
        if ($this->alert) {
            $payload->setNotificationAlert($this->alert);
        }

        if ($this->message) {
            $payload->message($this->message['msg_content'], $this->message);
        }

        foreach ($this->notifications as $platform => $data) {
            switch ($platform) {
                case static::IOS:
                    $payload->iosNotification($data['alert'], $data['options']);
                    break;
                case static::ANDROID:
                    $payload->androidNotification($data['alert'], $data['options']);
                    break;
                case static::WP:
                    $payload->addWinPhoneNotification(
                        $data['alert'],
                        $data['options']['title'] ?? null,
                        $data['options']['_open_page'] ?? null,
                        $data['options']['extras'] ?? null
                    );
                    break;
                default:
                    break;
            }
        }
        $payload->options($this->options);

        return $this;
    }
}
