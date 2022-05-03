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
    private $result;

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

        if ($this->params) {
            foreach ($this->params as $param) {
                $type = gettype($param['value']) == 'integer' ? \PDO::PARAM_INT : \PDO::PARAM_STR;
                $proccess->bindValue($param['bind'], $param['value'], $type);
            }
        }

        $proccessResult = $proccess->execute();
        $this->result = $proccess;
        // return $this->connection->lastInsertId();
        return $proccessResult;
    }

    public function getResult()
    {
        if (!$this->result) {
            return null;
        }

        return $this->result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
