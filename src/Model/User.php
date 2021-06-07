<?php

namespace App\Model;

use App\Database\Record;

class User extends Record
{
    const TABLENAME = 'usuario';

    public function toArray(): array
    {
        unset($this->password);
        unset($this->created_at);
        return parent::toArray();
    }
}
