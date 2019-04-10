<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use JWTAuth;
use App\Models\Forum;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class CreateForumMutation extends Mutation
{
    private $auth;

    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'CreateForum'
    ];

    /**
     * Define types.
     * 
     * @param void
     * @return GraphQL
     */
    public function type()
    {
        return GraphQL::type('forums');
    }

    /**
     * Authorize user.
     * 
     * @param array $args
     * @return bool
     */
    public function authorize(array $args)
    {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            $this->auth = null;
        }

        return (boolean) $this->auth;
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
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string'],
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
            'title' => [
                'name' => 'title',
                'type' => Type::nonNull(Type::string())
            ],
            'body' => [
                'name' => 'body',
                'type' => Type::nonNull(Type::string())
            ],
            'tags' => [
                'name' => 'tags',
                'type' => Type::listOf(Type::int())
            ],
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
        
        $forum = $this->auth->forums()->create([
            'title' => $args['title'],
            'slug' => str_slug($args['title'], '-'),
            'body' => $args['body']
        ]);
        
        if (isset($args['tags'])) {
            $forum->tags()->attach($args['tags']);
        }

        return Forum::with($with)->select($select)->where('id', $forum->id)->first();
    }
}