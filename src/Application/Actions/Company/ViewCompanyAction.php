<?php
declare(strict_types=1);

namespace App\Application\Actions\Company;

use Psr\Http\Message\ResponseInterface as Response;

class ViewCompanyAction extends CompanyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $company = $this->companyRepository->findById($id);

        $this->logger->info("Get Company: `{$company['name']}`");

        return $this->respondWithData($company);
    }
}
