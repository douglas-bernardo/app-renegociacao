<?php

namespace App\Modules\Negotiations\Infra\Database\Entity;

use App\Modules\Users\Infra\Database\Entity\User;
use App\Shared\Infra\Database\Record;

class TransferNegotiation extends Record
{
    const TABLENAME = 'transferencia_negociacao';
}