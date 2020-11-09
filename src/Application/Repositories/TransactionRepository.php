<?php
declare(strict_types=1);

namespace App\Application\Repositories;

use Exception;
use PDO;

class TransactionRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $sql = "SELECT c.id,
                c.name as company_name,
                cb.amount as remaining_budget,
                u.id as user_id,
                u.firstname,
                u.lastname,
                u.account,
                t.type as transaction_type,
                t.date as transaction_date,
                t.amount as transaction_amount FROM transactions t 
            INNER JOIN users u ON t.user_id = u.id
            INNER JOIN companies c ON u.company_id = c.id
            INNER JOIN companies_budget cb ON c.id = cb.company_id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $this->_relationship($result);
    }

    public function create($arr)
    {
        $sql = "INSERT INTO transactions (type, user_id, amount) 
                VALUES (:type, :user_id, :amount)";
        $stmt = $this->connection->prepare($sql);

        try {
            $this->connection->beginTransaction();

            $query = "SELECT id, amount FROM companies_budget WHERE company_id = :company";

            $pdo = $this->connection->prepare($query);
            $pdo->bindParam(':company', $arr['company_id']);
            $pdo->execute();
            $companyBudget = $pdo->fetch();

            switch ($arr['type']) {
                case 'R':
                case 'C':
                    $total = $companyBudget['amount'] - $arr['amount'];
                    break;
                case 'S':
                    $total = $companyBudget['amount'] + $arr['amount'];
                    break;
            }

            $update = "UPDATE companies_budget SET 
                        amount=:amount WHERE company_id=:company";

            $st = $this->connection->prepare($update);
            $status = $st->execute(['amount' => $total, 'company' => $arr['company_id']]);

            if (!$status) {
                throw new Exception();
            }

            $param = [
                'type' => $arr['type'],
                'user_id' => $arr['user_id'],
                'amount' => $arr['amount'],
            ];

            $result = $stmt->execute($param);
            $this->connection->commit();
        }catch (Exception $e){
            $this->connection->rollback();
            $result = 'Failed';
        }

        return $result;
    }

    private function _relationship($arr)
    {
        $transactionType = [
            'R' => 'Reimbursement',
            'C' => 'Disbursement',
            'S' => 'Close',
        ];

        if (empty($arr)) {
            return [];
        }
        
        if (!empty($arr[1])) {
            return array_reduce($arr, function ($carry, $item) use ($transactionType) {
                $data = [
                    'id' => $item['id'],
                    'name' => $item['company_name'],
                    'remaining_budget' => $item['remaining_budget'],
                    'user' => [
                        'id' => $item['user_id'],
                        'firstname' => $item['firstname'],
                        'lastname' => $item['lastname'],
                        'account' => $item['account'],
                    ],
                    'transaction' => [
                        'type' => $transactionType[$item['transaction_type']],
                        'date' => $item['transaction_date'],
                        'amount' => $item['transaction_amount'],
                    ]
                ];

                array_push($carry, $data);

                return $carry;
            }, []);
        }

        return [
            'id' => $arr['id'],
            'name' => $arr['company_name'],
            'remaining_budget' => $arr['remaining_budget'],
            'user' => [
                'id' => $arr['user_id'],
                'firstname' => $arr['firstname'],
                'lastname' => $arr['lastname'],
                'account' => $arr['account'],
            ],
            'transaction' => [
                'type' => $transactionType[$arr['transaction_type']],
                'date' => $arr['transaction_date'],
                'amount' => $arr['transaction_amount'],
            ]
        ];
    }
}
