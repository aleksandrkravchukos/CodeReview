<?php declare(strict_types=1);

namespace Review\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ReviewService
{
    const EU_ALPHA2_CODES = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'];
    const DEFAULT_API_BIN_SERVICE = 'https://lookup.binlist.net/';
    const DEFAULT_API_RATES_SERVICE = 'https://api.exchangeratesapi.io/latest/';
    const PERCENT_EU = 0.01;
    const PERCENT_NOT_EU = 0.02;
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
     * @var Client
     */
    private $guzzle;

    /**
     * ReviewService constructor.
     */
    public function __construct()
    {
        $this->guzzle = new Client();
    }

    /**
     * Get current input file.
     *
     * @return string
     */
    public function getInputFile(): string
    {
        return $this->inputFile;
    }

    /**
     * Set input file of service.
     *
     * @param string $inputFile
     *
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
     * Check if input file is readable.
     *
     * @return bool
     */
    public function checkFileIsReadable(): bool
    {
        return is_readable($this->getInputFile());
    }

    /*
     * Get service content.
     *
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

    /**
     * Check if json is valid.
     *
     * @param string $content
     *
     * @return bool
     */
    public function isJsonString(string $content): bool
    {
        json_decode(trim($content));

        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Get api bin service url.
     *
     * @return string
     */
    public function getApiBinServiceUrl(): string
    {
        return $this->apiBinServiceUrl;
    }

    /**
     * Set api bin service url.
     *
     * @param string $apiBinServiceUrl
     *
     * @return ReviewService
     */
    public function setApiBinServiceUrl(string $apiBinServiceUrl): ReviewService
    {
        $this->apiBinServiceUrl = $apiBinServiceUrl;

        return $this;
    }

    /**
     * Get api rates service url.
     *
     * @return string
     */
    public function getApiRatesServiceUrl(): string
    {
        return $this->apiRatesServiceUrl;
    }

    /**
     * Set api rates service url.
     *
     * @param string $apiRatesServiceUrl
     *
     * @return ReviewService
     */
    public function setApiRatesServiceUrl(string $apiRatesServiceUrl): ReviewService
    {
        $this->apiRatesServiceUrl = $apiRatesServiceUrl;

        return $this;
    }

    /**
     * Validate file row data.
     *
     * @param array $rowData
     *
     * @return bool
     */
    public function validate(array $rowData): bool
    {
        return isset($rowData['bin']) && isset($rowData['amount']) && isset($rowData['currency']);
    }

    /**
     * Validate file row data.
     *
     * @param array|null $rowData
     *
     * @return bool|null
     */
    public function isArrayRowData(?array $rowData): ?bool
    {
        return is_array($rowData);
    }

    /**
     * Calculate amount fixed.
     *
     * @param array $rowData
     * @param float $rate
     * @param bool $isEu
     *
     * @return float
     */
    public function calculateAmount(array $rowData, float $rate, bool $isEu): float
    {
        $amountFixed = $rowData['amount'];

        if (($rowData['currency'] !== 'EUR' && $rate > 0)) {
            $amountFixed = $rowData['amount'] / $rate;
        }

        return $amountFixed * ($isEu ? self::PERCENT_EU : self::PERCENT_NOT_EU);
    }

    /**
     * Check code country alpha2 is European.
     *
     * @param string $alpha2
     *
     * @return bool
     */
    public function isEuropeanCode(string $alpha2): bool
    {
        return in_array($alpha2, self::EU_ALPHA2_CODES);
    }

    /**
     * Get data from api service.
     *
     * @param string $apiServiceUrl
     * @param string $method
     * @param array $params
     *
     * @return array
     */
    public function getApiServiceData(string $apiServiceUrl, array $params = [], string $method = 'GET'): array
    {
        $result = '';
        $success = true;
        try {
            $request = $this->guzzle->request($method, $apiServiceUrl, $params);
            $result = json_decode($request->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            $success = false;
        }

        return [
            'success' => $success,
            'result' => $result,
        ];
    }
}
