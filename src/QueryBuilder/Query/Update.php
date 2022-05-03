<?php

namespace Code\QueryBuilder\Query;

class Update
{
    private $sql;

    public function __construct(string $table, array $fields = [], array $conditions = [], $opetator = ' AND')
    {
        $this->sql = "UPDATE $table SET ";

        $set = '';
        foreach ($fields as $f) {
            $set .= $set ? ", $f = :$f":"$f = :$f";
        }

        $where = '';
        foreach ($conditions as $key => $c) {
            $where .= $where ? "$opetator $key = $c" : "WHERE $key = $c";
        }

        $this->sql .= "$set $where";
    }

    public function getSql()
    {
        return $this->sql;
    }
}
