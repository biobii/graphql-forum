<?php

namespace App\GraphQL\Types;

use App\Models\Tag;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TagType extends GraphQLType
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'Tag',
        'description' => 'A Tag',
        'model' => Tag::class
    ];

    /**
     * Fields.
     * 
     * @param void
     * @return array $fields
     */
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the tag'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the tag'
            ]
        ];
    }
}