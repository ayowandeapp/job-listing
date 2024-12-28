<?php

class Database
{
    public $conn;

    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);

        } catch (\PDOException $e) {
            throw new Exception("Error Processing Request: {$e->getMessage()}", $e->getCode());

        }

    }

    /**
     * Summary of query
     * @param string $query
     * @throws \Exception
     * @return PDOStatement
     */
    public function query(string $query, $params = []): PDOStatement
    {
        try {
            $sth = $this->conn->prepare($query);

            //bind the params - prepared statement
            foreach ($params as $key => $value) {
                $sth->bindValue(":$key", $value);
            }
            $sth->execute();
            return $sth;
        } catch (\PDOException $e) {
            throw new Exception("Error Processing Request", $e->getCode());

        }

    }
}