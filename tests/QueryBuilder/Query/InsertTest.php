<?php

namespace CodeTests\QueryBuilder\Query;

use PHPUnit\Framework\TestCase;
use Code\QueryBuilder\Query\Insert;

class InsertTest extends TestCase
{
    private Insert $insert;

    public function setUp(): void
    {
        $this->insert = new Insert('products', ['name', 'price']);
    }
    protected function assertPostConditions(): void
    {
        $this->assertTrue(class_exists(Insert::class));
    }

    public function testIfInsertionQueryGeneratedWithSuccess()
    {
        $sql1 = "INSERT INTO products(name, price) VALUES(:name, :price)";
        $sql2 = "INSERT INTO products VALUES(:name, :price)";

        $this->assertEquals($sql1, $this->insert->getSql());
    }
}
