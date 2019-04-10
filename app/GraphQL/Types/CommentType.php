<?php

namespace App\GraphQL\Types;

use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CommentType extends GraphQLType
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'Comment',
        'description' => 'A Comment',
        'model' => Comment::class
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
                'description' => 'The id of the comment'
            ],
            'user_id' => [
                'type' => Type::int(),
                'description' => 'The user of the comment'
            ],
            'forum_id' => [
                'type' => Type::int(),
                'description' => 'The forum of the comment'
            ],
            'comment' => [
                'type' => Type::string(),
                'description' => 'The comment'
            ],
            'forum' => [
                'type' => GraphQL::type('forums'),
                'description' => 'The forum of the comment'
            ],
            'user' => [
                'type' => GraphQL::type('users'),
                'description' => 'The user of the comment'
            ],
        ];
    }
}