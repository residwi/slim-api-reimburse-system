<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $data = array_merge(['id' => $id], $this->getFormData());
        $user = $this->userRepository->update($data);

        $this->logger->info("User: `{$user['email']}` updated");

        return $this->respondWithData($user);
    }
}
