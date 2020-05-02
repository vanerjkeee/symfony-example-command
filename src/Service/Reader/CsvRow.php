<?php

declare(strict_types=1);

namespace App\Service\Reader;

class CsvRow implements FileRow
{
    private int $key;

    private array $data;

    /**
     * CsvRow constructor.
     * @param int $key
     * @param array $data
     */
    public function __construct(int $key, array $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getKey(): int
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
