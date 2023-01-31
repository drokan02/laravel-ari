<?php

namespace App\Http\Resources;

use App\JsonApi\Traits\JsonApiResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use JsonApiResource;

    public function toJsonApiAttributes(): array
    {
        return [
            'name' => $this->resource->name
        ];
    }

    function getRelationshipsLinks(): array
    {
        return [
            'role'
        ];
    }

    public function getIncludes(): array
    {
        return [
            'role'
        ];
    }
}
