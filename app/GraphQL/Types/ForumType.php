<?php

namespace App\GraphQL\Types;

use App\Models\Forum;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ForumType extends GraphQLType
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'Forum',
        'description' => 'A Forum',
        'model' => Forum::class
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
                'description' => 'The id of the forum'
            ],
            'users' => [
                'type' => GraphQL::type('users'),
                'description' => 'The user of the forum'
            ],
            'comments' => [
                'type' => Type::listOf(GraphQL::type('comments')),
                'description' => 'The comments of the forum'
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The title of the forum'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The slug of the forum'
            ],
            'body' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The slug of the forum'
            ],
            'tags' => [
                'type' => Type::listOf(GraphQL::type('tags')),
                'description' => 'The tags of forum'
            ]
        ];
    }
}