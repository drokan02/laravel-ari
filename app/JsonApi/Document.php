<?php

namespace App\JsonApi;

use Illuminate\Support\Collection;

class Document extends Collection
{
    public static function type(string $type): Document
    {
        return new self([
            'data' => [
                'type' => $type
            ]
        ]);
    }

    public function id($id): Document
    {
        if ($id) {
            $data = &$this->items['data'];
            $data['id'] = (string) $id;
        }
        return $this;
    }

    public function attributes(array $attributes): Document
    {
        $this->items['data']['attributes'] = $attributes;
        return $this;
    }

    public function link($self): Document
    {
        $data = &$this->items['data'];
        $data['link']['self'] = $self;
        return $this;
    }

    public function relationshipData($relationships): Document
    {
        $data = &$this->items['data'];
        $data['relationships'] = $relationships;
        return $this;
    }
}
