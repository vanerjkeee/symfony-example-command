<?php

declare(strict_types=1);

namespace App\Service\Reader;

class CsvFileReader implements FileReader
{
    private const DELIMITER = ';';

    /**
     * @param string $fileName
     * @return \Generator
     */
    public function readByRow(string $fileName): \Generator
    {
        $file = $this->getFile($fileName);
        $file->seek(1);
        while (!$file->eof()) {
            $row = $file->current();
            if (is_array($row)) {
                yield $row;
            }
            $file->next();
        }
    }

    /**
     * @param string $fileName
     * @param int $columnNumber
     * @param string $value
     * @return CsvRow|null
     */
    public function findRowByValue(string $fileName, int $columnNumber, string $value): ?CsvRow
    {
        $file = $this->getFile($fileName);
        $file->seek(1);
        while (!$file->eof()) {
            $row = $file->current();
            if (is_array($row) && isset($row[$columnNumber]) && $row[$columnNumber] === $value) {
                $key = $file->key();
                $file = null;
                return new CsvRow($key, $row);
            }
            $file->next();
        }
        $file = null;
        return null;
    }

    /**
     * @param string $fileName
     * @return \SplFileObject
     */
    private function getFile(string $fileName): \SplFileObject
    {
        if (!file_exists($fileName)) {
            throw new \RuntimeException(sprintf('File "%s" does not exist', $fileName));
        }
        $file = new \SplFileObject($fileName, 'r');
        $file->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
        $file->setCsvControl(static::DELIMITER);
        return $file;
    }
}
