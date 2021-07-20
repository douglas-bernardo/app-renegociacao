<?php

namespace App\Modules\Occurrences\Infra\Database\Entity;


use App\Modules\Domain\Infra\Database\Entity\Product;
use App\Modules\Domain\Infra\Database\Entity\StatusOccurrence;
use App\Shared\Infra\Database\Record;
use Exception;

class Occurrence extends Record
{
    const TABLENAME = 'ocorrencia';

    /**
     * @throws Exception
     */
    public function toArray(): array
    {
        $this->status_ocorrencia = (new StatusOccurrence($this->status_ocorrencia_id))->toArray();
        if ($this->idprojetots) {
            $this->produto = (new Product())->loadBy('idprojetots', $this->idprojetots)->toArray();
        }
        return parent::toArray();
    }
}