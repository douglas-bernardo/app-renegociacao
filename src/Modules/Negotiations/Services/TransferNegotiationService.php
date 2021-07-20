<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Negotiations\Infra\Database\Entity\Negotiation;
use App\Modules\Negotiations\Infra\Database\Entity\TransferNegotiation;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Modules\Users\Infra\Database\Entity\User;
use App\Shared\Errors\ApiException;
use Exception;

class TransferNegotiationService
{
    /**
     * @var INegotiationRepository
     */
    private INegotiationRepository $negotiationRepository;

    /**
     * ListNegotiationService constructor.
     * @param INegotiationRepository $negotiationRepository
     */
    public function __construct(INegotiationRepository $negotiationRepository)
    {
        $this->negotiationRepository = $negotiationRepository;
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function execute(int $id, array $data, int $userIdInclusion): Negotiation
    {
        $negotiation = $this->negotiationRepository->load($id);

        if (!$negotiation) {
            throw new ApiException("Negociação não encontrada");
        }

        if ((int) $negotiation->situacao_id !== 1) {
            throw new ApiException("Negociação já finalizada. Não é permitido a transferência");
        }

        $newUserResp = new User($data['usuario_novo_id']);

        if (!$newUserResp) {
            throw new ApiException("Novo usuário responsável não encontrado");
        }

        if ($negotiation->usuario_id === $newUserResp->id) {
            throw new ApiException("Negociação já foi transferida ou já pertence ao usuário escolhido");
        }

        if (!$newUserResp->ativo) {
            throw new ApiException("Novo usuário responsável inativo. Não é permitido a transferência");
        }

        $userRoles = [];
        foreach ($newUserResp->getRoles() as $role) $userRoles[] = $role->alias;
        if (!in_array('ROLE_CONSULTOR', $userRoles)) {
            throw new ApiException("Tipo de usuário para transferência incompatível.");
        }

        $transfer = new TransferNegotiation();
        $transfer->fromArray($data);
        $transfer->usuario_inclusao_id = $userIdInclusion;

        $negotiation->transfer($transfer);
        $negotiation->store();

        return $negotiation;
    }
}