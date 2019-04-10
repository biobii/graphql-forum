<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use JWTAuth;
use App\Models\Forum;
use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class CreateCommentMutation extends Mutation
{
    private $auth;

    /**
     * Define attributes.
     * 
     * @var
     */
    protected $attributes = [
        'name' => 'CreateComment'
    ];

    /**
     * Define types.
     * 
     * @param void
     * @return GraphQL
     */
    public function type()
    {
        return GraphQL::type('comments');
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
            'slug' => ['required', 'string'],
            'comment' => ['required', 'string'],
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
            ],
            'comment' => [
                'name' => 'comment',
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

    /**
     * Resolver.
     * 
     * @param $root
     * @param array $args
     * @param SelectFields $fields
     * @return Comment
     */
    public function resolve($root, $args, SelectFields $fields)
    {
        $with = $fields->getRelations();
        $select = $fields->getSelect();
        
        $forum = Forum::where('slug', $args['slug'])->first();

        $comment = $this->auth->comments()->create([
            'forum_id' => $forum->id,
            'comment' => $args['comment']
        ]);

        return Comment::with($with)->select($select)->where('id', $comment->id)->first();
    }
}