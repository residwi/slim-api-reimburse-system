<?php
declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\DomainException\DomainRecordNotFoundException;
use PDO;

class CompanyRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM companies";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM companies 
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch();

        if (!$result) {
            throw new DomainRecordNotFoundException('Company Not Found');
        }

        return $result;
    }


    public function create($arr)
    {
        $sql = "INSERT INTO companies (name, address) VALUES (:name, :address)";
                
        $stmt = $this->connection->prepare($sql);

        $stmt->execute($arr);

        $id = $this->connection->lastInsertId();
        $sql = "INSERT INTO companies_budget (company_id, amount) VALUES (?, ?)";
                
        $stmt = $this->connection->prepare($sql);

        $stmt->execute([$id, 0]);

        return array_merge([
            'id' => $id
        ], $arr);
    }

    public function update($arr)
    {
        $this->findById($arr['id']);

        $sql = "UPDATE companies SET 
                name=:name, 
                address=:address
            WHERE id=:id";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute($arr);

        return $arr;
    }

    public function delete($id)
    {
        $this->findById($id);

        $sql = "DELETE FROM companies
                WHERE id=:id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam('id', $id);

        return $stmt->execute();
    }
}
