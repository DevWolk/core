<?php

namespace Apiato\Core\Traits\TestsTraits\PhpUnit;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;

/**
 * Class TestsUploadHelperTrait.
 *
 * Tests helper for uploading files.
 */
trait TestsUploadHelperTrait
{
    public function getTestingImage(string $imageName, string $stubDirPath, string $mimeType = 'image/jpeg'): UploadedFile
    {
        return $this->getTestingFile($imageName, $stubDirPath, $mimeType);
    }

    public function getTestingFile(string $fileName, string $stubDirPath, string $mimeType = 'text/plain'): UploadedFile
    {
        $file = $stubDirPath . $fileName;

        return new UploadedFile($file, $fileName, $mimeType, null, true); // null = null | $testMode = true
    }

    public function createTestingImage(string $name, int $width = 200, int $height = 200): File
    {
        return UploadedFile::fake()->image($name, $width, $height);
    }
}
