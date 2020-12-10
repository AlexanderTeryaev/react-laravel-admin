<?php

namespace App\GraphQL\Mutations\User;

use App\Events\NewQuestionReportEvent;
use App\Notifications\Slack\NewReport;
use App\Question;
use App\QuestionReport;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SubmitQuestionReports
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
        $reports = Collect();
        foreach($args['input'] as $report){
            $question = Question::find($report['question_id']);
            if ($user->isInGroup($question->group_id)) {
                $r = QuestionReport::create(
                    [
                        'user_id' => $user->id,
                        'group_id' => $question->group_id,
                        'question_id' => $question->id,
                        'report' => $report['report'],
                        'status' => 'pending'
                    ]
                );
                $reports->push($r);
                $r->notify(new NewReport($r));
                //event(new NewQuestionReportEvent($r));
            } else {
                Log::info('The user '. $user->id .' attempts to submit an question report into a group to which he does not register');
            }
        }
        return true;
    }
}
