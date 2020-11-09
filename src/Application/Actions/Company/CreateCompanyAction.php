<?php
declare(strict_types=1);

namespace App\Application\Actions\Company;

use Psr\Http\Message\ResponseInterface as Response;

class CreateCompanyAction extends CompanyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        $company = $this->companyRepository->create($data);

        $this->logger->info("Company: `{$company['name']}` created");

        return $this->respondWithData($company);
    }
}
