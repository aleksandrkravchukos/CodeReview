<?php declare(strict_types=1);

namespace Review\Service;

class FileService
{
    /**
     * Input sample file
     *
     * @var string
     */
    private $inputFile;

    /**
     * Get current input file.
     *
     * @return string|null
     */
    public function getInputFile(): ?string
    {
        return $this->inputFile;
    }

    /**
     * Set input file of service.
     *
     * @param string $inputFile
     *
     * @return FileService
     */
    public function setInputFile(string $inputFile): FileService
    {
        $this->inputFile = $inputFile;

        return $this;
    }

    /**
     * Check if input file exist.
     *
     * @param string $file
     *
     * @return bool
     */
    public function checkFileExist(string $file): bool
    {
        return file_exists($file);
    }

    /**
     * Check if input file is readable.
     *
     * @param string $file
     *
     * @return bool
     */
    public function checkFileIsReadable(string $file): bool
    {
        return is_readable($file);
    }

    /*
     * Get service content.
     *
     * @param string $fileName
     *
     * @return string
     */
    public function getFileContent(string $file): string
    {
        $content = '';
        if ($this->checkFileExist($file)) {
            $content = file_get_contents($file);
        }

        return $content;
    }
}
