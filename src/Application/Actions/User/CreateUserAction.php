<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $companyId = (int) $this->resolveArg('id');

        $company = $this->companyRepository->findById($companyId);

        $data = array_merge(['company_id' => $company['id']], $this->getFormData());
        $user = $this->userRepository->create($data);

        $this->logger->info("User: `{$user['email']}` from company `{$company['name']}` created");

        return $this->respondWithData($user);
    }
}
