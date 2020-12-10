<?php

namespace App;

use App\Notifications\Account\MailResetPassword;
use App\Notifications\UserNotification;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;
use TaylorNetwork\UsernameGenerator\Facades\UsernameGenerator;
use TaylorNetwork\UsernameGenerator\FindSimilarUsernames;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, HasLocalePreference
{
    use Notifiable, FindSimilarUsernames, HasRoles, Searchable;

    protected $table = 'users';
    protected $guard_name = 'user';

    protected $fillable = [
        'current_group',
        'current_group_portal',
        'device_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'password',
        'avatar_url',
        'bio',
        'one_signal_id',
        'reputation',
        'can_submit_report',
        'curr_os',
        'curr_app_version',
        'curr_app_lang',
        'username_updated',
        'last_ip',
        'is_onboarded',
        'email_verified_at'
    ];

    protected $attributes = [
        'current_group' => 1,
        'reputation' => 0,
        'can_submit_report' => true,
        'username_updated' => 0,
        'is_onboarded' => false
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    private $viewer_is_subscribe;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Return a token for OneSignal user identification for notifications
     *
     * @return array
     */
    public function routeNotificationForOneSignal()
    {
        return $this->one_signal_id;
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->curr_app_lang;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->groups()->whereNotNull('user_groups.email')->pluck('user_groups.email')->toArray(),
            'bio' => $this->bio,
            'curr_os' => $this->curr_os,
            'groups_ids' => $this->groups()->pluck('groups.id')->toArray(),
            'populations_ids' => $this->populations()->pluck('group_populations.id')->toArray(),
            'groups_emails' => $this->groups()->whereNotNull('user_groups.email')->pluck('user_groups.group_id')->toArray(),
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPassword($token));
    }

    /**
     * Get new unique username for user
     */
    public function usernameGenerator()
    {
        while (true)
        {
            $username = UsernameGenerator::generate();
            if (strlen($username) > 30 || User::where('username', '=', $username)->exists())
                continue;
            $this->update(['username' => $username]);
            break;
        }
    }

    /**
     * Get the stats record associated with the user.
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(UserStatistics::class);
    }

    /**
     * Alias to get statistics of the current group
     */
    public function stats()
    {
        return $this->statistics()->first();
    }

    /**
     * Get the current group of the user.
     */
    public function group(): BelongsTo
    {
        return $this->BelongsTo(Group::class, 'current_group');
    }

    /**
     * The groups that belong to the user.
     */
    public function populations(): BelongsToMany
    {
        return $this->belongsToMany(
            GroupPopulation::class,
            'user_groups',
            'user_id' ,
            'population_id'
        )->withPivot('id', 'group_id', 'method', 'email', 'created_at')->withTimestamps();
    }

    /**
     * The groups that belong to the user.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups')->withPivot('id', 'population_id', 'method', 'email', 'created_at')->withTimestamps();
    }

    /**
     * Get the current group portal of the user.
     */
    public function manageable_group(): BelongsTo
    {
        return $this->BelongsTo(Group::class, 'current_group_portal');
    }

    /**
     * Groups whose user is the manager (has rights on it)
     */
    public function manageable_groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_managers')->withPivot('id')->withTimestamps();
    }

    /**
     * Same as above but returns all groups for admins
     */
    public function manageableGroups()
    {
        if ($this->hasRole('admin'))
            return Group::all();
        return $this->manageable_groups()->get();
    }

    /**
     * Get the answers for the user.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Get specific answer of the user.
     * /!\ Used only for graphQL for the | [User/Learner] -> answer(id) | query
     */
    public function answer(): HasOne
    {
        return $this->hasOne(UserAnswer::class);
    }


    /**
     * Get specific notification by id of the user.
     * /!\ Used only for graphQL for the | [User] -> notification(id) | query
     */
    public function notification(): HasOne
    {
        return $this->hasOne(DatabaseNotification::class, 'notifiable_id');
    }

    /**
     * Get the quizz subscriptions for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get specific subscription of the user.
     * /!\ Used only for graphQL for the | [User/Learner] -> subscription(id) | query
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class);
    }

    /**
     * The quizzes that belong to the user.
     */
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quizz::class, 'user_subscriptions')->withPivot('group_id', 'created_at', 'updated_at');
    }

    /**
     * Get the reports for the user.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(QuestionReport::class);
    }

    /**
     * The questions from user quizzes subscriptions
     */
    public function questions()
    {
        $quizzes = $this->quizzes()->withoutGlobalScope('user_current_group')->published()->pluck('quizzes.id');
        return Question::withoutGlobalScope('user_current_group')
                        ->whereIn('quizz_id', $quizzes)
                        ->with(['quizz' => function ($query) {
                            $query->withoutGlobalScope('user_current_group')->with(['group' => function ($query) {
                                $query->withoutGlobalScope('status');
                            }]);
                        }])->get();
    }

    /**
     * Know if a user is subscribed to a quizz
     *
     * @param  Int $quizz_id
     * @return bool
     */
    public function isSubscribed($quizz_id)
    {
        // This is to avoid viewer_is_subscribe = false during subscribeQuizz Mutation
        if (isset($this->viewer_is_subscribe))
            return $this->viewer_is_subscribe;
        return $this->quizzes->contains($quizz_id);
    }

    /**
     * Subscribe user to a quizz
     *
     * @param  Quizz
     * @return \App\UserSubscription
     */
    public function subscribe(Quizz $quizz)
    {
        $this->quizzes()->syncWithoutDetaching([$quizz->id => ['group_id' => $quizz->group_id]]);
        $this->viewer_is_subscribe = true;
        return UserSubscription::where('user_id', '=', $this->id)->where('quizz_id', '=', $quizz->id)->first();
    }

    /**
     * Unsubscribe user to a quizz
     *
     * @param  Int $quizz_id
     * @return array
     */
    public function unsubscribe($quizz_id)
    {
        $subscription = UserSubscription::where('group_id', '=', $this->current_group)
                                            ->where('user_id', '=', $this->id)
                                            ->where('quizz_id', '=', $quizz_id)->first();
        $subscription->delete();
        $this->viewer_is_subscribe = false;
        return $subscription;
    }

    /**
     * To know if the user is registered in the group
     *
     * @param $group_id
     * @return bool
     */
    public function isInGroup($group_id)
    {
        return $this->groups()->where('groups.id', '=', $group_id)->count() > 0;
    }

    /**
     * To switch user to group
     *
     * @param $group_id
     * @return bool
     */
    public function switchGroup($group_id)
    {
        $this->current_group = $group_id;
        return $this->save();
    }

    /**
     * To switch user to manageable group
     *
     * @param $group_id
     * @return bool
     */
    public function switchManageableGroup($group_id)
    {
        $this->current_group_portal = $group_id;
        return $this->save();
    }

    /**
     * //TODO: Duplicate func
     * To know if the user is registered in the group
     *
     * @param $group_id
     * @return bool
     */
    public function isGroupManager($group_id)
    {
        return $this->manageableGroups()->where('id', $group_id)->count() > 0;
    }

    /**
     * Add user inside group
     *
     * @param int $group_id
     * @param String $method
     * @param String $email
     * @param int $population_id
     */
    public function addInGroup($group_id, $method, $email = null, $population_id = null){
        // TMP Code, waiting group invitation system
        if (!$population_id)
        {
            $group = Group::find($group_id);
            if ($group->populations()->count() == 0)
                $group->populations()->create([
                    'name' => 'Default population',
                    'description' => 'Default population',
                    'master_key' => Str::lower(Str::random(5)) // Improve to avoid duplicates
                ]);
            $population_id = $group->populations()->first()->id;
        }
        // End TMP code

        $this->groups()->syncWithoutDetaching([
            $group_id => [
                'population_id' => $population_id,
                'method' => $method,
                'email' => $email
            ]
        ]);
        if (!UserStatistics::withoutGlobalScope('user_current_group')->where('user_id', '=', $this->id)->where('group_id', '=', $group_id)->exists())
            UserStatistics::create(
                [
                    'user_id' => $this->id,
                    'group_id' => $group_id,
                    'bad_answers' => 0,
                    'good_answers' => 0,
                    'app_rank' => UserStatistics::withoutGlobalScope('user_current_group')->where('group_id', '=', $group_id)->max('app_rank') + 1,
                    'unlocks' => 0,
                ]);
    }

    /**
     * Add user to group Managers
     *
     * @param int $group_id
     */
    public function addInGroupManagers($group_id){
        if (!$this->isGroupManager($group_id))
            $this->manageable_groups()->attach($group_id);
    }

    /**
     * Leave private group
     *
     * @param int $group_id
     * @param bool $delete_data
     *
     * @return bool
     */
    public function leaveGroup($group_id, $delete_data = false): bool
    {
        if ($group_id == 1) // TODO: Replace 1 by default_group global config
            return false;

        if (($email = $this->groups()->where('groups.id', $group_id)->first()->pivot->email))
            GroupInvitation::where('group_id', $group_id)
                ->where('email', $email)
                ->whereNotNull('accepted_at')
                ->whereNull('leaved_at')
                ->update(['leaved_at' => now()]);

        $this->groups()->detach($group_id);

        if ($delete_data)
        {
            UserStatistics::withoutGlobalScope('user_current_group')->where('user_id', '=', $this->id)->where('group_id', '=', $group_id)->delete();
            UserSubscription::withoutGlobalScope('user_current_group')->where('user_id', '=', $this->id)->where('group_id', '=', $group_id)->delete();
            UserAnswer::withoutGlobalScope('user_current_group')->where('user_id', '=', $this->id)->where('group_id', '=', $group_id)->delete();
        }
        $this->update(['current_group' => 1]); // TODO: Replace 1 by default_group global config
        return true;
    }

    /**
     * Update user avatar
     *
     * @param \Nuwave\Lighthouse\Schema\Types\Scalars\Upload $image
     *
     * @return bool
     */
    public function updateAvatar($image): bool
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'users/avatar_'. $this->id . '_' . now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($image), 'public');
        return $this->update(['avatar_url' => $image_name]);
    }

    /**
     * Update user current group statistics
     *
     * @param UserAnswer
     */
    public function updateStats($answers): void
    {
        $groups_answers = $answers->groupBy('group_id');

        foreach ($groups_answers as $group_id => $answers)
        {
            $stats = UserStatistics::withoutGlobalScope('user_current_group')->where('user_id', '=', $this->id)->where('group_id', '=', $group_id);

            // Log if user stats exist (1 per user group) and if not create it

            $stats->increment('bad_answers', $answers->where('result', false)->count());
            $stats->increment('good_answers', $answers->where('result', true)->count());
            $stats->increment('unlocks', $answers->where('is_enduro', false)->count());
            $stats->increment('score', (($answers->where('result', true)->count() * 2) - $answers->where('result', false)->count()));
        }
    }

    /**
     * Get email of user
     * // This is temporary
     * @return string
     */
    public function getEmail()
    {
       $portal_user = Auth::user();
       return $this->groups()->where('groups.id', $portal_user->manageable_group->id)->first()->pivot->email;
    }

    /**
     * Get the population of current group
     * @return string
     */
    public function getGroupPopulation()
    {
        $portal_user = Auth::user();
        return GroupPopulation::find(
            $this->groups()->where(
                'groups.id',
                $portal_user->manageable_group->id
            )->first()->pivot->population_id
        );
    }


    /**
     * Does the user receive push notifications
     *
     * @return boolean
     */
    public function receivePushNotifications(): bool
    {
        if ($this->one_signal_id)
            return true;
        return false;
    }

    /**
     * Does the user receive push notifications
     *
     * @return int
     */
    public function unreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Create group invitations for groups matching
     *
     * @param String $email
     * @return \Illuminate\Support\Collection
     */
    public function createInvitationByDomainMatching($email)
    {
        $email_exploded = explode('@', $email);
        $invitations = collect();
        $allowed_domains = GroupAllowedDomain::where('domain', '=', $email_exploded[1])->get();

        foreach ($allowed_domains as $allowed_domain) {
            if (!GroupInvitation::whereNull('accepted_at')->where('email', $email)->where('group_id', $allowed_domain->group->id)->exists())
                $invitations->push(GroupInvitation::create([
                    'group_id' => $allowed_domain->group->id,
                    'population_id' => $allowed_domain->population->id,
                    'email' => $email,
                    'token' => Str::uuid()->toString()
                ]));
        }
        return $invitations;
    }

    /**
     * Get manageable groups of email
     *
     * @param String $email
     * @return \Illuminate\Support\Collection
     */
    public function createInvitationGroupManagers($email)
    {
        $invitations = collect();
        $email_user = User::where('email', '=', $email);

        if ($email_user->exists()) {
            $user = $email_user->first();
            foreach ($user->manageable_groups as $group) {
                if (!GroupInvitation::whereNull('accepted_at')->where('email', $email)->where('group_id', $group->id)->exists()) {
                    if ($group->populations()->count() == 0)
                        $group->populations()->create([
                            'name' => 'Default population',
                            'description' => 'Default population',
                            'master_key' => Str::lower(Str::random(5)) // Improve to avoid duplicates
                        ]);
                    $invitations->push(GroupInvitation::create([
                        'group_id' => $group->id,
                        'population_id' => $group->populations()->first()->id,
                        'email' => $email,
                        'token' => Str::uuid()->toString()
                    ]));
                }
            }
        }
        return $invitations;
    }

    /**
     * Get groups matching with any pending invitations
     *
     * @param String $email
     * @return array<GroupInvitation>
     */
    public function getPendingInvitations($email)
    {
        $invitations = GroupInvitation::whereNull('accepted_at')->where('email', $email);
        // Avoid spam
        $invitations->update(['updated_at' => now()]);
        return $invitations->get();
    }

    /**
     * Get user info for debugging
     *
     * @return array
     */
    public function DebugInfos() {
        return ['User Infos' => "{$this->username}(#{$this->id}) on device {$this->curr_os} with current group: {$this->group->name}(#{$this->current_group})"];
    }

    /**
     * Get user enduro grades for this user
     *
     * @return Collection
     */
    public function getEnduroGrades()
    {
        $enduro_grades = $this->answers()
            ->select('answered_at')
            ->selectRaw('sum(result) as score')
            ->selectRaw('count(*) as total')
            ->where('is_enduro', true)
            ->groupBy('answered_at')
            ->orderBy('answered_at', 'DESC')
            ->get();
        foreach ($enduro_grades as $enduro_grade)
            $enduro_grade->answers = $this->answers()
                ->where('is_enduro', true)
                ->where('answered_at', $enduro_grade->answered_at)
                ->get();
        return $enduro_grades;
    }

    /**
     * Get user progress for this quizz
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @throws ResolverException
     * @return int
     */
    public function getQuizzProgressPercentage($rootValue, array $args): int
    {
        $quizz = Quizz::find($args['quizz_id']);
        $questions_ids = $quizz->questions()->pluck('id');
        $good_answered_questions = $this->answers()
            ->distinct('question_id')
            ->where('result', true)
            ->whereIn('question_id', $questions_ids)
            ->count('question_id');

        if ($questions_ids->count() == 0)
            return 0;
        return round($good_answered_questions / $questions_ids->count() * 100);
    }

    //
    //
    // From here all method should be useless after graphql usage
    //
    //
    public function sendNotification($notif_array)
    {
        $this->notify(new UserNotification($notif_array)); //Pass the model data to the OneSignal Notificator
    }

    public function sendCongratNotification($isResolved, $question)
    {
        $body = '';
        if ($isResolved)
        {
            $body = 'The question: ' . $question . ' is now resolved';
        }
        else
        {
            $body = 'The question: ' . $question . ' is now closed';
        }
        $notif = [
            'subject' => 'Thank you for your report',
            'body' => $body,
        ];
    }




    public function dailyInstallation($isPortal = false, $group_id = 0)
    {
        $date_start = Request::get('date_start');
        $date_end = Request::get('date_end');
        $users_installation = DB::table('users')->selectRaw('YEAR(created_at) AS \'Year\', MONTH(created_at) AS \'Month\', DAY(created_at) AS \'Day\', COUNT(*) AS \'userinstallation\'')
            ->groupBy(DB::raw('DAY(created_at), MONTH(created_at), YEAR(created_at)'))
            ->orderBy('Year')
            ->orderBy('Month')
            ->orderBy('Day');
        if ($isPortal == true && $group_id > 0)
        {
            $users_installation = $users_installation->where('current_group', $group_id);
        }
        if ($date_start && $date_end)
            $users_installation = $users_installation->whereBetween('users.created_at', array($date_start, $date_end));
        else if ($date_start)
            $users_installation = $users_installation->whereBetween('users.created_at', array($date_start, now()->toDateString()));
        $users_installation = $users_installation->get();

        $months_name = array();
        $monthly_user_data = array();
        foreach ($users_installation as $installation)
        {
            array_push($months_name, $installation->Day . '/' . $installation->Month . '/' . $installation->Year);
            array_push($monthly_user_data, $installation->userinstallation);
        }
        $max = 0;
        if (count($monthly_user_data) > 0)
        {
            $max_user = max($monthly_user_data);
            $max = round(($max_user + 5) / 10) * 10;
        }
        $monthly_chart_data = array(
            'months' => $months_name,
            'users_nb' => $monthly_user_data,
            'max' => $max,
        );
        return $monthly_chart_data;
    }

    public function dailyQuestionAnswered($isPortal = false, $group_id = 0)
    {

        $date_start = Request::get('date_start');
        $date_end = Request::get('date_end');
        $user_questions = DB::table('user_answers')->selectRaw('YEAR(created_at) AS \'Year\', MONTH(created_at) AS \'Month\', DAY(created_at) AS \'Day\', COUNT(*) AS \'useranswer\'')
            ->groupBy(DB::raw('DAY(created_at), MONTH(created_at), YEAR(created_at)'))
            ->orderBy('Year')
            ->orderBy('Month')
            ->orderBy('Day');
        if ($isPortal == true && $group_id > 0)
        {
            $user_questions = $user_questions->where('group_id', $group_id);
        }
        if ($date_start && $date_end)
            $user_questions = $user_questions->whereBetween('user_answers.created_at', array($date_start, $date_end));
        else if ($date_start)
            $user_questions = $user_questions->whereBetween('user_answers.created_at', array($date_start, now()->toDateString()));
        $user_questions = $user_questions->get();
        $months_name = array();
        $monthly_question_data = array();
        foreach ($user_questions as $question)
        {
            array_push($months_name, $question->Day . '/' . $question->Month . '/' . $question->Year);
            array_push($monthly_question_data, $question->useranswer);
        }
        $max = 0;
        if (count($monthly_question_data) > 0)
        {
            $max_user = max($monthly_question_data);
            $max = round(($max_user + 5) / 10) * 10;
        }
        $monthly_chart_data = array(
            'months' => $months_name,
            'questions_nb' => $monthly_question_data,
            'max' => $max,
        );
        return $monthly_chart_data;
    }

    public function userRepartitionInstall($isPortal = false, $group_id = 0)
    {
        $date_start = Request::get('date_start');
        $date_end = Request::get('date_end');
        $repartition = ["ios", "android"];
        $repartition_data = array();
        foreach ($repartition as $rep) {
            $rep_count = $this->where('curr_os', $rep);
            if ($isPortal == true && $group_id > 0)
            {
                $rep_count = $rep_count->where('current_group', $group_id);
            }
            if ($date_start && $date_end)
                $rep_count = $rep_count->whereBetween('users.created_at', array($date_start, $date_end));
            else if ($date_start)
                $rep_count = $rep_count->whereBetween('users.created_at', array($date_start, now()->toDateString()));
            $rep_count = $rep_count->get()->count();
            array_push($repartition_data, $rep_count);
        }
        $repartition_data_array = array(
            'repartition' => $repartition,
            'data' => $repartition_data,
        );
        return $repartition_data_array;
    }

    public function fromGroupId($group_id)
    {
        return $this->select('user_groups.user_id', 'users.email', 'user_groups.group_id', 'users.curr_os', 'users.created_at', 'users.updated_at')->join('user_groups', 'users.id', '=', 'user_groups.user_id')
            ->where('user_groups.group_id', $group_id);
    }

    public function GetQuizzes()
    {
        return $this->select('user_subscriptions.quizz_id', 'user_subscriptions.created_at')->join('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
            ->where('user_subscriptions.user_id', $this->id);
    }

    public function GetQuestions()
    {
        return $this->select('user_answers.question_id', 'user_answers.answered_at', 'user_answers.result')->join('user_answers', 'users.id', '=', 'user_answers.user_id')
            ->where('user_answers.user_id', $this->id);
    }

}
