<?php

namespace App\GraphQL\Queries;

use GraphQL;
use App\Models\User;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class UsersQuery extends Query
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'Users query'
    ];

    /**
     * Set the type.
     * 
     * @param void
     * @return Type
     */
    public function type()
    {
        return Type::listOf(GraphQL::type('users'));
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
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'name' => [
                'name' => 'name',
                'type' => Type::string()
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string()
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
    public function resolve($root, $args, SelectFields $fields)
    {
        $with = $fields->getRelations();
        $select = $fields->getSelect();

        if (empty($args['id']) && empty($args['email']))
            return User::all();

        if(!empty($args['id']))
            return User::with($with)->select($select)->where('id', $args['id'])->get();

        if (!empty($args['email']))
            return User::with($with)->select($select)->where('email', $args['email'])->get();
    }
}