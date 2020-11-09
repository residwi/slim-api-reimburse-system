<?php
declare(strict_types=1);

namespace App\Application\Actions\Company;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateCompanyAction extends CompanyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $data = array_merge(['id' => $id], $this->getFormData());
        $company = $this->companyRepository->update($data);

        $this->logger->info("Company: `{$company['name']}` updated");

        return $this->respondWithData($company);
    }
}
