<?php

declare(strict_types=1);

namespace App\Service\Scanner;

interface FileScanner
{
    /**
     * @param string $directory
     * @return array
     */
    public function scan(string $directory): array;
}
