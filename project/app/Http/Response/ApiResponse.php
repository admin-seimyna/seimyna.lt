<?php

namespace App\Http\Response;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApiResponse
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var FormRequest|Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected Builder $builder;


    /**
     * @var array
     */
    protected array $policies = [];

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param FormRequest|Request $request
     * @return static
     */
    public function request($request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Builder $builder
     * @return $this
     */
    public function query(Builder $builder): self
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @param $callback
     * @return $this
     */
    public function handle($callback): self
    {
        $arguments = func_get_args();
        array_shift($arguments);

        $this->data = $callback instanceof Response ? $callback->get() : $callback($this);
        return $this;
    }

    /**
     * @param string $method
     * @param $arguments
     * @return $this
     */
    public function authorize(string $method, $arguments): self
    {
        $response = Gate::inspect($method, $arguments);
        if (!$response->allowed()) {
            abort($response->code(), $response->message());
        }

        return $this;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function filter($filter): self
    {
        $this->builder = $filter::apply($this->builder, $this->request);
        return $this;
    }

    /**
     * @param $callback
     * @return $this
     */
    public function format($callback): self
    {
        $this->data = $callback($this->builder->get());
        return $this;
    }

    /**
     * @param int $code
     * @return JsonResponse
     */
    public function json(int $code = 200): JsonResponse
    {
        return new JsonResponse($this->data, $code);
    }
}
