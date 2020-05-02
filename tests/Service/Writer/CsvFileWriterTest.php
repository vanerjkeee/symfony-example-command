<?php

declare(strict_types=1);

namespace App\Tests\Service\Writer;

use App\Service\Writer\CsvFileWriter;
use PHPUnit\Framework\TestCase;

class CsvFileWriterTest extends TestCase
{
    private const FILE_PATH = 'tests/data/testfile.csv';

    private CsvFileWriter $csvFileWriter;

    public function setUp(): void
    {
        file_put_contents(static::FILE_PATH, '');
        $this->csvFileWriter = new CsvFileWriter();
        parent::setUp();
    }

    public function tearDown(): void
    {
        unlink(static::FILE_PATH);
        parent::tearDown();
    }

    public function testAppendRow_givenWrongFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->csvFileWriter->appendRow(static::FILE_PATH . '1', [1, 2, 3, 4]);
    }

    public function testAppendRow_givenCorrectFile(): void
    {
        $this->csvFileWriter->appendRow(static::FILE_PATH, [1, 2, 3, 4]);
        $this->assertEquals('1;2;3;4' . PHP_EOL, file_get_contents(static::FILE_PATH));
        $this->csvFileWriter->appendRow(static::FILE_PATH, [5, 6, 7, 8]);
        $this->assertEquals(
            '1;2;3;4' . PHP_EOL . '5;6;7;8' . PHP_EOL,
            file_get_contents(static::FILE_PATH)
        );
    }

    public function testRewriteRow_givenWrongFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->csvFileWriter->rewriteRow(static::FILE_PATH . '1', 1, [1, 2, 3, 4]);
    }

    public function testRewriteRow_givenCorrectFile(): void
    {
        file_put_contents(static::FILE_PATH, '1;2;3;4' . PHP_EOL . '5;6;7;8' . PHP_EOL);
        $this->csvFileWriter->rewriteRow(static::FILE_PATH, 1, [1, 2, 3, 4]);
        $this->assertEquals(
            '1;2;3;4' . PHP_EOL . '1;2;3;4' . PHP_EOL,
            file_get_contents(static::FILE_PATH)
        );
    }
}
