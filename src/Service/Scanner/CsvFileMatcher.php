<?php

declare(strict_types=1);

namespace App\Service\Scanner;

class CsvFileMatcher implements FileMatcher
{
    private const EXTENSION = 'csv';

    /**
     * @param string $fileName
     * @return bool
     */
    public function isMatch(string $fileName): bool
    {
        $extension = substr($fileName, strrpos($fileName, '.') + 1);
        return $extension === static::EXTENSION;
    }
}
