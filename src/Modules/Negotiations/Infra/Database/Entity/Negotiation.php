<?php

namespace App\Modules\Negotiations\Infra\Database\Entity;


use App\Modules\Domain\Infra\Database\Entity\Reasons;
use App\Modules\Domain\Infra\Database\Entity\RequestSource;
use App\Modules\Domain\Infra\Database\Entity\RequestType;
use App\Modules\Domain\Infra\Database\Entity\Situation;
use App\Modules\Occurrences\Infra\Database\Entity\Occurrence;
use App\Modules\Users\Infra\Database\Entity\User;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Record;
use App\Shared\Infra\Database\Repository;
use Exception;

class Negotiation extends Record
{
    const TABLENAME = 'negociacao';

    protected ?TransferNegotiation $transferNegotiation = null;

    /**
     * @throws Exception
     */
    public function restore(): Negotiation
    {
        switch ($this->situacao_id) {
            case 2:
            case 16:
                $this->delReference(CancelContract::class);
                break;

            case 6:
                $this->delReference(RetentionContract::class);
                break;

            case 7:
                $this->delReference(DowngradeContract::class);
                break;

            default:
                break;
        }

        $this->situacao_id = 1;
        $this->data_finalizacao = null;
        $this->reembolso = '0';
        $this->numero_pc = '0';
        $this->taxas_extras = '0';
        $this->valor_primeira_parcela = '0';
        $this->observacao = '';

        return $this;
    }

    /**
     * @throws Exception
     */
    public function delReference(string $activeRecord)
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('negociacao_id', '=', $this->id));
        $repository = new Repository($activeRecord);
        return $repository->delete($criteria);
    }

    public function transfer(TransferNegotiation $transferNegotiation): void
    {
        $this->transferNegotiation = $transferNegotiation;
        $this->transferNegotiation->negociacao_id = $this->id;
        $this->transferNegotiation->usuario_antigo_id = $this->usuario_id;
        $this->transferNegotiation->data_transferencia = date(CONF_DATE_APP);
    }

    public function store()
    {
        if ($this->transferNegotiation) {
            $this->usuario_id = $this->transferNegotiation->usuario_novo_id;
            $this->transferida = true;
            $this->transferNegotiation->store();
        }

        $this->reembolso = isset($this->reembolso) ? str_format_currency($this->reembolso) : null;
        $this->taxas_extras = isset($this->taxas_extras) ? str_format_currency($this->taxas_extras) : null;
        $this->valor_primeira_parcela = isset($this->valor_primeira_parcela) ? str_format_currency($this->valor_primeira_parcela) : null;
        $this->data_finalizacao = isset($this->data_finalizacao) ? date("Y-m-d", strtotime($this->data_finalizacao)) : null;
        return parent::store();
    }

    /**
     * @throws Exception
     */
    public function toArray(): array
    {
        $this->origem = (new RequestSource($this->origem_id))->toArray();
        $this->tipo_solicitacao = (new RequestType($this->tipo_solicitacao_id))->toArray();
        $this->motivo = (new Reasons($this->motivo_id))->toArray();
        $this->ocorrencia = (new Occurrence($this->ocorrencia_id))->toArray();
        $this->usuario = (new User($this->usuario_id))->toArray();
        $this->situacao = (new Situation($this->situacao_id))->toArray();
        return parent::toArray();
    }
}