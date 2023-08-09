<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as Application;

abstract class MysqlBaseRepository
{
    protected $app;
    protected $model;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new ApiException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function find(int|string $id)
    {
        return $this->model->query()->find($id);
    }

    public function findOrFail(int|string $id)
    {
        return $this->model->query()->findOrFail($id);
    }
}
