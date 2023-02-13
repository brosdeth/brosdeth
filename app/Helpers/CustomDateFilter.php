<?php

namespace App\Helpers;

use Carbon\Carbon;

class CustomDateFilter
{
    protected static $formartDate = 'Y-m-d';

    public static function dateFilter($key)
    {
        $date = Carbon::now();
        switch ($key) {
            case 'this_day':
                return [$date->format(self::$formartDate), $date->format(self::$formartDate)];
                break;
                // case 'tomorrow':
                //     return [Carbon::tomorrow()->format(self::$formartDate),Carbon::tomorrow()->format(self::$formartDate)];
                //     break;
            case 'yesterday':
                return [Carbon::yesterday()->format(self::$formartDate), Carbon::yesterday()->format(self::$formartDate)];
                break;
            case 'this_week':
                $weekStartDate = $date->startOfWeek()->format(self::$formartDate);
                $weekEndDate = $date->endOfWeek()->format(self::$formartDate);
                return [$weekStartDate, $weekEndDate];
                break;
            case 'last_week':
                $weekStartDate = $date->startOfWeek()->subWeek()->format(self::$formartDate);
                $weekEndDate = $date->endOfWeek()->format(self::$formartDate);
                return [$weekStartDate, $weekEndDate];
                break;
            case 'this_month':
                $start = $date->startOfMonth()->format(self::$formartDate);
                $end = $date->lastOfMonth()->format(self::$formartDate);
                return [$start, $end];
                break;
            case 'last_month':
                $start = $date->startOfMonth()->subMonth()->format(self::$formartDate);
                $end = $date->lastOfMonth()->format(self::$formartDate);
                return [$start, $end];
                break;
            case 'this_quarter':
                $start = $date->startOfQuarter()->format(self::$formartDate); // the actual start of quarter method
                $end = $date->endOfQuarter()->format(self::$formartDate);
                return [$start, $end];
                break;
            case 'last_quarter':
                $start = $date->startOfQuarter()->subQuarters()->format(self::$formartDate);
                $end = $date->endOfQuarter()->format(self::$formartDate);
                return [$start, $end];
                break;
            case 'this_year':
                $start = $date->firstOfYear()->format(self::$formartDate);
                $end = $date->endOfYear()->format(self::$formartDate);
                return [$start, $end];
                break;
            case 'last_year':
                $start = $date->firstOfYear()->subYear()->format(self::$formartDate);
                $end = $date->endOfYear()->format(self::$formartDate);
                return [$start, $end];
                break;
            default:
                return [$date->format(self::$formartDate), $date->format(self::$formartDate)];
        }
    }

    public static function getKeywordDate()
    {
        return [
            'group' => [
                [
                    'title_en' => 'Day',
                    'title_km' => 'ថ្ងៃ',
                    'keywords' => [
                        ['keyword' => 'this_day', 'title_en' => 'This Day', 'title_km' => 'ថ្ងៃនេះ', 'data' => self::dateFilter('this_day')],
                        ['keyword' => 'yesterday', 'title_en' => 'Yesterday', 'title_km' => 'ម្សិលមិញ', 'data' => self::dateFilter('yesterday')],
                        ['keyword' => 'this_week', 'title_en' => 'This Week', 'title_km' => 'ស​ប្តា​ហ៍​នេះ', 'data' => self::dateFilter('this_week')],
                        ['keyword' => 'last_week', 'title_en' => 'Last Week', 'title_km' => 'សប្ដាហ៍​មុន', 'data' => self::dateFilter('last_week')],
                    ],
                ],
                [
                    'title_en' => 'Month & Quarter',
                    'title_km' => 'ខែ​ & ត្រីមាស',
                    'keywords' => [
                        ['keyword' => 'this_month', 'title_en' => 'This Month', 'title_km' => 'ខែ​នេះ', 'data' => self::dateFilter('this_month')],
                        ['keyword' => 'last_month', 'title_en' => 'Last Month', 'title_km' => 'ខែមុន', 'data' => self::dateFilter('last_month')],
                        ['keyword' => 'this_quarter', 'title_en' => 'This Quarter', 'title_km' => 'ត្រីមាសនេះ', 'data' => self::dateFilter('this_quarter')],
                        ['keyword' => 'last_quarter', 'title_en' => 'Last Quarter', 'title_km' => 'ត្រីមាសមុន', 'data' => self::dateFilter('last_quarter')],
                    ],
                ],
                [
                    'title_en' => 'Year',
                    'title_km' => 'ឆ្នាំ',
                    'keywords' => [
                        ['keyword' => 'this_year', 'title_en' => 'This Year', 'title_km' => 'ឆ្នាំ​នេះ', 'data' => self::dateFilter('this_year')],
                        ['keyword' => 'last_year', 'title_en' => 'Last Year', 'title_km' => 'ឆ្នាំមុន', 'data' => self::dateFilter('last_year')],
                    ],
                ],
            ],
            'keywords' => [
                ['keyword' => 'this_day', 'title_en' => 'This Day', 'title_km' => 'ថ្ងៃនេះ', 'data' => self::dateFilter('this_day')],
                ['keyword' => 'yesterday', 'title_en' => 'Yesterday', 'title_km' => 'ម្សិលមិញ', 'data' => self::dateFilter('yesterday')],
                ['keyword' => 'this_week', 'title_en' => 'This Week', 'title_km' => 'ស​ប្តា​ហ៍​នេះ', 'data' => self::dateFilter('this_week')],
                ['keyword' => 'last_week', 'title_en' => 'Last Week', 'title_km' => 'សប្ដាហ៍​មុន', 'data' => self::dateFilter('last_week')],
                ['keyword' => 'this_month', 'title_en' => 'This Month', 'title_km' => 'ខែ​នេះ', 'data' => self::dateFilter('this_month')],
                ['keyword' => 'last_month', 'title_en' => 'Last Month', 'title_km' => 'ខែមុន', 'data' => self::dateFilter('last_month')],
                ['keyword' => 'this_quarter', 'title_en' => 'This Quarter', 'title_km' => 'ត្រីមាសនេះ', 'data' => self::dateFilter('this_quarter')],
                ['keyword' => 'last_quarter', 'title_en' => 'Last Quarter', 'title_km' => 'ត្រីមាសមុន', 'data' => self::dateFilter('last_quarter')],
                ['keyword' => 'this_year', 'title_en' => 'This Year', 'title_km' => 'ឆ្នាំ​នេះ', 'data' => self::dateFilter('this_year')],
                ['keyword' => 'last_year', 'title_en' => 'Last Year', 'title_km' => 'ឆ្នាំមុន', 'data' => self::dateFilter('last_year')],
            ]
        ];
    }
    public static function thisMonth()
    {
        $date = Carbon::now();
        $start = $date->startOfMonth()->format(self::$formartDate);
        $end = $date->lastOfMonth()->format(self::$formartDate);
        return [$start, $end];
    }

    public static function thisDay()
    {
        $date = Carbon::now();
        return [$date->format(self::$formartDate), $date->format(self::$formartDate)];
    }
}
