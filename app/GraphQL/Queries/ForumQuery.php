<?php

namespace App\GraphQL\Queries;

use GraphQL;
use App\Models\Forum;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class ForumQuery extends Query
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
        return Type::listOf(GraphQL::type('forums'));
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
            'slug' => [
                'name' => 'slug',
                'type' => Type::string()
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
        
        $where = function ($query) use ($args) {
            if (isset($args['id']))
                $query->where('id', $args['id']);
                
            if (isset($args['slug']))
                $query->where('slug', $args['slug']);
        };

        return Forum::with($with)->select($select)->where($where)->get();
    }
}