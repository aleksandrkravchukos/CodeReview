<?php declare(strict_types=1);

namespace Review;

use Exception;
use Review\Service\ReviewService;

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

$errors = [];

if ($service->checkFileExist() && $service->checkFileIsReadable()) {
    $content = explode("\n", $service->getFileContent());

    foreach ($content as $row) {
        $rowData = '';
        if ($service->isJsonString(trim($row))) {
            $rowData = json_decode($row);
        }

        if ($service->isValidXML(trim($row))) {
            // TODO: implement xml input data
        }

        if (isset($rowData->bin) && isset($rowData->amount) && isset($rowData->currency)) {
            try {
                $binResults = file_get_contents($api . $rowData->bin);
            } catch (Exception $exception) {
                $errors[] = $exception->getMessage();
                continue;
            }

            if (!$binResults) {
                $errors[] = 'bin ' . $rowData['bin'] . ' have no results';
            } else {

                $decodedResults = json_decode($binResults);
                $isEu = in_array($decodedResults->country->alpha2, ReviewService::EU_ALPHA2_CODES);

                try {
                    $getRate = @json_decode(file_get_contents($ratesApi), true);
                } catch (Exception $exception) {
                    $errors[] = 'Response from rates api - ' . $exception->getMessage();
                    continue;
                }
                $rate = 0;
                if (isset($getRate[['rates'][$rowData->currency]])) {
                    $rate = $getRate[['rates'][$rowData->currency]];
                }

                $amountFixed = $rowData->amount;

                if ($rowData->currency !== 'EUR' && $rate > 0) {
                    $amountFixed = $rowData->amount / $rate;
                }

                echo $amountFixed * ($isEu ? 0.01 : 0.02) . PHP_EOL;
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