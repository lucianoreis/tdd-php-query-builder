<?php

namespace CodeTests\QueryBuilder;

use Code\QueryBuilder\Query\Delete;
use Code\QueryBuilder\Query\Insert;
use Code\QueryBuilder\Query\Select;
use Code\QueryBuilder\Query\Update;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use Code\QueryBuilder\Executor;

class ExecutorTest extends TestCase
{
    private static \PDO $conn;
    private Executor $executor;

    protected function setUp(): void
    {
        $this->executor = new Executor(self::$conn);
    }

    public static function setUpBeforeClass(): void
    {
        try {
            self::$conn = new PDO('sqlite::memory:');
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
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function tearDownAfterClass(): void
    {
        self::$conn->exec("DROP TABLE products");
    }

    public function testInsertANewProductInADataBase()
    {
        $queryBuilder = new Insert('products', ['name', 'price', 'created_at', 'updated_at']);

        $executor = $this->executor;
        $executor->setQuery($queryBuilder);
        $executor
            ->setParam(':name', 'Product 1')
            ->setParam(':price', 19.99)
            ->setParam(':created_at', date('Y-m-d H:i:s'))
            ->setParam(':updated_at', date('Y-m-d H:i:s'));

        $this->assertTrue($executor->execute());
    }

    public function testTheSelectionOfAProduct()
    {
        $queryBuilder = new Select('products');

        $executor = $this->executor;
        $executor->setQuery($queryBuilder);
        $executor->execute();

        $products = $executor->getResult();
        $this->assertEquals('Product 1', $products[0]['name']);
        $this->assertEquals(19.99, $products[0]['price']);
    }

//    public function testUpdateAndGetASingleProduct()
//    {
//        $queryBuilder = new Update('products', ['name'], ['id' => 1]);
//
//        $executor = $this->executor;
//        $executor->setQuery($queryBuilder);
//        $executor->setParam(':name', 'Produto 1 Editado');
//        $updated = $executor->execute();
//        $this->assertTrue($updated);
//
//        $queryBuilderProduct = (new Select('products'))
//            ->where('id', '=', ':id');
//        $executorResult = new Executor(self::$conn);
//        $executorResult->setQuery($queryBuilderProduct);
//        $executorResult->setParam(':id', 1);
//        $executorResult->execute();
//
//        $products = $executor->getResult();
//
//        $this->assertEquals('Produto 1 Editado', $products[0]['name']);
//    }

    public function testIfDeletedAProductFromDatabase()
    {
        $query = new Delete('products', ['id' => 1]);
        $executor = $this->executor;
        $executor->setQuery($query);
        $this->assertTrue($executor->execute());

        $query = (new Select('products'))->where('id', '=', ':id');
        $executor = new Executor(self::$conn);
        $executor->setQuery($query);
        $executor->setParam(':id', 1);
        $executor->execute();

        $products = $executor->getResult();

        $this->assertCount(0, $products);
    }
}
