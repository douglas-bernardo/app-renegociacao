<?php

namespace App\Model;

use App\Database\Record;

class Occurrence extends Record
{
    const TABLENAME = 'ocorrencia';

    public function toArray(): array
    {
        $this->status_ocorrencia = (new StatusOccurrence($this->status_ocorrencia_id))->toArray();
        if ($this->idprojetots) {
            $this->produto = (new Product())->loadBy('idprojetots', $this->idprojetots)->toArray();
        }
        return parent::toArray();
    }
}