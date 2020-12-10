<?php

namespace App\GraphQL\Mutations\User;

use App\Question;
use App\UserAnswer;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SubmitAnswers
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Request::get('user');
        $answers = collect();

        foreach($args['input'] as $answer) {
            $question = Question::withoutGlobalScope('user_current_group')->find($answer['question_id']);

            if (!$question)
                Log::warning("The user {$user->username}(#{$user->id}) attempts to submit an answer to a question(#{$answer['question_id']}) that is either deleted or disabled.");
            else if (!$user->isInGroup($question->group_id))
                Log::warning("The user {$user->username}(#{$user->id}) attempts to submit an answer to a question(#{$question->id}) from a group(#{$question->group_id}) to which he does not register ({$user->groups->pluck('id')})");
            else
                $answers->push(UserAnswer::create([
                        'group_id' => $question->group_id,
                        'question_id' => $question->id,
                        'user_id' => $user->id,
                        'result' => $answer['result'],
                        'ip' => Request::ip(),
                        'is_enduro' => $answer['is_enduro'],
                        'answered_at' => $answer['answered_at']
                    ]));
        }
        $user->updateStats($answers);
        // Avoid the hassle of retrieving foreign data (quizz info, questions info) from other groups.
        return $answers->where('group_id', $user->current_group);
    }
}
