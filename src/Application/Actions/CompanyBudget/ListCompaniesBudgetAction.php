<?php
declare(strict_types=1);

namespace App\Application\Actions\CompanyBudget;

use Psr\Http\Message\ResponseInterface as Response;

class ListCompaniesBudgetAction extends CompanyBudgetAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $companies = $this->companyBudgetRepository->findAll();

        $this->logger->info("Get Companies Budget list");

        return $this->respondWithData($companies);
    }
}
