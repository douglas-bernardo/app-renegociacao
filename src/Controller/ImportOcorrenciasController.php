<?php

namespace App\Controller;

use App\Database\Transaction;
use Exception;
use App\Model\Ocorrencia;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImportOcorrenciasController
{
    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $lastOcorrenciaId = (new Ocorrencia())->getLast();
            $lastOcorrenciaObject = new Ocorrencia($lastOcorrenciaId);

            if ($lastOcorrenciaObject->toArray()) {
                $num_ocorrencia = $lastOcorrenciaObject->numero_ocorrencia;
                $url = "http://api.timesharing/ocorrencias/list-after-number/{$num_ocorrencia}";
            } else {
                $url = "http://api.timesharing/ocorrencias/list-after-date?date=2020-07-01";
            }

            $client = HttpClient::create();
            $response = $client->request('GET', $url);
            $ocorrencias = $response->toArray();

            foreach ($ocorrencias['data'] as $arrayOcorrencia) {
                $ocorrencia = new Ocorrencia();
                $ocorrencia->fromArray($arrayOcorrencia);
                $ocorrencia->situacao_id = 1;
                $ocorrencia->store();
                unset($ocorrencia);
            }

            Transaction::close();

            return new JsonResponse([
                'success' =>  count($ocorrencias['data']) . ' imported.'
            ]);
        } catch (Exception $e) {
            Transaction::rollback();
            echo $e->getMessage();
        }
    }
}
