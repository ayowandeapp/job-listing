<?php

namespace Framework\Rules;

use Framework\Database;

class ExistsRule implements RuleInterface
{
    private Database $db;
    public function __construct()
    {

        $config = require basePath('App/Config/db.php');
        $this->db = new Database($config);
    }
    public function validate(array $data, string $field, mixed $param = '')
    {
        [$tableName, $columnName] = explode(',', $param);

        $exist = $this->db->query(
            "SELECT * FROM $tableName WHERE $columnName = :$columnName",
            [$columnName => $data[$field]]
        )->fetch();

        return !$exist;


    }
    public function getMessage(array $data, string $field, mixed $param = '')
    {
        return "The $field already exist! ";
    }

}