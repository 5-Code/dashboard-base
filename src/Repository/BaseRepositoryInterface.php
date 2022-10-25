<?php

namespace Habib\Dashboard\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function all();

    public function destroy(int|array|Model $model): bool;

    public function find(int|array|Model $model, callable $callable = null, bool $deleted = false);

    public function delete(int|Model $model): Model|bool;

    public function forceDelete(int|Model $model): Model|bool;

    public function restore(int|Model $model): Model|bool;

    public function index(): LengthAwarePaginator;

    public function deletedOnly(): array|LengthAwarePaginator;
}
