<?php declare(strict_types=1);

namespace Review;

use Exception;

require __DIR__ . '/vendor/autoload.php';

$inputFile = $argv[1];

$service = new Service\ReviewService();

$service->setInputFile($inputFile);
$api = $service->getApiBinServiceUrl();
$ratesApi = $service->getApiRatesServiceUrl();

if (isset($argv[2])) {
    $api = $argv[2];
    $service->setApiBinServiceUrl($argv[2]);
}

if (isset($argv[3])) {
    $ratesApi = $argv[3];
    $service->setApiRatesServiceUrl($argv[2]);
}

$amountFixedResult = [];
$errors = [];
if ($service->checkFileExist() && $service->checkFileIsReadable()) {
    $content = explode(PHP_EOL, $service->getFileContent());

    foreach ($content as $row) {
        $rowData = '';
        if ($service->isJsonString(trim($row))) {
            $rowData = json_decode($row, true);
        }

        if ($service->isArrayRowData($rowData) && $service->validate($rowData)) {
            try {
                $binResults = file_get_contents($api . $rowData['bin']);

                if (!$binResults) {
                    $errors[] = 'bin ' . $rowData['bin'] . ' have no results';
                } else {

                    $decodedResults = @json_decode($binResults);

                    $getRate = @json_decode(file_get_contents($ratesApi), true);

                    $rate = 0;
                    if (isset($getRate['rates'][$rowData['currency']])) {
                        $rate = $getRate['rates'][$rowData['currency']];
                    }

                    $isEu = false;
                    if (isset($decodedResults->country->alpha2)) {
                        $isEu = $service->isEuropeanCode($decodedResults->country->alpha2);
                    }

                    $amountFixedResult[] = $service->calculateAmount($rowData, $rate, $isEu);
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