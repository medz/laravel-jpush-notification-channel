<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush;

use JPush\PushPayload as Payload;
use Medz\Laravel\Notifications\JPush\Contracts\PushPayloadMakeable;

class PushPayload
{
    protected $payload;

    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
    }

    public function make(PushPayloadMakeable $makeable)
    {
        $makeable->make($this);

        return $this;
    }

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

    public function send()
    {
        return $this->payload->send();
    }

    public function __call($method, $params)
    {
        $this->payload->{$method}($params);
        
        return $this;
    }
}
