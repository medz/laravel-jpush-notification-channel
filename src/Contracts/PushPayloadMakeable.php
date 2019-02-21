<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush\Contracts;

use Medz\Laravel\Notifications\JPush\PushPayload;

interface PushPayloadMakeable
{
    public function make(PushPayload $payload);
}
