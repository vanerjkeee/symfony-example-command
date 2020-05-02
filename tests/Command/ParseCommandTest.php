<?php

declare(strict_types=1);

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParseCommandTest extends KernelTestCase
{
    private const OUTPUT_FILE = 'output/result.csv';

    private const DIRECTORY = 'tests/data';

    public function setUp(): void
    {
        if (file_exists(static::OUTPUT_FILE)) {
            unlink(static::OUTPUT_FILE);
        }
        mkdir(static::DIRECTORY . '/dir1');
        mkdir(static::DIRECTORY . '/dir2');
        mkdir(static::DIRECTORY . '/dir1/dir11');
        parent::setUp();
    }

    public function tearDown(): void
    {
        unlink(static::OUTPUT_FILE);
        rmdir(static::DIRECTORY . '/dir1/dir11');
        rmdir(static::DIRECTORY . '/dir2');
        rmdir(static::DIRECTORY . '/dir1');
        parent::tearDown();
    }

    public function testCommand_givenNoFiles()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'directory' => $kernel->getProjectDir() . '/' . static::DIRECTORY,
            ]
        );

        $this->assertEquals('date;A;B;C' . PHP_EOL, file_get_contents(static::OUTPUT_FILE));
    }

    public function testCommand_givenFiles()
    {
        file_put_contents(
            static::DIRECTORY . '/dir1/dir11/file.csv',
            'date;A;B;C' . PHP_EOL . '2020-01-01;1;2;3' . PHP_EOL
        );
        file_put_contents(
            static::DIRECTORY . '/dir1/file.csv',
            'date;A;B;C' . PHP_EOL . '2020-01-01;1;2;3' . PHP_EOL
        );
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'directory' => $kernel->getProjectDir() . '/' . static::DIRECTORY,
            ]
        );

        $this->assertEquals(
            'date;A;B;C' . PHP_EOL . '2020-01-01;2;4;6' . PHP_EOL,
            file_get_contents(static::OUTPUT_FILE)
        );
        unlink(static::DIRECTORY . '/dir1/dir11/file.csv');
        unlink(static::DIRECTORY . '/dir1/file.csv');
    }
}
