<?php

namespace App\Repositories;

use App\Models\Model;

abstract class Repository
{
    /** @var Model $model */
    protected $model;

    /** @var array $relations */
    protected $relations = [];

    public function create(array $payload)
    {
        return $this->model::create($payload);
    }

    public function createMany(array $payload)
    {
        return $this->model::insert($payload);
    }

    public function findOrFail(int $id)
    {
        return $this->model::with($this->relations)->findOrFail($id);
    }

    public function first()
    {
        return $this->model::first();
    }

    public function searchUsingPagination(array $args = [], int $page = 1, int $perPage = 15)
    {
        $query = $this->buildQuery($args);
        return $query->paginate(
            $perPage,
            ['*'],
            'page',
            $page
        );
    }

    public function search(array $args = [], array $orderBy = [])
    {
        $query = $this->buildQuery($args, $orderBy);

        return $query->get();
    }

    public function searchV2(array $columns, array $whereOptions, array $sortOptions = [])
    {
        $query = $this->model::query();

        foreach ($whereOptions as $column => $object) {
            if (is_array($object)) {
                $query = $query->whereIn($column, $object);
            } else {
                $query = $query->where($column, '=', $object);
            }
        }

        $query = $query->select(...$columns);

        if (count($sortOptions) > 0) {
            foreach ($sortOptions as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        return $query->get();
    }

    public function searchV3(array $columns, array $whereOptions, array $otherOptions = [])
    {
        $query = $this->model::query();
        foreach ($whereOptions as $whereType => $whereBody) {
            switch ($whereType) {
                case '=':
                case '<':
                case '>':
                case '<=':
                case '>=':
                case '<>':
                    foreach ($whereBody as $body) {
                        $query = $query->where(...$body);
                    }
                    break;
                case 'in':
                case 'IN':
                    foreach ($whereBody as $column => $object) {
                        $query = $query->whereIn($column, $object);
                    }
                    break;
                case 'not_in':
                case 'NOT_IN':
                    foreach ($whereBody as $column => $object) {
                        $query = $query->whereNotIn($column, $object);
                    }
                    break;
                case 'null':
                case 'NULL':
                    foreach ($whereBody as $column) {
                        $query = $query->whereNull($column);
                    }
                    break;
                case 'not_null':
                case 'NOT_NULL':
                    foreach ($whereBody as $column) {
                        $query = $query->whereNotNull($column);
                    }
                    break;
            }
        }

        $query = $query->select(...$columns);

        if (isset($otherOptions['group'])) {
            if (count($otherOptions['group']) > 0) {
                $query = $query->groupBy(...$otherOptions['group']);
            }
        }

        if (isset($otherOptions['sort'])) {
            if (count($otherOptions['sort']) > 0) {
                foreach ($otherOptions['sort'] as $body) {
                    $query = $query->orderBy(...$body);
                }
            }
        }

        if (isset($otherOptions['join'])) {
            foreach ($otherOptions['join'] as $body) {
                $query = $query->join(...$body);
            }
        }

        if (isset($otherOptions['distinct']) && $otherOptions['distinct'] === true) {
            $query = $query->distinct();
        }

        if (isset($otherOptions['first']) && $otherOptions['first'] === true) {
            return $query->first();
        }

        if (isset($otherOptions['take']) && (int)$otherOptions['take'] > 0) {
            $query = $query->take((int)$otherOptions['take']);
        }

        return $query->get();
    }

    public function updateV3(array $whereOptions, array $payload)
    {
        $query = $this->model::query();
        foreach ($whereOptions as $whereType => $whereBody) {
            switch ($whereType) {
                case '=':
                case '<':
                case '>':
                case '<=':
                case '>=':
                case '<>':
                    foreach ($whereBody as $body) {
                        $query = $query->where(...$body);
                    }
                    break;
                case 'in':
                case 'IN':
                    foreach ($whereBody as $column => $object) {
                        $query = $query->whereIn($column, $object);
                    }
                    break;
                case 'not_in':
                case 'NOT_IN':
                    foreach ($whereBody as $column => $object) {
                        $query = $query->whereNotIn($column, $object);
                    }
                    break;
                case 'null':
                case 'NULL':
                    foreach ($whereBody as $column) {
                        $query = $query->whereNull($column);
                    }
                    break;
                case 'not_null':
                case 'NOT_NULL':
                    foreach ($whereBody as $column) {
                        $query = $query->whereNotNull($column);
                    }
                    break;
            }
        }

        return $query->update($payload);
    }

    public function searchByIds(string $column, array $ids, array $relations = [])
    {
        if (count($relations) > 0) {
            return $this->model::with($relations)->whereIn($column, $ids)->get();
        } else {
            return $this->model::whereIn($column, $ids)->get();
        }
    }

    public function update(int $id, array $payload)
    {
        return $this->model::where('id', $id)->update($payload);
    }

    public function updateMany(array $ids, array $payload)
    {
        return $this->model::whereIn('id', $ids)->update($payload);
    }

    public function delete(int $id)
    {
        return $this->model::destroy($id);
    }

    public function deleteAll()
    {
        return $this->model::truncate();
    }

    public function createOrUpdate(array $row, array $uniqueKeys = [])
    {
        $uniqueData = [];
        foreach ($uniqueKeys as $uniqueKey) {
            $uniqueData[$uniqueKey] = $row[$uniqueKey];
        }
        $model = $this->model::firstOrCreate($uniqueData);
        $model->update($row);
        return $this->findOrFail($model->id);
    }

    protected function addWhereInMultipleColumns($query, array $columns, array $multiValues)
    {
        $columnParameter = '(' . implode(',', $columns) . ')';
        $prepareItems = [];

        foreach ($multiValues as $values) {
            $preparedStr = implode(',', array_fill(0, count($values), '?'));
            $prepareItems[] = '(' . $preparedStr . ')';
        }

        return $query->whereRaw(
            $columnParameter . ' IN (' . implode(',', $prepareItems) . ')',
            collect($multiValues)->flatten()->all()
        );
    }

    protected function buildQuery(array $args = [], array $orderBy = [])
    {
        $query = $this->model::query();

        if (count($args)) {
            foreach ($args as $key => $value) {
                if (is_null($value)) {
                    $query->whereNull($key);
                } elseif (is_array($value)) {
                    if (isAssocArray($value)) {
                        if (isset($value['between'])) {
                            $query->whereBetween($key, $value['between']);
                        }
                        if (isset($value['in'])) {
                            $query->whereIn($key, $value['in']);
                        }
                    } else {
                        $query->whereIn($key, $value);
                    }
                } else {
                    $query->where($key, $value);
                }
            }
        }

        foreach ($orderBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }

    public function updateWithOptions(array $whereOptions, array $payload)
    {
        $query = $this->model::query();

        foreach ($whereOptions as $column => $object) {
            if (is_array($object)) {
                $query = $query->whereIn($column, $object);
            } else {
                $query = $query->where($column, $object);
            }
        }

        $query->update($payload);
    }
}
