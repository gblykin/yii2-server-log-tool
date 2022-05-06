<?php
namespace app\helpers;

class DataHelper
{
    public static function fillData($from, $to, $emptyFiller = [], $data = [], $dateField = null){
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