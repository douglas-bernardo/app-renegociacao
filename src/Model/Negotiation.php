<?php

namespace App\Model;

use App\Database\Record;

class Negotiation extends Record
{
    const TABLENAME = 'negociacao';

    public function toArray(): array
    {
        $this->origem = (new RequestSource($this->origem_id))->toArray();
        $this->tipo_solicitacao = (new RequestType($this->tipo_solicitacao_id))->toArray();
        $this->motivo = (new Reasons($this->motivo_id))->toArray();
        $this->ocorrencia = (new Occurrence($this->ocorrencia_id))->toArray();
        $this->usuario = (new User($this->usuario_id))->toArray();
        $this->situacao = (new Situation($this->situacao_id))->toArray();
//        unset($this->origem_id);
//        unset($this->tipo_solicitacao_id);
//        unset($this->motivo_id);
//        unset($this->ocorrencia_id);
//        unset($this->usuario_id);
//        unset($this->situacao_id);
        return parent::toArray();
    }
}