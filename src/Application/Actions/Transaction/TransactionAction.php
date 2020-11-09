<?php
declare(strict_types=1);

namespace App\Application\Actions\Transaction;

use App\Application\Actions\Action;
use App\Application\Repositories\TransactionRepository;
use App\Application\Repositories\UserRepository;
use Psr\Log\LoggerInterface;

abstract class TransactionAction extends Action
{
    /**
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param LoggerInterface $logger
     * @param TransactionRepository  $transactionRepository
     */
    public function __construct(LoggerInterface $logger, TransactionRepository $transactionRepository, UserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }
}
