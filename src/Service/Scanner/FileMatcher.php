<?php

declare(strict_types=1);

namespace App\Service\Scanner;

interface FileMatcher
{
    /**
     * @param string $fileName
     * @return bool
     */
    public function isMatch(string $fileName): bool;
}
