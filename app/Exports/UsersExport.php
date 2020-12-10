<?php

namespace App\Exports;


use App\Group;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class UsersExport implements FromQuery, WithMapping, WithHeadings, WithStrictNullComparison
{
    use Exportable;

    private $group_id;

    public function __construct(int $group_id)
    {
        $this->group_id = $group_id;
    }

    public function query()
    {
        return Group::find($this->group_id)->users();
    }

    public function map($user): array
    {
        $stats = $user->statistics()->where('user_statistics.group_id', $this->group_id)->first();
        return [
            $user->id,
            $stats->app_rank,
            $user->groups()->where('groups.id', $this->group_id)->first()->pivot->email,
            $user->username,
            $user->curr_os,
            $user->subscriptions()->where('user_subscriptions.group_id', $this->group_id)->count(),
            $user->answers()->where('user_answers.group_id', $this->group_id)->count(),
            $stats->goodAnswerRate(),
            $user->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Rank',
            'Email',
            'Username',
            'Platform',
            'Quizzes',
            'Answers',
            'Good Answer Rate',
            'Last Seen'
        ];
    }
}
