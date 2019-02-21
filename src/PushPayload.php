<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush;

use JPush\PushPayload as Payload;

class PushPayload
{
    /**
     * JPush push payload
     * @var \JPush\PushPayload
     */
    protected $payload;

    /**
     * Create a push payload.
     * @param \JPush\PushPayload $payload
     */
    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Using makeable.
     * @param Medz\Laravel\Notifications\JPush\Contracts\PushPayloadMakeable $makeable
     */
    public function make(Contracts\PushPayloadMakeable $makeable)
    {
        $makeable->make($this);

        return $this;
    }

    /**
     * set push audiences.
     * @param string $key
     * @param mixed $value
     */
    public function audiences(string $key, $value)
    {
        switch ($key) {
            case 'tag':
                $this->payload->addTag($value);
                break;
            case 'tag_and':
                $this->payload->addTagAnd($value);
                break;
            case 'tag_not':
                $this->payload->addTagNot($value);
                break;
            case 'alias':
                $this->payload->addAlias($value);
                break;
            case 'registration_id':
                $this->payload->addRegistrationId($value);
                break;
            case 'segment':
                $this->payload->addSegmentId($value);
                break;
            case 'abtest':
                $this->payload->addAbtest($value);
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Send payload to Jpush.
     * @return mixed
     */
    public function send()
    {
        return $this->payload->send();
    }

    /**
     * Call payload methods.
     * @param string $method
     * @param array $params
     */
    public function __call($method, $params)
    {
        $this->payload->{$method}(...$params);
        
        return $this;
    }
}
