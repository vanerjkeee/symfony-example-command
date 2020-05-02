<?php

declare(strict_types=1);

namespace App\Tests\Service\Reader;

use App\Service\Reader\CsvFileReader;
use PHPUnit\Framework\TestCase;

class CsvFileReaderTest extends TestCase
{
    private const FILE_PATH = 'tests/data/testfile.csv';

    private CsvFileReader $csvFileReader;

    public function setUp(): void
    {
        file_put_contents(static::FILE_PATH, implode(PHP_EOL, $this->getFileContent()));
        $this->csvFileReader = new CsvFileReader();
        parent::setUp();
    }

    public function tearDown(): void
    {
        unlink(static::FILE_PATH);
        parent::tearDown();
    }

    public function testReadByRow_givenWrongFile(): void
    {
        $rows = $this->csvFileReader->readByRow(static::FILE_PATH . '1');
        $this->expectException(\RuntimeException::class);
        $rows->next();
    }

    public function testReadByRow_givenCorrectFile(): void
    {
        $rows = $this->csvFileReader->readByRow(static::FILE_PATH);
        $i = 1;
        $expected = $this->getFileContent();
        foreach ($rows as $row) {
            $this->assertEquals(explode(';', $expected[$i]), $row);
            $i++;
        }
    }

    public function testFindRowByValue_givenWrongFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->csvFileReader->findRowByValue(static::FILE_PATH . '1', 0, '2018-03-02');
    }

    public function testFindRowByValue_givenCorrectFile(): void
    {
        $row = $this->csvFileReader->findRowByValue(static::FILE_PATH, 0, '2018-03-02');
        $this->assertNotNull($row);
        $this->assertEquals(2, $row->getKey());
        $this->assertEquals(explode(';', $this->getFileContent()[2]), $row->getData());
    }

    private function getFileContent(): array
    {
        return [
            'date;A;B;C',
            '2018-03-01;4.1;4.1;-4.1',
            '2018-03-02;8;8;-8',
            '2018-03-03;12;12;-12'
        ];
    }
}
