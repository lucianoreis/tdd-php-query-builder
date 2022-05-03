<?php

namespace CodeTests\QueryBuilder\Query;

use PHPUnit\Framework\TestCase;
use Code\QueryBuilder\Query\Update;

class UpdateTest extends TestCase
{
    private Update $update;

    public function setUp(): void
    {
        $this->update = new Update('products', ['name', 'price'], ['id' => '1']);
    }
    protected function assertPostConditions(): void
    {
        $this->assertTrue(class_exists(Update::class));
    }

    public function testIfUpdateQueryHasGeneratedWithSuccess()
    {
        $sql ="UPDATE products SET name = :name, price = :price WHERE id = 1";
        $this->assertEquals($sql, $this->update->getSql());
    }

    public function testIfUpdateQueryWithConditionsHasGeneratedWithSuccess()
    {
        $update = new Update('products', ['name', 'price'], ['id' => '1', 'name' => 'Caneca']);

        $sql ="UPDATE products SET name = :name, price = :price WHERE id = 1 AND name = Caneca";
        $this->assertEquals($sql, $update->getSql());
    }
}
