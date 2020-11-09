<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $emailOrId = $this->resolveArg('emailOrId');

        if (filter_var($emailOrId, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userRepository->findByEmail($emailOrId);
        } else {
            $user = $this->userRepository->findById($emailOrId);
        }
        
        $this->logger->info("Get User: `{$user['email']}`");

        return $this->respondWithData($user);
    }
}
