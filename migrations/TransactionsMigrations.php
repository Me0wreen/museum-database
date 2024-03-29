<?php

namespace migrations;

use classes\server\Database;
use Exception;
use models\AdminModel;
use models\TransactionModel;

class TransactionsMigrations
{
    private object $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getPdo();
    }

    /**
     * @throws Exception
     */
    public function createTable(): string
    {
        $stmt = $this->pdo->prepare("CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `amount` int(50) NOT NULL,
  `date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        if (!$stmt->execute()) {
            throw new Exception("Невозможно создать таблицу транзакций.");
        }
        return "Таблица транзакций успешно создана.";
    }

    public function alterKeys(): string {
        $stmt = $this->pdo->prepare("ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);");

        if (!$stmt->execute()) {
            throw new Exception("Невозможно установить первичный ключ.");
        }

        return "Успешно установлен первичный ключ.";
    }

    public function alterAutoIncrement(): string {
        $stmt = $this->pdo->prepare("ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;");

        if (!$stmt->execute()) {
            throw new Exception("Невозможно установить автоматическую инкрементацию");
        }

        return "Изменения успешно внесены в таблицу.";
    }

    public function seed($amount): void {
        $transactionModel = new TransactionModel();
        $faker = \Faker\Factory::create();

        $payment_types = getList("payment_types");
        $payment_categories = getList("payment_categories");
        $payment_methods = getList("payment_methods");

        for ($i = 0; $i < $amount; $i++) {
            $transactionModel->create($payment_types[$faker->randomKey($payment_types)],
            $payment_categories[$faker->randomKey($payment_categories)], $payment_methods[$faker->randomKey($payment_methods)],
                rand(20,5000), $faker->date());
            $transactionModel->store();
        }
    }
}