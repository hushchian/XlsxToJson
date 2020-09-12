<?php

namespace App;

class PriceCalculator
{
    /**
     * @var mixed float
     */
    private $usdRate;

    public function __construct()
    {
        $privatCourse = json_decode(
            file_get_contents('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5'),
            true
        );
        foreach ($privatCourse as $course) {
            if ($course['ccy'] !== 'USD'){
                continue;
            }

            $this->usdRate = $course['sale'];
            break;
        }
    }

    /**
     * @param float $usd
     * @return float
     */
    public function getUahFromUsd(float $usd): float
    {
        return $usd * $this->usdRate;
    }

    /**
     * @param float $uah
     * @return float
     */
    public function getUsdFromUah(float $uah): float
    {
        return $uah / $this->usdRate;
    }
}