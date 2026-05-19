<?php

declare(strict_types=1);

namespace Todo\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Todo\Model\TodoTable;

class TodoController extends AbstractRestfulController
{
    private const VALID_STATUSES = ['pending', 'in_progress', 'done'];

    public function __construct(private readonly TodoTable $table) {}

    /** GET /api/todos */
    public function getList(): JsonModel
    {
        return new JsonModel(['data' => $this->table->fetchAll()]);
    }

    /** GET /api/todos/:id */
    public function get(mixed $id): JsonModel
    {
        $todo = $this->table->find((int) $id);

        if ($todo === null) {
            $this->response->setStatusCode(404);
            return new JsonModel(['error' => 'Todo not found']);
        }

        return new JsonModel(['data' => $todo]);
    }

    /** POST /api/todos */
    public function create(mixed $data): JsonModel
    {
        $errors = $this->validate($data);
        if ($errors !== []) {
            $this->response->setStatusCode(422);
            return new JsonModel(['error' => 'Validation failed', 'messages' => $errors]);
        }

        $todo = $this->table->insert([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'],
        ]);

        $this->response->setStatusCode(201);
        return new JsonModel(['data' => $todo]);
    }

    /** PUT /api/todos/:id */
    public function update(mixed $id, mixed $data): JsonModel
    {
        $existing = $this->table->find((int) $id);
        if ($existing === null) {
            $this->response->setStatusCode(404);
            return new JsonModel(['error' => 'Todo not found']);
        }

        $errors = $this->validate($data);
        if ($errors !== []) {
            $this->response->setStatusCode(422);
            return new JsonModel(['error' => 'Validation failed', 'messages' => $errors]);
        }

        $todo = $this->table->update((int) $id, [
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'],
        ]);

        return new JsonModel(['data' => $todo]);
    }

    /** DELETE /api/todos/:id */
    public function delete(mixed $id): JsonModel
    {
        $existing = $this->table->find((int) $id);
        if ($existing === null) {
            $this->response->setStatusCode(404);
            return new JsonModel(['error' => 'Todo not found']);
        }

        $this->table->delete((int) $id);
        return new JsonModel(['message' => 'Todo deleted successfully']);
    }

    private function validate(mixed $data): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'title is required';
        } elseif (mb_strlen($data['title']) > 255) {
            $errors['title'] = 'title must be 255 characters or less';
        }

        if (empty($data['status'])) {
            $errors['status'] = 'status is required';
        } elseif (!in_array($data['status'], self::VALID_STATUSES, true)) {
            $errors['status'] = 'status must be one of: pending, in_progress, done';
        }

        return $errors;
    }
}