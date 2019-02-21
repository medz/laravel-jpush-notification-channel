<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush\Contracts;

use Medz\Laravel\Notifications\JPush\PushPayload;

interface PushPayloadMakeable
{
    /**
     * Make push payload.
     * @param \Medz\Laravel\Notifications\JPush\PushPayload $payload
     */
    public function make(PushPayload $payload);
}
