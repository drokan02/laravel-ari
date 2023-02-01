<?php

namespace App\JsonApi;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class JsonApiQueryBuilder
{
    public function getResourceType()
    {
        return function () {
            /** @var Builder $this */
            return property_exists($this->model, 'resourceType') ? $this->model->resourceType : $this->model->getTable();
        };
    }

    public function allowedIncludes()
    {
        return function ($allowedIncludes = []) {

            /** @var Builder $this */
            if (request()->isNotFilled('include')) {
                return $this;
            }
            $includes = explode(',', request('include'));
            foreach ($includes as $include) {
                if (request()->isMethod('POST') || request()->isMethod('PATCH')) {
                    if (in_array($include, $allowedIncludes)) {
                        $this->getModel()->load($include);
                    }
                } else {
                    abort_unless(in_array($include, $allowedIncludes), 400, "Unsupported inclusion {$include} resource");
                    $this->with($include);
                }
            }
            if (request()->isMethod('POST') || request()->isMethod('PATCH')) {
                return $this->getModel();
            } else {
                return $this;
            }
        };
    }

    public function sparseFieldset()
    {
        return function () {
            /** @var Builder $this */
            if (request()->isNotFilled('fields')) {
                return $this;
            }

            $fields = array_filter(explode(',', request('fields.' . $this->getResourceType())));
            $fields = array_map(fn ($field) => Str::snake($field), $fields);

            $routeKeyName = $this->model->getRouteKeyName();
            if (!in_array($routeKeyName, $fields)) {
                $fields[] = $routeKeyName;
            }

            return $this->addSelect($fields);
        };
    }
}
