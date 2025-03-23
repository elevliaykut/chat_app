<?php


namespace App\Services;

use App\Exceptions\Repository\InvalidModelClassException;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    /**
     * @var Model
     */
    public Model $model;

    /**
     * Class of the model repository works with
     *
     * @var string
     */
    protected string $modelClass;

    /**
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->resolveModelEntity();
    }


    /**
     * Resolve model entity if it is valid
     *
     * @return void
     * @throws InvalidModelClassException
     */
    protected function resolveModelEntity(): void
    {
        if (is_null($this->model())) {
            throw new InvalidModelClassException('Model class is null');
        }

        $this->model = resolve($this->model());

        if (!$this->model instanceof Model) {
            throw new InvalidModelClassException("{$this->model()} is not an instance of Model");
        }
    }

    /**
     * Get searchable fields array
     *
     * @return mixed
     */
    abstract public function getFieldsSearchable(): array;

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model(): string;

    /**
     * Resolve model by key or return the model instance back
     *
     * @param $keyOrModel
     * @return Model
     */
    protected function resolveModel($keyOrModel): Model
    {
        return !$keyOrModel instanceof Model
            ? $this->retrieveById($keyOrModel)
            : $keyOrModel;
    }

    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->allQuery();

        return $query->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return Builder
     */
    public function allQuery(array $search = [], int $skip = null, int $limit = null): Builder
    {
        $query = $this->model->newQuery();

        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->getFieldsSearchable(), true)) {
                    $query->where($key, $value);
                }
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }

    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return Builder[]|Collection
     */
    public function all(array $search = [], int $skip = null, int $limit = null, array $columns = ['*'])
    {
        $query = $this->allQuery($search, $skip, $limit);

        return $query->get($columns);
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create(array $input): Model
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find(int $id, array $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * Retrieve the brand by the given id.
     *
     * @param $id
     *
     * @return Model
     */
    public function retrieveById($id): Model
    {
        return $this->model::query()->findOrFail($id);
    }

    /**
     * Retrieve the brand by the given id.
     *
     * @param int id
     *
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model::query()->where('id', $id)->exists();
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return Builder|Builder[]|Collection|Model
     */
    public function update(array $input, int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @param int $id
     *
     * @return bool|mixed|null
     * @throws Exception
     *
     */
    public function destroy(int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }
}
