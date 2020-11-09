<?php
declare(strict_types=1);

namespace App\Application\Actions\Company;

use Psr\Http\Message\ResponseInterface as Response;

class ListCompaniesAction extends CompanyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $companies = $this->companyRepository->findAll();

        $this->logger->info("Get Companies list");

        return $this->respondWithData($companies);
    }
}
