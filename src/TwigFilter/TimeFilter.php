<?php

namespace App\TwigFilter;

use App\Service\TimeService;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeFilter extends AbstractExtension
{
    public function getFilters():array{
        return [
            new TwigFilter('stringTime', [$this, 'stringTime']),
        ];
    }

    public function stringTime(string $time):string{
        $timeService = new TimeService();
        return $timeService->convertIntTimeToString($time);
    }
}