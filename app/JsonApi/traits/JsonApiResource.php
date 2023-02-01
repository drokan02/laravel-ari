<?php

namespace App\JsonApi\Traits;

use App\JsonApi\Document;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait JsonApiResource
{
    abstract function toJsonApiAttributes(): array;
    abstract function getRelationshipsLinks(): array;
    abstract function getIncludes(): array;
    public function toArray($request): array
    {
        $relations = [];
        if ($request->filled('include')) {
            // $relations = $this->setIncluded();

        }
        $aux = $this->generateApiResource($this, 'role');
        return  (array)$this->basicResourceObjects($this)
            ->relationshipData($aux)
            ->link($this->getLink($this->resource))
            ->get('data');
    }

    function basicResourceObjects($resource, $isRelationship = false): Document
    {
        $document = new Document;
        if ($resource !== null) {
            $document = $document->type($resource->getResourceType())
                ->id($resource->getRouteKey());
            if (!$isRelationship) {
                $atributes = $resource->toJsonApiAttributes();
                $document->attributes($this->filterAttributes($atributes));
            } else {
            }
        }
        return $document;
    }

    function getLink(&$resource)
    {
        return route("{$resource->getResourceType()}.show", $resource);
    }

    public function filterAttributes(array $attributes): array
    {
        return array_filter($attributes, function ($value) {
            if (request()->isNotFilled('fields')) {
                return true;
            }
            $fields = explode(',', request('fields.' . $this->getResourceType()));
            if ($value === $this->getRouteKey()) {
                return in_array($this->getRouteKeyName(), $fields);
            }

            return $value;
        });
    }

    public function resourceAttributesToCamelCase($resource)
    {
        return collect($resource)->mapWithKeys(function ($value, $key) {
            if (is_array($value)) {
                $value = collect($value)->mapWithKeys(fn ($value, $key) => ([Str::camel($key) => $value]))->all();
            }
            return [Str::camel($key) => $value];
        })->all();
    }

    private function setIncluded()
    {
        $included = [];
        $relations = array_filter($this->getRelationsWithApiResourceInstances());
        foreach ($relations as $include) {
            if ($include instanceof Collection) {
                foreach ($include as $resource) {
                    $included[] = $resource;
                }
            } elseif (isset($include->resource)) {
                // include as resource
                $included[] = $include;
            }
        }
        $this->with['included'] = $included;
        return $relations;
    }

    private function generateApiResource($resource, $relationName)
    {
        $included = [];
        $relatedResourceName = Str::ucfirst(Str::singular($relationName));
        $relatedApiResource = "App\\Http\\Resources\\{$relatedResourceName}Resource";
        $response = [
            $relationName =>  $relatedApiResource::make($resource->{$relationName})
        ];
        $included[] = $response;
        foreach ($response as $key => $resource) {
            $relationship[$key] =  $this->basicResourceObjects($resource->{'resource'}, true);
        }
        $relationship;
        $this->with['included'] = $included;
        return $relationship;
    }
}
