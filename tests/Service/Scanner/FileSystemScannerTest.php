<?php

declare(strict_types=1);

namespace App\Tests\Service\Scanner;

use App\Service\Scanner\FileMatcher;
use App\Service\Scanner\FileSystemScanner;
use PHPUnit\Framework\TestCase;

class FileSystemScannerTest extends TestCase
{
    private const DIRECTORY = 'tests/data';

    private FileMatcher $fileMatcher;

    private FileSystemScanner $fileSystemScanner;

    public function setUp(): void
    {
        mkdir(static::DIRECTORY . '/dir1');
        mkdir(static::DIRECTORY . '/dir2');
        mkdir(static::DIRECTORY . '/dir1/dir11');
        $this->fileMatcher = $this->getMockBuilder(FileMatcher::class)->getMock();
        $this->fileSystemScanner = new FileSystemScanner($this->fileMatcher);
        parent::setUp();
    }

    public function tearDown(): void
    {
        rmdir(static::DIRECTORY . '/dir1/dir11');
        rmdir(static::DIRECTORY . '/dir2');
        rmdir(static::DIRECTORY . '/dir1');
    }

    public function testScan(): void
    {
        $files = $this->fileSystemScanner->scan(static::DIRECTORY);
        $this->assertEmpty($files);

        file_put_contents(static::DIRECTORY . '/dir1/dir11/file.csv', '');
        $this->fileMatcher->expects($this->once())->method('isMatch')->willReturn(true);
        $files = $this->fileSystemScanner->scan(static::DIRECTORY);
        $this->assertCount(1, $files);
        $this->assertEquals(static::DIRECTORY . '/dir1/dir11/file.csv', $files[0]);
        unlink(static::DIRECTORY . '/dir1/dir11/file.csv');
    }
}
