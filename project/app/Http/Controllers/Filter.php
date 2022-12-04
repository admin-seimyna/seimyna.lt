<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Filter
{
    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @var FormRequest|Request
     */
    protected $request;

    /**
     * @var array
     */
    protected array $filters = [];

    /**
     * @var string
     */
    protected string $method = 'get';

    /**
     * @param Builder $builder
     * @param Request|FormRequest $request
     */
    public function __construct(Builder $builder, $request = null)
    {
        $this->builder = $builder;
        $this->request = $request ?? request();
    }

    /**
     * @param Builder $builder
     * @param null $request
     * @return Builder
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function apply(Builder $builder, $request = null): Builder
    {
        return (new static($builder, $request))->filter();
    }


    /**
     * @return Builder
     * @throws \Exception
     */
    public function filter(): Builder
    {
        collect($this->filters)->each(function ($rules, $name) {
            $method = Str::camel('filter_' . $name);
            if (!method_exists($this, $method)) {
                return;
            }

            $requestMethod = mb_strtolower($this->method) === 'get' ? 'query' : 'input';
            $value = $this->request->{$requestMethod}($name);

            foreach($rules as $rule) {
                $ruleMethod = Str::camel('validate_' . $rule);
                if (!method_exists($this, $ruleMethod)) {
                    throw new \Exception('[filter] validation method "' . $ruleMethod . '" doesnt exists');
                }

                if (!$this->{$ruleMethod}($value)) {
                    return;
                }
            }

            $this->builder = $this->{$method}($this->builder, $value);
        });

        return $this->builder;
    }

    /**
     * @param $value
     * @return bool
     */
    public function validateRequired($value): bool
    {
        if ($value === 0) {
            return true;
        }
        return !empty($value) && $value !== '';
    }
}
