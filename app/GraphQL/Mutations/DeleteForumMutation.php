<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use JWTAuth;
use App\Models\Forum;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeleteForumMutation extends Mutation
{
    private $auth;

    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'DeleteForum'
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
            'slug' => ['required', 'string']
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
            'slug' => [
                'name' => 'slug',
                'type' => Type::nonNull(Type::string())
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
        
        $forum = Forum::with($with)->select($select)->where('slug', $args['slug'])->first();
        $forum->tags()->detach();
        $forum->delete();

        return $forum->toArray();
    }
}