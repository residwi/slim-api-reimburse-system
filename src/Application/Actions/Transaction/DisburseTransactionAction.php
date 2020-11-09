<?php
declare(strict_types=1);

namespace App\Application\Actions\Transaction;

use Psr\Http\Message\ResponseInterface as Response;

class DisburseTransactionAction extends TransactionAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        $userId = (int) $this->resolveArg('id');

        $user = $this->userRepository->findById($userId);

        $data = array_merge([
            'company_id' => $user['company']['id'],
            'user_id' => $user['id'],
            'type' => 'C'
        ], $this->getFormData());

        $transaction = $this->transactionRepository->create($data);

        $this->logger->info("User: `{$user['firstname']}` do disbursement transaction");

        return $this->respondWithData($transaction);
    }
}
