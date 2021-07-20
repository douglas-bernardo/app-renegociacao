<?php


namespace App\Modules\Users\Repositories;


interface IUserTokensRepository
{
    public function generate();
}