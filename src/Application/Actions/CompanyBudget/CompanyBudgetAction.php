<?php
declare(strict_types=1);

namespace App\Application\Actions\CompanyBudget;

use App\Application\Actions\Action;
use App\Application\Repositories\CompanyBudgetRepository;
use Psr\Log\LoggerInterface;

abstract class CompanyBudgetAction extends Action
{
    /**
     * @var CompanyBudgetRepository
     */
    protected $companyBudgetRepository;

    /**
     * @param LoggerInterface $logger
     * @param CompanyBudgetRepository  $companyBudgetRepository
     */
    public function __construct(LoggerInterface $logger, CompanyBudgetRepository $companyBudgetRepository)
    {
        parent::__construct($logger);
        $this->companyBudgetRepository = $companyBudgetRepository;
    }
}
