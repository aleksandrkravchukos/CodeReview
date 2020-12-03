<?php declare(strict_types=1);

namespace Review\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiService
{
    const DEFAULT_API_BIN_SERVICE   = 'https://lookup.binlist.net/';
    const DEFAULT_API_RATES_SERVICE = 'https://api.exchangeratesapi.io/latest/';

    /**
     * @var string
     */
    private string $apiBinServiceUrl;

    /**
     * @var string
     */
    private $apiRatesServiceUrl;

    /**
     * @var Client
     */
    private $guzzle;

    /**
     * ApiService constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->guzzle = $client;
        $this->setApiBinServiceUrl(self::DEFAULT_API_BIN_SERVICE);
        $this->setApiRatesServiceUrl(self::DEFAULT_API_RATES_SERVICE);
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
     * @return ApiService
     */
    public function setApiBinServiceUrl(string $apiBinServiceUrl): ApiService
    {
        $this->apiBinServiceUrl = $apiBinServiceUrl;

        return $this;
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
        $result  = '';
        $success = true;
        try {
            $request = $this->guzzle->request($method, $apiServiceUrl, $params);
            $result  = json_decode($request->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            $success = false;
        }

        return [
            'success' => $success,
            'result'  => $result,
        ];
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
     * @return ApiService
     */
    public function setApiRatesServiceUrl(string $apiRatesServiceUrl): ApiService
    {
        $this->apiRatesServiceUrl = $apiRatesServiceUrl;

        return $this;
    }
}
