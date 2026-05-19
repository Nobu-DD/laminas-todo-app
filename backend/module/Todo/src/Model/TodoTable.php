<?php

declare(strict_types=1);

namespace Todo\Model;

use Laminas\Db\TableGateway\TableGateway;

class TodoTable
{
    public function __construct(private readonly TableGateway $tableGateway) {}

    public function fetchAll(): array
    {
        return iterator_to_array($this->tableGateway->select());
    }

    public function find(int $id): ?array
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row    = $rowset->current();
        return $row ? (array) $row : null;
    }

    public function insert(array $data): array
    {
        $this->tableGateway->insert($data);
        $id = (int) $this->tableGateway->getLastInsertValue();
        return $this->find($id);
    }

    public function update(int $id, array $data): ?array
    {
        $this->tableGateway->update($data, ['id' => $id]);
        return $this->find($id);
    }

    public function delete(int $id): int
    {
        return $this->tableGateway->delete(['id' => $id]);
    }
}