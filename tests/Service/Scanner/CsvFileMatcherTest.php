<?php

declare(strict_types=1);

namespace App\Tests\Service\Scanner;

use App\Service\Scanner\CsvFileMatcher;
use PHPUnit\Framework\TestCase;

class CsvFileMatcherTest extends TestCase
{
    private CsvFileMatcher $csvFileMatcher;

    public function setUp(): void
    {
        $this->csvFileMatcher = new CsvFileMatcher();
        parent::setUp();
    }

    /**
     * @dataProvider dataProvider
     * @param string $fileName
     * @param bool $expected
     */
    public function testIsMatch(string $fileName, bool $expected): void
    {
        $this->assertEquals($this->csvFileMatcher->isMatch($fileName), $expected);
    }

    public function dataProvider()
    {
        return [
            ['csv', false],
            ['123.txt', false],
            ['123.csv', true],
        ];
    }
}
