<?php

namespace Habib\Dashboard\Test;

use PHPUnit\Framework\TestCase as BaseTest;

class TestCase extends BaseTest
{
    protected function setUp(): void
    {
        $this->emptyTempDirectory();
    }

    protected function emptyTempDirectory()
    {
        $tempDirPath = __DIR__.'/temp';

        $files = scandir($tempDirPath);

        foreach ($files as $file) {
            if (! in_array($file, ['.', '..', '.gitignore'])) {
                unlink("{$tempDirPath}/{$file}");
            }
        }
    }
}
