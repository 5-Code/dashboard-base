<?php

namespace Habib\Dashboard\Repository;

use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @template T
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    protected array $filters = ['id'];

    protected array $with = [];

    /**
     * @var T
     */
    protected Model $model;

    protected Request $request;

    /**
     * @param T $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->request = app('request');
    }

    /**
     * @return Builder|Collection
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function all()
    {
        $query = $this->getModel()->query()->when($this->request->get('limit'), fn($q, $v) => $q->limit($v));

        return $this->applyFilter($query)->get();
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    public function applyFilter(Builder $query)
    {
        foreach ($this->getFilters() as $filter) {
            // created_at#>=#to
            // updated_at#<=form
            $filter = explode('#', $filter);
            $name = $filter[0];
            $alias = $filter[2] ?? $name;
            $value = $this->getRequest()->get($alias);
            $operator = $this->getRequest()->get(str_replace('.', '_', $alias) . '_op', $filter[1] ?? '=');
            if (is_null($value) && !in_array($operator, ['nullable', 'notNullable'])) {
                continue;
            }

            if (count(explode('.', $name)) > 1) {
                $param = explode('.', $name);
                $name = end($param);
                $relation = implode('.', array_slice($param, 0, -1));

                $query->whereHas($relation, fn($q) => $this->filter($q, $value, $name, $operator));
            } else {
                $name = "{$this->getModel()->getTable()}.{$name}";
                $this->filter($query, $value, $name, $operator);
            }
        }

        return $query->with($this->getWith())
            ->orderBy($this->request->get('order_by', 'id'), $this->request->get('order_direction', 'desc'));
    }

    /**
     * @return string[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return Application|Request|mixed|\Request
     */
    public function getRequest(): mixed
    {
        return $this->request;
    }

    private function filter($query, $value, $name, $operator)
    {
        return match ($operator) {
            'in' => $query->whereIn($name, is_array($value) ? $value : explode(',', $value)),
            'notIn' => $query->whereNotIn($name, is_array($value) ? $value : explode(',', $value)),
            'nullable' => $query->whereNull($name),
            'notNullable' => $query->whereNotNull($name),
            'between' => $query->whereBetween($name, is_array($value) ? $value : explode(',', $value)),
            'like', 'ilike', 'rlike' => $query->where($name, $operator, "%$value%"),
            'date' => $query->whereDate($name, $operator, $value),
            'day' => $query->whereDay($name, $operator, $value),
            'month' => $query->whereMonth($name, $operator, $value),
            'year' => $query->whereYear($name, $operator, $value),
            'column' => $query->whereColumn($name, $operator, $value),
            'time' => $query->whereTime($name, $operator, $value),
            default => $query->where($name, $operator, $value),
        };
    }

    /**
     * @return array
     */
    public function getWith(): array
    {
        return $this->with;
    }

    /**
     * @param array $with
     */
    public function setWith(array $with): void
    {
        $this->with = $with;
    }

    /**
     * @param int|int[]|Model $model
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function destroy(int|array|Model $model): bool
    {
        if (!$model = $this->find($model)) {
            return false;
        }

        return $model instanceof Collection ? $model->toQuery()->delete() : $model->delete();
    }

    /**
     * @param int|array|Model|string $model
     * @param callable|null $callable
     * @param bool $deleted
     * @return Builder|Builder[]|Collection|Model|object|null
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function find(int|array|Model|string $model, callable $callable = null, bool $deleted = false)
    {
        if ($model instanceof Model) {
            return $model;
        }

        $query = $this->getModel()->query()->with($this->getWith());

        if ($deleted) {
            $query->withTrashed();
        }

        if (is_callable($callable)) {
            $callable($query);
        }

        if (is_array($model)) {
            return $query->findMany($model);
        }

        if (!is_numeric($model)) {
            $query->getModel()->setKeyName($this->request->get('filed_name', 'slug'));

            return $query->where(fn($q) => $q->where($query->getModel()->getKeyName() . '->ar', $model)
                ->orWhere($query->getModel()->getKeyName() . '->en', $model))
                ->first();
        }

        return $query->find($model);
    }

    /**
     * @param int|Model $model
     * @return Model|bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function delete(int|Model $model): Model|bool
    {
        if (!$model = $this->find($model)) {
            return false;
        }

        return $model->delete();
    }

    /**
     * @param int|Model $model
     * @return Model|bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function forceDelete(int|Model $model): Model|bool
    {
        if (!$model = $this->find($model)) {
            return false;
        }

        return $model->forceDelete();
    }

    /**
     * @param int|Model $model
     * @return Model|bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function restore(int|Model $model): Model|bool
    {
        if (!$model = $this->find($model, deleted: true)) {
            return false;
        }

        return $model->restore();
    }

    /**
     * @return LengthAwarePaginator
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): LengthAwarePaginator
    {
        return $this->applyFilter($this->getModel()->query())->paginate($this->request->get('limit', 15));
    }

    /**
     * @return array|LengthAwarePaginator
     */
    public function deletedOnly(): array|LengthAwarePaginator
    {
        return $this->getModel()->onlyTrashed()->paginate();
    }

    /**
     * @param array $data
     * @return Model|bool
     */
    public function store(array $data): bool|Model
    {
        return DB::transaction(function () use ($data) {
            // changes something
            $this->beforeCreate($data);

            if (!$saved = $this->getModel()->create($data)) {
                return false;
            }

            return tap($saved,fn($model) => $this->attach($saved, $data));
        });
    }

    abstract public function beforeCreate(array &$data);

    abstract public function attach(Model $model, array &$data);

    /**
     * @param int|Model $model
     * @param array $data
     * @return Model|bool
     */
    public function update(int|Model $model, array $data): bool|Model
    {
        return DB::transaction(function () use ($model, $data) {
            if (!$model = $this->find($model)) {
                return false;
            }

            $this->beforeUpdate($model, $data);

            if (!$model->update($data)) {
                return false;
            }

            return tap($model,fn($model) => $this->attach($model, $data));
        });
    }

    abstract public function beforeUpdate(Model $model, array &$data);

    public function addWith($with): static
    {
        if (in_array($with, $this->with)) {
            return $this;
        }

        $this->with[] = $with;

        return $this;
    }

    /**
     * @return Builder
     */
    public function getQueryFiltered(): Builder
    {
        return $this->applyFilter($this->getQuery());
    }

    /***
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->getModel()->query();
    }

    public function new(array $attributes = [])
    {
        return new $this->model($attributes);
    }
}
