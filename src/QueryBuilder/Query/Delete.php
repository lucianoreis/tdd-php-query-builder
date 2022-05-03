<?php

namespace Code\QueryBuilder\Query;

class Delete
{
    private $sql;

    public function __construct(string $table, array $conditions = [], $opetator = ' AND')
    {
        $this->sql = "DELETE FROM $table";

        $where = '';
        foreach ($conditions as $key => $c) {
            $where .= $where ? "$opetator $key = $c" : " WHERE $key = $c";
        }

        $this->sql .= "$where";
    }

    public function getSql()
    {
        return $this->sql;
    }
}
