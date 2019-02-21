<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush;

use JPush\PushPayload;

class Sender
{
    /**
     * The audience keys.
     * @var array
     */
    static protected $keys = [
        'tags' => 'tag',
        'tagAnds' => 'tag_and',
        'tagNots' => 'tag_not',
        'alias' => 'alias',
        'registrationIds' => 'registration_id',
        'segmentIds' => 'segment',
        'abtests' => 'abtest',
    ];

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
     * @param \JPush\PushPayload $payload
     * @return \JPush\PushPayload
     */
    public function make(PushPayload $payload): PushPayload
    {
        if ($this->platform) {
            $payload->setPlatform($this->platform);
        }

        foreach($this->audience as $key => $value) {
            $payload->updateAudience($key, $value, static::$keys[$key]);
        }

        return $payload;
    }
}
