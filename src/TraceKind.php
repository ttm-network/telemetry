<?php

declare(strict_types=1);

namespace TTM\Telemetry;

enum TraceKind
{
    case INTERNAL;
    case CLIENT;
    case SERVER;
    case PRODUCER;
    case CONSUMER;
}

