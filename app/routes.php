<?php
declare(strict_types=1);

use App\Application\Actions\Company\CreateCompanyAction;
use App\Application\Actions\Company\DeleteCompanyAction;
use App\Application\Actions\Company\ListCompaniesAction;
use App\Application\Actions\Company\UpdateCompanyAction;
use App\Application\Actions\Company\ViewCompanyAction;
use App\Application\Actions\CompanyBudget\ListCompaniesBudgetAction;
use App\Application\Actions\CompanyBudget\ViewCompanyBudgetAction;
use App\Application\Actions\Transaction\CloseTransactionAction;
use App\Application\Actions\Transaction\DisburseTransactionAction;
use App\Application\Actions\Transaction\ListTransactionsAction;
use App\Application\Actions\Transaction\ReimburseTransactionAction;
use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\DeleteUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/transactions', ListTransactionsAction::class);

    $app->group('/users', function (Group $group) {
        // user transactions
        $group->get('/{id}/reimburse', ReimburseTransactionAction::class);
        $group->get('/{id}/disburse', DisburseTransactionAction::class);
        $group->get('/{id}/close', CloseTransactionAction::class);

        // crud user
        $group->get('', ListUsersAction::class);
        $group->get('/{emailOrId}', ViewUserAction::class);
        $group->put('/{id}', UpdateUserAction::class);
        $group->delete('/{id}', DeleteUserAction::class);
    });

    $app->group('/companies', function (Group $group) {
        // get company budget
        $group->get('/budget', ListCompaniesBudgetAction::class);
        $group->get('/{id}/budget', ViewCompanyBudgetAction::class);

        // crud company
        $group->get('', ListCompaniesAction::class);
        $group->get('/{id}', ViewCompanyAction::class);
        $group->post('', CreateCompanyAction::class);
        $group->put('/{id}', UpdateCompanyAction::class);
        $group->delete('/{id}', DeleteCompanyAction::class);

        // create user company
        $group->post('/{id}/users', CreateUserAction::class);
    });
};
