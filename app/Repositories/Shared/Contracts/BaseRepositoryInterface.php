<?php

namespace App\Repositories\Shared\Contracts;

use Illuminate\Http\Request;

interface BaseRepositoryInterface
{
    public function getAll(Request $request, null|string|int $parentId = null);
    public function getAllRaw(array $options = [], null|string|int $parentId = null);
    public function store(array $data, null|string|int $parentId = null);
    public function get(string|int $id, null|string|int $parentId = null);
    // public function getRaw(string|int $id, null|string|int $parentId = null);
    public function update(array $data, string|int $id, null|string|int $parentId = null);
    public function delete(string|int $id, null|string|int $parentId = null);
}
