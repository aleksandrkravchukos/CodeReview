<?php declare(strict_types=1);

namespace Review\Service;

class ReviewService
{
    const EU_ALPHA2_CODES = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'];

    const PERCENT_EU     = "0.01";
    const PERCENT_NOT_EU = "0.02";
    const EUR            = "EUR";

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
     * @param string $rate
     * @param bool $isEu
     * @param int $scale
     *
     * @return string
     */
    public function calculateAmount(array $rowData, string $rate, bool $isEu, int $scale): string
    {
        $amountFixed = strval($rowData['amount']);

        if (($rowData['currency'] !== 'EUR' && $rate > 0)) {
            $amountFixed = bcdiv(strval($rowData['amount']), $rate, $scale);
        }

        return bcmul($amountFixed, ($isEu ? strval(self::PERCENT_EU) : strval(self::PERCENT_NOT_EU)), $scale);
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
}
