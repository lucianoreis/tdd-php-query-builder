<?php

namespace CodeTests\QueryBuilder;

use Code\QueryBuilder\Query\Insert;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use Code\QueryBuilder\Executor;

class ExecutorTest extends TestCase
{
    private static \PDO $conn;
    private static Executor $executor;

    public static function setUpBeforeClass(): void
    {
        try {
            self::$conn = new PDO('sqlite::memory:');
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

        $statement = "
            CREATE TABLE IF NOT EXISTS 'products' (
                'id' INTEGER PRIMARY KEY,
                'name' TEXT,
                'price' FLOAT,
                'created_at' TIMESTAMP, 
                'updated_at' TIMESTAMP
            )";

        self::$conn->exec($statement);

        self::$executor = new Executor(self::$conn);
    }

    public static function tearDownAfterClass(): void
    {
        self::$conn->exec("DROP TABLE products");
    }

    public function testInsertANewProductInADataBase()
    {
        $queryBuilder = new Insert('products', ['name', 'price', 'created_at', 'updated_at']);

        self::$executor->setQuery($queryBuilder);

        self::$executor
            ->setParam(':name', 'Product 1')
            ->setParam(':price', 19.99)
            ->setParam(':created_at', date('Y-m-d H:i:s'))
            ->setParam(':updated_at', date('Y-m-d H:i:s'));

        $this->assertEquals(1, self::$executor->execute());
    }
}
