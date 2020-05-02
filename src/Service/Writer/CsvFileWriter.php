<?php

declare(strict_types=1);

namespace App\Service\Writer;

class CsvFileWriter implements FileWriter
{
    private const DELIMITER = ';';

    /**
     * @param string $fileName
     * @param int $rowIndex
     * @param array $replacementData
     */
    public function rewriteRow(string $fileName, int $rowIndex, array $replacementData): void
    {
        $file = $this->getFile($fileName, 'r');

        $tmpFileName = $fileName . 'tmp';
        file_put_contents($tmpFileName, '');
        $fileTmp = $this->getFile($tmpFileName, 'w');

        while (!$file->eof()) {
            $line = $file->current();
            if ($file->key() === $rowIndex) {
                $line = $replacementData;
            }
            if (is_array($line)) {
                $fileTmp->fputcsv($line);
            }
            $file->next();
        }

        $file = null;
        $fileTmp = null;
        unlink($fileName);
        rename($fileName . 'tmp', $fileName);
    }

    /**
     * @param string $fileName
     * @param array $data
     */
    public function appendRow(string $fileName, array $data): void
    {
        $file = $this->getFile($fileName, 'r+');
        $file->fseek(0, SEEK_END);
        $file->fputcsv($data);
        $file = null;
    }

    /**
     * @param string $fileName
     * @param string $openMode
     * @return \SplFileObject
     */
    private function getFile(string $fileName, string $openMode): \SplFileObject
    {
        if (!file_exists($fileName)) {
            throw new \RuntimeException(sprintf('File "%s" does not exist', $fileName));
        }
        $file = new \SplFileObject($fileName, $openMode);
        $file->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
        $file->setCsvControl(static::DELIMITER);
        return $file;
    }
}
