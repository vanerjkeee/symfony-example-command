<?php

declare(strict_types=1);

namespace App\Service\Reader;

interface FileRow
{
    /**
     * @return array
     */
    public function getData(): array;
}
