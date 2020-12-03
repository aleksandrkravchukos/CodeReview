<?php declare(strict_types=1);

namespace Review;

use Exception;

require __DIR__ . '/vendor/autoload.php';

$inputFile = $argv[1];

$authBin = [];
$authRates = [];
$apiBinUrl = '';
$apiRatesUrl = '';
foreach ($argv as $parameter) {

    if (strpos($parameter, '--authBinLogin') !== false) {
        $authBin['auth']['login'] = explode('=', $parameter)[1];
    }

    if (strpos($parameter, '--authBinPassword') !== false) {
        $authBin['auth']['password'] = explode('=', $parameter)[1];
    }

    if (strpos($parameter, '--authBinType') !== false) {
        $authBin['auth']['type'] = explode('=', $parameter)[1];
    }

    if (strpos($parameter, '--authRatesLogin') !== false) {
        $authRates['auth']['login'] = explode('=', $parameter)[1];
    }

    if (strpos($parameter, '--authRatesPassword') !== false) {
        $authRates['auth']['password'] = explode('=', $parameter)[1];
    }

    if (strpos($parameter, '--authRatesType') !== false) {
        $authRates['auth']['type'] = explode('=', $parameter)[1];
    }

    if (strpos($parameter, '--apiBinUrl') !== false) {
        $apiBinUrl = explode('=', $parameter)[1];
    }

    if (strpos($parameter, '--apiRatesUrl') !== false) {
        $apiRatesUrl = explode('=', $parameter)[1];
    }
}

$service = new Service\ReviewService();
$fileService = new Service\FileService();

$fileService->setInputFile($inputFile);
$api = $service->getApiBinServiceUrl();
$ratesApi = $service->getApiRatesServiceUrl();

$authApiBin = [];
$paramsBin = [];
if ($apiBinUrl !== '') {
    $api = $apiBinUrl;
    $service->setApiBinServiceUrl($apiBinUrl);

    if (isset($authBin['auth']['login']) && isset($authBin['auth']['password']) && isset($authBin['auth']['type'])) {
        $authApiBin = [
            'auth' => [
                $authBin['auth']['login'],
                $authBin['auth']['password'],
                $authBin['auth']['type'],
            ]
        ];
    }
}

$paramsBin = array_merge($paramsBin, $authApiBin);

$authApiRates = [];
$paramsRates = [];
if ($apiRatesUrl !== '') {
    $ratesApi = $apiRatesUrl;
    $service->setApiRatesServiceUrl($apiRatesUrl);

    if (isset($authBin['auth']['login']) && isset($authBin['auth']['password']) && isset($authBin['auth']['type'])) {
        $authApiRates = [
            'auth' => [
                $paramsRates['auth']['login'],
                $paramsRates['auth']['password'],
                $paramsRates['auth']['type'],
            ]
        ];
    }
}

$paramsRates = array_merge($paramsRates, $authApiRates);

$amountFixedResult = [];
$errors = [];
if ($fileService->checkFileExist($inputFile) && $fileService->checkFileIsReadable($inputFile)) {
    $content = explode(PHP_EOL, $fileService->getFileContent($inputFile));

    foreach ($content as $row) {
        $rowData = '';
        if ($service->isJsonString(trim($row))) {
            $rowData = json_decode($row, true);
        }

        if ($service->isArrayRowData($rowData) && $service->validate($rowData)) {
            try {
                $binResults = $service->getApiServiceData($api. $rowData['bin'], $paramsBin);
                if (!$binResults['success']) {
                    $errors[] = 'bin ' . $rowData['bin'] . ' have incorrect results from bit api';
                } else {

                    $decodedResults = $binResults['result'];

                    $getRate = $service->getApiServiceData($ratesApi, $paramsRates);

                    if (!$getRate['success']) {
                        $errors[] = 'bin ' . $rowData['bin'] . ' have incorrect results rates api';
                    } else {
                        $rate = 0;
                        if (isset($getRate['result']['rates'][$rowData['currency']])) {
                            $rate = $getRate['result']['rates'][$rowData['currency']];
                        }

                        $isEu = false;

                        if (isset($decodedResults['country']['alpha2'])) {
                            $isEu = $service->isEuropeanCode($decodedResults['country']['alpha2']);
                        }

                        $amountFixedResult[] = $service->calculateAmount($rowData, $rate, $isEu);
                    }
                }
            } catch (Exception $exception) {
                $errors[] = $exception->getMessage();
            }
        } else {
            $errors[] = $row . ' is not valid';
        }
    }

} else {
    $errors[] = "File doesn't exist or not readable";
}
if ($errors) {
    print_r($errors);
}

if ($amountFixedResult) {
    print_r($amountFixedResult);
}