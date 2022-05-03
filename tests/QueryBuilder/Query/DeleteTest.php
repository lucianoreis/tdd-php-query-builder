<?php

namespace CodeTests\QueryBuilder\Query;

use PHPUnit\Framework\TestCase;
use Code\QueryBuilder\Query\Delete;

class DeleteTest extends TestCase
{
    private Delete $delete;

    public function setUp(): void
    {
        $this->delete = new Delete('products', ['id' => 10]);
    }
    protected function assertPostConditions(): void
    {
        $this->assertTrue(class_exists(Delete::class));
    }

    public function testIfDeleteQueryHasGeneratedWithSuccess()
    {
        $sql ="DELETE FROM products WHERE id = 10";
        $this->assertEquals($sql, $this->delete->getSql());
    }
}
