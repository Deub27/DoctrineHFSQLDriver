<?php

/*
 * This file is part of the tbcd/doctrine-hfsql-driver package.
 *
 * (c) Thomas Beauchataud <thomas.beauchataud@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TBCD\Doctrine\HFSQLDriver\Tests;

use Doctrine\DBAL\Driver\Exception;
use PHPUnit\Framework\TestCase;
use TBCD\Doctrine\HFSQLDriver\Driver;
use TBCD\Doctrine\HFSQLDriver\Result;
use TBCD\Doctrine\HFSQLDriver\Statement;
use TypeError;

class StatementTest extends TestCase
{

    use TestHelperTrait;

    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::clearFiles();
    }

    /**
     * @return void
     */
    public function testCreation(): void
    {
        try {
            new Statement(false, '');
            $this->expectError();
        } catch (TypeError) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * @throws Exception
     */
    public function testExecuteWithoutParams(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', 'name_data')");
        $this->assertInstanceOf(Statement::class, $statement);
        $result = $statement->execute();
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @throws Exception
     */
    public function testBindValue(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (?, ?, ?)");
        $statement->bindValue(0, 1);
        $statement->bindValue(1, 'code_data');
        $statement->bindValue(2, 'name_data');
        $result = $statement->execute();
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testBindParam(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (?, ?, ?)");
        $id = 1;
        $code = 'code_data';
        $name = 'name_data';
        $statement->bindParam(0, $id);
        $statement->bindParam(1, $code);
        $statement->bindParam(2, $name);
        $result = $statement->execute();
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testExecuteWithParams(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (?, ?, ?)");
        $result = $statement->execute([1, 'code_data', 'name_data']);
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testBindValueAndExecuteWithParams(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (:id, ?, ?)");
        $statement->bindValue('id', 1);
        $result = $statement->execute(['code_data', 'name_data']);
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testUnexistingParams(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (:id, ':code', ':name')");
        $this->expectException(Exception::class);
        $statement->bindValue('test', 1);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testTooMuchParams(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', ?)");
        $this->expectException(Exception::class);
        $statement->execute(['code_data', 'name_data']);
    }
}