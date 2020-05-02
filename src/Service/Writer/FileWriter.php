<?php

declare(strict_types=1);

namespace App\Service\Writer;

interface FileWriter
{
    /**
     * @param string $fileName
     * @param int $rowIndex
     * @param array $replacementData
     */
    public function rewriteRow(string $fileName, int $rowIndex, array $replacementData): void;

    /**
     * @param string $fileName
     * @param array $data
     */
    public function appendRow(string $fileName, array $data): void;
}
