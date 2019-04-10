<?php

namespace App\GraphQL\Queries;

use GraphQL;
use App\Models\Tag;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class TagQuery extends Query
{
    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'Tag query'
    ];

    /**
     * Set the type.
     * 
     * @param void
     * @return Type
     */
    public function type()
    {
        return Type::listOf(GraphQL::type('tags'));
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
            ]
        ];
    }

    /**
     * Resolver.
     * 
     * @param $root
     * @param array $args
     * @return Tag
     */
    public function resolve($root, $args, SelectFields $fields)
    {
        $select = $fields->getSelect();
        
        $where = function ($query) use ($args) {
            if (isset($args['id']))
                $query->where('id', $args['id']);
                
            if (isset($args['name']))
                $query->where('slug', $args['slug']);
        };

        return Tag::select($select)->where($where)->get();
    }
}