<?php

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user',
        'model' => User::class
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
                'description' => 'The id of the user'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the user'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of the user'
            ],
            'forums' => [
                'type' => Type::listOf(GraphQL::type('forums')),
                'description' => 'The forums of the user'
            ],
            'comments' => [
                'type' => Type::listOf(GraphQL::type('comments')),
                'description' => 'The comments of the user'
            ],
        ];
    }
}