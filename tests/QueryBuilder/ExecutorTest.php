<?php

namespace CodeTests\QueryBuilder;

use PHPUnit\Framework\TestCase;

class ExecutorTest extends TestCase
{
    private static \PDO $conn;

    public static function setUpBeforeClass(): void
    {
        self::$conn = new \PDO('sqlite::memory:');
        self::$conn->exec("
            CREATE TABLE IF NOT EXISTS 'products' (
                'id' integer PRIMARY KEY,
                'name' TEXT,
                'price' FLOAT,
                created_at TIMESTAMP, 
                updated_at TIMESTAMP, 
            );
        ");
    }

    public static function tearDownAfterClass(): void
    {
        self::$conn->exec("DROP TABLE products");
    }
}
