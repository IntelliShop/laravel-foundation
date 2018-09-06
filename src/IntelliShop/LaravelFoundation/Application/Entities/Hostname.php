<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Entities;

use Hyn\Tenancy\Models\Hostname as OriginalModel;

class Hostname extends OriginalModel
{
    /** @var string */
    public $timezone;

    /** @var string */
    public $locale;
}
