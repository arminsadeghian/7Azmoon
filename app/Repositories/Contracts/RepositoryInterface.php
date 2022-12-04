<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function create(array $data);

    public function update(int $id, array $data);

    public function find(int $id);

    public function all(array $where);

    public function deleteBy(array $where);

    public function delete(int $id): bool;

    public function paginate(int $page = 1, int $pageSize = 20, array $columns = []): array;
}
