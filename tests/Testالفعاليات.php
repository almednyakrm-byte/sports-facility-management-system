<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالفعاليات extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetالفعاليات()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM الفعاليات')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'الفعاليات 1'],
                ['id' => 2, 'name' => 'الفعاليات 2'],
            ]);

        $result = $this->pdo->query('SELECT * FROM الفعاليات')->fetchAll();
        $this->assertCount(2, $result);
    }

    public function testPostالفعاليات()
    {
        $data = ['name' => 'الفعاليات 3'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO الفعاليات (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('INSERT INTO الفعاليات (name) VALUES (:name)');
        $this->statement->bindParam(':name', $data['name']);
        $this->assertTrue($this->statement->execute());
    }

    public function testPutالفعاليات()
    {
        $id = 1;
        $data = ['name' => 'الفعاليات 1 updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE الفعاليات SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('UPDATE الفعاليات SET name = :name WHERE id = :id');
        $this->statement->bindParam(':name', $data['name']);
        $this->statement->bindParam(':id', $id);
        $this->assertTrue($this->statement->execute());
    }

    public function testDeleteالفعاليات()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM الفعاليات WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('DELETE FROM الفعاليات WHERE id = :id');
        $this->statement->bindParam(':id', $id);
        $this->assertTrue($this->statement->execute());
    }
}