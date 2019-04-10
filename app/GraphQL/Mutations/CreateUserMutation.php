<?php

namespace App\GraphQL\Mutations;

use Hash;
use GraphQL;
use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreateUserMutation extends Mutation
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'CreateUser'
    ];

    /**
     * Define types.
     * 
     * @param void
     * @return GraphQL
     */
    public function type()
    {
        return GraphQL::type('users');
    }

    /**
     * Validating users input.
     * 
     * @param array $args
     * @return array $args
     */
    public function rules(array $args = [])
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required']
        ];
    }

    /**
     * Lists of available args.
     * 
     * @param void
     * @return array
     */
    public function args()
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string())
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string())
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    /**
     * Resolver.
     * 
     * @param $root
     * @param array $args
     * @return User
     */
    public function resolve($root, $args)
    {
        return User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make('secret')
        ]);
    }
}