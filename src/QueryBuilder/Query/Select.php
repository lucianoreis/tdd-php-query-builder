<?php

namespace Code\QueryBuilder\Query;

class Select
{
    private $query;
    private $where;
    private $orderBy;
    private $limit;
    private $join;

    public function __construct($table)
    {
        $this->query = "SELECT * FROM $table";
    }

    public function getSql()
    {
        return $this->query . $this->join . $this->where . $this->orderBy . $this->limit;
    }

    public function where(string $field, string $operador, string $bind = null, $concat = 'AND')
    {
        $bind = is_null($bind) ? ":$field" : $bind;
        if (!$this->where) {
            $this->where .= " WHERE $field $operador $bind";
        } else {
            $this->where .= " $concat $field $operador $bind";
        }

        return $this;
    }

    public function orderBy(string $field, string $order)
    {
        $this->orderBy = " ORDER BY $field $order";

        return $this;
    }

    public function limit(int $skip, int $take)
    {
        $this->limit = " LIMIT $skip, $take";

        return $this;
    }

    public function join(string $type, string $table, string $foreingKey, string $operator, $referenceColumn, $concat = false)
    {
        if (!$concat) {
            $this->join .= " $type $table ON $foreingKey $operator $referenceColumn";
        } else {
            $this->join .= " $concat $foreingKey $operator $referenceColumn";
        }

        return $this;
    }

    public function select(...$fields)
    {
        $fields = implode(', ', $fields);
        $this->query = str_replace('*', $fields, $this->query);

        return $this;
    }
}
