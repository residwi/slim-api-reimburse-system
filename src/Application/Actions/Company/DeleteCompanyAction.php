<?php
declare(strict_types=1);

namespace App\Application\Actions\Company;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteCompanyAction extends CompanyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $company = $this->companyRepository->delete($id);

        $this->logger->info("Company deleted");

        return $this->respondWithData($company);
    }
}
