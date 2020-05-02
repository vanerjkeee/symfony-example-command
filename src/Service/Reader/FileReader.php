<?php

declare(strict_types=1);

namespace App\Service\Reader;

interface FileReader
{
    /**
     * @param string $fileName
     * @return \Generator
     */
    public function readByRow(string $fileName): \Generator;

    /**
     * @param string $fileName
     * @param int $columnNumber
     * @param string $value
     * @return FileRow|null
     */
    public function findRowByValue(string $fileName, int $columnNumber, string $value): ?FileRow;
}
