<?php
declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\DomainException\DomainRecordNotFoundException;
use PDO;

class CompanyBudgetRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $sql = "SELECT 
                c.name as company_name, cb.amount as company_budget
                FROM companies_budget cb 
                INNER JOIN companies c 
                ON cb.company_id = c.id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $this->_relationship($result);
    }

    public function findById($id)
    {
        $sql = "SELECT 
                c.name as company_name, cb.amount as company_budget
                FROM companies_budget cb 
                INNER JOIN companies c 
                ON cb.company_id = c.id 
            WHERE c.id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            throw new DomainRecordNotFoundException('Company Budget Not Found');
        }

        return $this->_relationship($result);
    }

    private function _relationship($arr)
    {
        if (empty($arr)) {
            return [];
        }

        if (!empty($arr[1])) {
            return array_reduce($arr, function ($carry, $item) {
                $data = [
                    'company' => $item['company_name'],
                    'budget' => $item['company_budget'],
                ];

                array_push($carry, $data);

                return $carry;
            }, []);
        }

        return [
            'company' => $arr['company_name'],
            'budget' => $arr['company_budget'],
        ];
    }
}
