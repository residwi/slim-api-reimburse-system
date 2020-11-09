<?php
declare(strict_types=1);

namespace App\Application\Actions\CompanyBudget;

use Psr\Http\Message\ResponseInterface as Response;

class ViewCompanyBudgetAction extends CompanyBudgetAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $companyBudget = $this->companyBudgetRepository->findById($id);

        $this->logger->info("Get Company Budget: `{$companyBudget['company']}`");

        return $this->respondWithData($companyBudget);
    }
}
