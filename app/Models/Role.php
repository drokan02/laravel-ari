<?php

namespace App\Models;

use App\JsonApi\Traits\CamelCasing;
use App\JsonApi\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, CamelCasing, HasUuid;

    protected $guarded = [];

    public $resourceType = 'roles';


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
