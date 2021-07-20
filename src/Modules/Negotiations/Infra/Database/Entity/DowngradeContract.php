<?php

namespace App\Modules\Negotiations\Infra\Database\Entity;

use App\Shared\Infra\Database\Record;

class DowngradeContract extends Record
{
    const TABLENAME = 'reversao';

    public function store()
    {
        $this->valor_venda = str_format_currency($this->valor_venda);
        return parent::store();
    }
}