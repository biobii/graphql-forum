<?php

namespace App\GraphQL\Queries;

use GraphQL;
use App\Models\Forum;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class ForumPaginationQuery extends Query
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'Forum query'
    ];

    /**
     * Set the type.
     * 
     * @param void
     * @return Type
     */
    public function type()
    {
        return GraphQL::paginate('forums');
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
            'limit' => [
                'name' => 'limit',
                'type' => Type::int()
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::int()
            ]
        ];
    }

    /**
     * Resolver.
     * 
     * @param $root
     * @param array $args
     * @return Forum
     */
    public function resolve($root, $args, SelectFields $fields)
    {
        $with = $fields->getRelations();
        $select = $fields->getSelect();

        return Forum::with($with)->select($select)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}