<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush;

class Sender implements Contracts\PushPayloadMakeable
{
    /**
     * Sender platform.
     * @var string|array<string>
     */
    protected $platform;

    /**
     * Sender audience.
     * @var array
     */
    protected $audience = [];

    /**
     * Create a sender.
     * @param array $payload;
     */
    public function __construct(?array $payload = null)
    {
        if (isset($payload['platform'])) {
            $this->setPlatform($payload['platform']);
        }
        if (isset($payload['audience'])) {
            $this->setAudience($payload['audience']);
        }
    }

    /**
     * Set send platform.
     * @param string|array $platform
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Set send audience
     * @param array $audience
     */
    public function setAudience(array $audience)
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * Make send params.
     * @param \Medz\Laravel\Notifications\JPush\PushPayload $payload
     */
    public function make(PushPayload $payload)
    {
        if ($this->platform) {
            $payload->setPlatform($this->platform);
        }

        foreach($this->audience as $key => $value) {
            $payload->audiences($key, $value);
        }

        return $payload;
    }
}
