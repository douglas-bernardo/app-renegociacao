<?php


namespace App\Modules\Reports\Infra\Database\Entity;


use App\Shared\Infra\Database\Record;

class RetentionDowngradeBalance extends Record
{
    const TABLENAME = 'vw_retention_downgrade_balance';
}