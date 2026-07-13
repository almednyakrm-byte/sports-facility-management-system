<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالمنشئات extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetالمنشئات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM المنشئات')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'المنشئ 1']]);

        $result = $this->pdo->query('SELECT * FROM المنشئات');
        $this->assertEquals([['id' => 1, 'name' => 'المنشئ 1']], $result->fetchAll());
    }

    public function testPostالمنشئات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المنشئات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'المنشئ 2');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('INSERT INTO المنشئات (name) VALUES (:name)');
        $this->stmt->bindParam(':name', 'المنشئ 2');
        $this->stmt->execute();
        $this->assertEquals(1, $this->pdo->lastInsertId());
    }

    public function testPutالمنشئات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المنشئات SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'المنشئ 1 Updated');

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('UPDATE المنشئات SET name = :name WHERE id = :id');
        $this->stmt->bindParam(':name', 'المنشئ 1 Updated');
        $this->stmt->bindParam(':id', 1);
        $this->stmt->execute();
        $this->assertEquals(1, $this->stmt->rowCount());
    }

    public function testDeleteالمنشئات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المنشئات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('DELETE FROM المنشئات WHERE id = :id');
        $this->stmt->bindParam(':id', 1);
        $this->stmt->execute();
        $this->assertEquals(1, $this->stmt->rowCount());
    }
}