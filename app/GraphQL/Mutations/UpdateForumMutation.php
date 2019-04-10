<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use JWTAuth;
use App\Models\Forum;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class UpdateForumMutation extends Mutation
{
    private $auth;

    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'UpdateForum'
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

            $forum = Forum::where('slug', $args['slug'])->first();
        } catch (Exception $e) {
            $this->auth = null;
            return (boolean) $this->auth;
        }

        return (boolean) isset($this->auth) && $this->auth->id === $forum->user_id;
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
            'slug' => ['required', 'string'],
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
            'slug' => [
                'name' => 'slug',
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
        
        $forum = Forum::with($with)->select($select)->where('slug', $args['slug'])->first();

        $forum->update([
            'title' => $args['title'],
            'body' => $args['body']
        ]);
        
        if (isset($args['tags'])) {
            $forum->tags()->sync($args['tags']);
        }

        return Forum::with($with)->select($select)->where('slug', $args['slug'])->first();
    }
}