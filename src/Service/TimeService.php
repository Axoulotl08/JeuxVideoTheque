<?php

namespace App\Service;

class TimeService
{
    public function convertIntTimeToString(int $time): string{
        return str_pad(intdiv($time, 60), 2, '0', STR_PAD_LEFT)
            .':'.str_pad($time % 60, 2, '0', STR_PAD_LEFT);
    }

    public function convertStringTimetoInt(string $time): int{
        $split = explode(':', $time);
        return ($split[0] * 60) + $split[1];
    }

}