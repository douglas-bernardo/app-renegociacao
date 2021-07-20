<?php

namespace App\Modules\Negotiations\Infra\Database\Entity;

use App\Shared\Infra\Database\Record;

class CancelContract extends Record
{
    const TABLENAME = 'cancelamento';

    public function store()
    {
        $this->multa = isset($this->multa) ? str_format_currency($this->multa) : null;
        return parent::store();
    }
}