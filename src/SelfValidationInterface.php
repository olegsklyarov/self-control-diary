<?php

declare(strict_types=1);

namespace App;

interface SelfValidationInterface
{
    public function validate(): array;
}
