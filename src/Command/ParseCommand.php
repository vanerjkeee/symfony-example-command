<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Reader\CsvRow;
use App\Service\Reader\FileReader;
use App\Service\Scanner\FileScanner;
use App\Service\Writer\FileWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends Command
{
    protected static $defaultName = 'app:run';

    private FileScanner $fileScanner;

    private FileReader $fileReader;

    private FileWriter $fileWriter;

    private string $outputFile;

    public function __construct(
        FileScanner $fileSystemScanner,
        FileReader $fileReader,
        FileWriter $fileWriter,
        string $outputFile
    ) {
        $this->fileScanner = $fileSystemScanner;
        $this->fileReader = $fileReader;
        $this->fileWriter = $fileWriter;
        $this->outputFile = $outputFile;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('directory', InputArgument::REQUIRED, 'Directory path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        file_put_contents($this->outputFile, 'date;A;B;C' . PHP_EOL);

        $files = $this->fileScanner->scan($directory);
        foreach ($files as $file) {
            $rows = $this->fileReader->readByRow($file);
            foreach ($rows as $row) {
                $rowDate = $row[0];
                for ($i = 1; $i < count($row); $i++) {
                    $row[$i] = (float)$row[$i];
                }

                /** @var CsvRow $existedRow */
                $existedRow = $this->fileReader->findRowByValue($this->outputFile, 0, $rowDate);
                if ($existedRow) {
                    $existedData = $existedRow->getData();
                    $existedKey = $existedRow->getKey();
                    for ($i = 1; $i < count($existedData); $i++) {
                        $existedData[$i] = (float)$existedData[$i] + $row[$i];
                    }
                    $this->fileWriter->rewriteRow($this->outputFile, $existedKey, $existedData);
                } else {
                    $this->fileWriter->appendRow($this->outputFile, $row);
                }
            }
        }

        return 0;
    }
}
