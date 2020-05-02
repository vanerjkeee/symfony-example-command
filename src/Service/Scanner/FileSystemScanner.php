<?php

declare(strict_types=1);

namespace App\Service\Scanner;

class FileSystemScanner implements FileScanner
{
    private FileMatcher $fileMatcher;

    /**
     * FileSystemScanner constructor.
     * @param FileMatcher $fileMatcher
     */
    public function __construct(FileMatcher $fileMatcher)
    {
        $this->fileMatcher = $fileMatcher;
    }

    /**
     * @param string $directory
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public function scan(string $directory): array
    {
        if (!is_dir($directory)) {
            throw new \InvalidArgumentException('Directory does not exists');
        }

        $result = [];
        $doneDirs = [];
        $currentDir = $directory;
        $currentContent = scandir($directory, SCANDIR_SORT_DESCENDING);
        $i = 0;

        //альтернатива рекурсивному поиску по дереву папок
        while (true) {
            //если прошли папку целиком
            if ($currentContent[$i] === '..') {
                // если прошли целиком начальную папку, значит выходим из цикла
                if ($currentDir === $directory) {
                    break;
                }

                //если это не корневая папка, то выходим наверх
                $doneDirs[] = $currentDir;
                $currentDir = substr($currentDir, 0, strrpos($currentDir, '/'));
                $currentContent = scandir($currentDir, SCANDIR_SORT_DESCENDING);
                $i = 0;
                continue;
            }

            $item = $currentDir . '/' . $currentContent[$i];
            //если найден файл, проверяем на совпадение и сохраняем
            if (!is_dir($item)) {
                if (!in_array($item, $result) && $this->fileMatcher->isMatch($item)) {
                    $result[] = $item;
                }
            } else {
                //проверяем, что не зайдем в одну и ту же папку дважды
                if (!in_array($item, $doneDirs)) {
                    $content = scandir($item, SCANDIR_SORT_DESCENDING);
                    //если папка пустая, не пытаемся ее сканировать
                    if (count($content) === 2) {
                        $doneDirs[] = $item;
                    } else {
                        //если папка не пустая, то переходим в нее
                        $currentDir = $item;
                        $currentContent = scandir($currentDir, SCANDIR_SORT_DESCENDING);
                        $i = 0;
                        continue;
                    }
                }
            }
            $i++;
        }

        return $result;
    }
}
