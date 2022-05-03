<?php

namespace Code\QueryBuilder;


class Executor
{
    /**
     * @var \PDO
     */
    private \PDO $connection;
    /**
     * @var mixed|null
     */
    private $query;
    /**
     * @var array
     */
    private array $params = [];

    public function __construct(\PDO $connection, $query = null)
    {
        $this->connection = $connection;
        $this->query = $query;
    }

    public function setQuery($queryBuilder)
    {
        $this->query = $queryBuilder;
    }

    public function setParam($bind, $value)
    {
        $this->params[] = [
            'bind' => $bind,
            'value' => $value
        ];

        return $this;
    }

    public function execute()
    {
        $proccess = $this->connection->prepare($this->query->getSql());

        foreach ($this->params as $param) {
            $type = gettype($param['value']) == 'string' ? \PDO::PARAM_STR : \PDO::PARAM_INT;
            $proccess->bindValue($param['bind'], $param['value'], $type);
        }

        $proccess->execute();
        return $this->connection->lastInsertId();
    }
}
