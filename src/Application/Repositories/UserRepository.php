<?php
declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\DomainException\DomainRecordNotFoundException;
use PDO;

class UserRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $sql = "SELECT 
                    u.id, 
                    u.firstname, 
                    u.lastname, 
                    u.email, 
                    u.account, 
                    c.id as company_id, 
                    c.name as company_name, 
                    c.address as company_address
                FROM users u INNER JOIN companies c ON u.company_id = c.id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $this->_relationship($result);
    }

    public function findById($id)
    {
        $sql = "SELECT 
                    u.id, 
                    u.firstname, 
                    u.lastname, 
                    u.email, 
                    u.account, 
                    c.id as company_id, 
                    c.name as company_name, 
                    c.address as company_address
                FROM users u INNER JOIN companies c ON u.company_id = c.id
                WHERE u.id=:id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch();

        if (!$result) {
            throw new DomainRecordNotFoundException('User Not Found');
        }

        return $this->_relationship($result);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT 
                    u.id, 
                    u.firstname, 
                    u.lastname, 
                    u.email, 
                    u.account, 
                    c.id as company_id, 
                    c.name as company_name, 
                    c.address as company_address
                FROM users u INNER JOIN companies c ON u.company_id = c.id
                WHERE u.email=:email";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $result = $stmt->fetch();

        if (!$result) {
            throw new DomainRecordNotFoundException('User Not Found');
        }

        return $this->_relationship($result);
    }

    public function create($arr)
    {
        $sql = "INSERT INTO users 
                (firstname, lastname, email, account, company_id) 
                VALUES (:firstname, :lastname, :email, :account, :company_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute($arr);

        return array_merge([
            'id' => $this->connection->lastInsertId()
        ], $arr);
    }

    public function update($arr)
    {
        $this->findById($arr['id']);

        $sql = "UPDATE users SET 
                firstname=:firstname, 
                lastname=:lastname, 
                email=:email, 
                account=:account, 
                company_id=:company_id
            WHERE id=:id";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute($arr);

        return $arr;
    }

    public function delete($id)
    {
        $this->findById($id);

        $sql = "DELETE FROM users
                WHERE id=:id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam('id', $id);

        return $stmt->execute();
    }

    private function _relationship($arr)
    {
        if (empty($arr)) {
            return [];
        }
        
        if (!empty($arr[1])) {
            return array_reduce($arr, function ($carry, $item) {
                $data = [
                    'id' => $item['id'],
                    'firstname' => $item['firstname'],
                    'lastname' => $item['lastname'],
                    'email' => $item['email'],
                    'account' => $item['account'],
                    'company' => [
                        'id' => $item['company_id'],
                        'name' => $item['company_name'],
                        'address' => $item['company_address'],
                    ]
                ];
    
                array_push($carry, $data);
    
                return $carry;
            }, []);   
        }
        
        return [
            'id' => $arr['id'],
            'firstname' => $arr['firstname'],
            'lastname' => $arr['lastname'],
            'email' => $arr['email'],
            'account' => $arr['account'],
            'company' => [
                'id' => $arr['company_id'],
                'name' => $arr['company_name'],
                'address' => $arr['company_address'],
            ]
        ];
    }
}
