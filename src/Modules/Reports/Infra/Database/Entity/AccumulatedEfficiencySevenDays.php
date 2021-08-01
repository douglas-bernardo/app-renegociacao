<?php


namespace App\Modules\Reports\Infra\Database\Entity;


use App\Shared\Infra\Database\Record;

class AccumulatedEfficiencySevenDays extends Record
{
    const TABLENAME = 'vw_accumulated_efficiency_seven_days';
}