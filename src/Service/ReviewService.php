<?php declare(strict_types=1);

namespace Review\Service;

class ReviewService
{

    const EU_ALPHA2_CODES = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'];
    const DEFAULT_API_BIN_SERVICE = 'https://lookup.binlist.net/';
    const DEFAULT_API_RATES_SERVICE = 'https://api.exchangeratesapi.io/latest/';

    /**
     * Input sample file
     *
     * @var string
     */
    private $inputFile;

    /**
     * @var string
     */
    private $apiBinServiceUrl;

    /**
     * @var string
     */
    private $apiRatesServiceUrl;

    /**
     * @return string
     */
    public function getInputFile()
    {
        return $this->inputFile;
    }

    /**
     * @param string $inputFile
     * @return ReviewService
     */
    public function setInputFile(string $inputFile): ReviewService
    {
        $this->setApiBinServiceUrl(self::DEFAULT_API_BIN_SERVICE);
        $this->setApiRatesServiceUrl(self::DEFAULT_API_RATES_SERVICE);
        $this->inputFile = $inputFile;
        return $this;
    }

    /**
     * Check if input file exist.
     *
     * @return bool
     */
    public function checkFileExist(): bool
    {
        return file_exists($this->getInputFile());
    }

    /**
     * Check if input file exist.
     *
     * @return bool
     */
    public function checkFileIsReadable(): bool
    {
        return is_readable($this->getInputFile());
    }

    /*
     * @param string $fileName
     * @return string
     */
    public function getFileContent(): string
    {
        $content = '';

        if ($this->checkFileExist()) {
            $content = file_get_contents($this->getInputFile());
        }

        return $content;
    }

    public function isJsonString(string $content): bool
    {
        json_decode(trim($content));

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function isValidXML(string $content): bool
    {
        $isXml = true;
        $doc = @simplexml_load_string($content);
        if (!$doc) {
            $isXml = false;
        }

        return $isXml;
    }

    /**
     * @return string
     */
    public function getApiBinServiceUrl(): string
    {
        return $this->apiBinServiceUrl;
    }

    /**
     * @param string $apiBinServiceUrl
     * @return ReviewService
     */
    public function setApiBinServiceUrl(string $apiBinServiceUrl): ReviewService
    {
        $this->apiBinServiceUrl = $apiBinServiceUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiRatesServiceUrl(): string
    {
        return $this->apiRatesServiceUrl;
    }

    /**
     * @param string $apiRatesServiceUrl
     * @return ReviewService
     */
    public function setApiRatesServiceUrl(string $apiRatesServiceUrl): ReviewService
    {
        $this->apiRatesServiceUrl = $apiRatesServiceUrl;
        return $this;
    }

}