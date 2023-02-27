<?php
namespace app\helpers;

use Exception;

/**
 * Helper for fill data for time intervals
 */
class DataHelper
{
    /**
     * returns modified $date array by $dateField keys and filled by $emptyFiller values if source data has no needed value
     *
     * @param int|string $from start date (timestamp or date in format 'Y-m-d')
     * @param int|string $to finish date (timestamp or date in format 'Y-m-d')
     * @param array $emptyFiller array of values which will be set for every item in result data array (if item no has needed key in data array)
     * @param array $data main array with data
     * @param null $dateField key with date
     * @return array
     * @throws Exception
     */
    public static function fillData($from, $to, array $emptyFiller = [], array $data = [], $dateField = null): array
    {
        $resultData = [];

        $dateFrom = is_numeric($from) ? date('Y-m-d', $from) : $from;
        $dateTo = is_numeric($to) ? date('Y-m-d', $to) : $to;

        $endDate = new \DateTime($dateTo);
        $endDate->modify('+1 day');
        $period = new \DatePeriod(
            new \DateTime($dateFrom),
            new \DateInterval('P1D'),
            $endDate
        );

        foreach ($period as $item){
            $date = $item->format('Y-m-d');
            $resultData[$date] = $emptyFiller;
            $resultData[$date]['date'] = $date;
        }

        if (!empty($data) && !is_null($dateField)){
            foreach ($data as $dataItem){
                $date = $dataItem->{$dateField};
                $resultData[$date] = $dataItem;
            }
        }

        return $resultData;
    }

}