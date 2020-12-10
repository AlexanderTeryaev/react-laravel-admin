<?php

namespace Tests\Feature;

use App\Author;
use App\Category;
use App\Group;
use App\GroupInvitation;
use App\GroupPopulation;
use App\Question;
use App\Quizz;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $headers = ["x-mrmld-device-id" => "test-device-id"];

    /**
     * Test first user request for account creation
     *
     * @return void
     */
    public function testFirstUserRequest(): void
    {
        $this->withHeaders($this->headers)
            ->query('viewer', ['id', 'username', 'group' => ['id', 'name']])
            ->assertJsonFragment([
                'id' => "1",
                'group' => [
                    'id' => '1',
                    'name' => 'Marmelade'
                ]
            ]);
    }

    /**
     * Test user connection
     *
     * @return void
     */
    public function testUserLogin(): void
    {
        $user = factory(User::class)->create(['current_group_portal' => 1, 'password' => Hash::make('toto42sh')]);
        $user->addInGroupManagers(1);


        $this->withHeaders($this->headers)
            ->mutation('login', ['email' => $user->email, 'password' => 'toto42sh'], ['token', 'viewer' => ['id', 'username']])
            ->assertJsonFragment([
                'login' => null
            ]);

        $user->update(['email_verified_at' => now()]);

        $this->withHeaders($this->headers)
            ->mutation('login', ['email' => $user->email, 'password' => 'toto42sh'], ['token', 'viewer' => ['id', 'username']])
            ->assertJsonFragment([
                'id' => strval($user->id),
                'username' => $user->username
            ]);
    }

    /**
     * Test user updateProfile
     *
     * @return void
     */
    public function testUpdateProfile(): void
    {
        $username = Str::random(10);

        $this->withHeaders($this->headers)
            ->mutation('updateProfile', ['input' => ['username' => $username, 'bio' => 'helloWorld', 'is_onboarded' => true]], ['username', 'bio', 'is_onboarded'])
            ->assertJsonFragment([
                'username' => $username,
                'bio' => 'helloWorld',
                'is_onboarded' => true
            ]);

        $player_id = Str::random(36 );
        $this->withHeaders($this->headers)
            ->mutation('updateProfile', ['input' => ['bio' => '', 'one_signal_id' => $player_id]], ['username', 'bio', 'is_onboarded', 'one_signal_id'])
            ->assertJsonFragment([
                'username' => $username,
                'bio' => '',
                'is_onboarded' => true,
                'one_signal_id' => $player_id
            ]);
    }

    /**
     * Upload avatar url
     *
     * @return void
     */
    public function testUploadAvatar(): void
    {
        $response = $this->withHeaders($this->headers)->multipartGraphQL([
            'operations' => /* @lang JSON */
                '
                {
                    "query": "mutation Upload($file: Upload!) { updateProfile(input: { avatar: $file }) { avatar_url } }",
                    "variables": {
                        "file": null
                    }
                }
                ',
            'map' => /* @lang JSON */
                '
                    {
                        "0": ["variables.file"]
                    }
                ',
            ],
            [
                '0' => UploadedFile::fake()->create('image.jpg', 1000),
            ]
        )->decodeResponseJson();

        Storage::assertExists($response['data']['updateProfile']['avatar_url']);
    }

    /**
     * Update user profile
     * @deprecated Use updateProfile mutation
     *
     * @return void
     */
    public function testUpdateProfileOld(): void
    {
        // Deprecated mutations bellow
        $username = Str::random(10);

        $this->withHeaders($this->headers)
            ->mutation('updateUsername', ['username' => $username], ['username'])
            ->assertJsonFragment([
                'username' => $username
            ]);

        $this->withHeaders($this->headers)
            ->mutation('updateUsername', ['username' => $username], ['username'])
            ->assertJsonFragment([
                'username' => $username
            ]);

        $this->withHeaders($this->headers)
            ->mutation('updateBio', ['bio' => 'Hello World'], ['bio'])
            ->assertJsonFragment([
                'bio' => 'Hello World'
            ]);

        $this->withHeaders($this->headers)
            ->mutation('updateBio', ['bio' => 'Too long string must throw an validation error, lorem ipsum'], ['bio'])
            ->assertJsonFragment([
                'updateBio' => null
            ]);
    }


    /**
     * Test user quizz subscription
     *
     * @return void
     */
    public function testUserSubscribeCategoryQuizzes(): void
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $category = factory(Category::class)->create(['group_id' => 1]);
        $quizzes = factory(Quizz::class, 10)->create(['group_id' => 1, 'author_id' => $author->id, 'is_published' => true]);

        $category->quizzes()->attach($quizzes->pluck('id'));

        $result = collect();
        foreach ($quizzes as $quizz)
            $result->push([
                'quizz' => ['id' => strval($quizz->id)]
            ]);

        $this->withHeaders($this->headers)
            ->mutation('subscribeCategoryQuizzes', ['category_id' => $category->id], ['quizz' => ['id']])
            ->assertJsonFragment([
                'subscribeCategoryQuizzes' => $result
            ]);
    }

    /**
     * Test user quizz subscription
     *
     * @return void
     */
    public function testUserSubscribeQuizz(): void
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->states( 'published')->states( 'published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders($this->headers)
            ->mutation('subscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']])
            ->assertJsonFragment([
                'quizz' => [
                    'id' => strval($quizz->id)
                ]
            ]);

        $this->withHeaders($this->headers)
            ->mutation('subscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']])
            ->assertJsonFragment([
                'subscribeQuizz' => null
            ]);

    }

    /**
     * Test user quizz unsubscription
     *
     * @return void
     */
    public function testUserQuizzUnsubscription(): void
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->states( 'published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders($this->headers)
            ->mutation('subscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']])
            ->assertJsonFragment([
                'quizz' => [
                    'id' => strval($quizz->id)
                ]
            ]);

        $this->withHeaders($this->headers)
            ->mutation('unsubscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']])
            ->assertJsonFragment([
                'quizz' => [
                    'id' => strval($quizz->id)
                ]
            ]);

        $this->withHeaders($this->headers)
            ->mutation('unsubscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']])
            ->assertJsonFragment([
                'unsubscribeQuizz' => null
            ]);
    }

    /**
     * Test user join group by masterKey whose trial
     * period has ended and no subscribed
     *
     * @return void
     */
    public function testUserJoinClosedGroup(): void
    {
        $group = factory(Group::class)->create(['trial_ends_at' => now()->subDays(10)]);
        $population = factory(GroupPopulation::class)->create(['group_id' => $group->id]);
        $this->withHeaders($this->headers)
            ->mutation('joinGroup', ['code' => $population->master_key, 'switch' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'joinGroup' => null
            ]);

    }

    /**
     * Test user join group by MasterKey
     *
     * @return void
     */
    public function testUserJoinGroupByMasterKey(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        $population = factory(GroupPopulation::class)->create(['group_id' => $group->id]);
        $this->withHeaders($this->headers)
            ->mutation('joinGroup', ['code' => $population->master_key, 'switch' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'joinGroup' => [
                    [
                        'id' => strval($group->id),
                        'is_current' => true
                    ]
                ]
            ]);

        // Switch in default group
        $this->withHeaders($this->headers)
            ->mutation('switchGroup', ['group_id' => 1], ['id', 'is_current'])
            ->assertJsonFragment([
                'switchGroup' => [
                    'id' => "1",
                    'is_current' => true
                ]
            ]);

        // Make trial end
        $group->update(['trial_ends_at' => now()->subDays(10)]);

        // Try to switch in group
        $this->withHeaders($this->headers)
            ->mutation('switchGroup', ['group_id' => $group->id], ['id', 'is_current'])
            ->assertJsonFragment([
                'switchGroup' => null
            ]);
    }


    /**
     * Test manager createEmailInvitation
     *
     * @return void
     */
    public function testManagerCreateInvitation(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        $population = factory(GroupPopulation::class)->create(['group_id' => $group->id]);

        $user = factory(User::class)->state('verified')->create(['current_group_portal' => $group->id]);
        $user->addInGroupManagers($group->id);
        $token = Auth::tokenById($user->id);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('createGroupInvitations', [
                'population_id' => $population->id,
                'emails' => ['john@doe.com'],

            ], ['email'])
            ->assertJsonFragment([
                'email' => 'john@doe.com',
            ]);
    }

    /**
     * Test user join group by invitation
     *
     * @return void
     */
    public function testUserJoinGroupByInvitation(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        $population = factory(GroupPopulation::class)->create(['group_id' => $group->id]);
        $email = 'john@doe.com';

        $invitation = GroupInvitation::create([
            'group_id' => $group->id,
            'population_id' => $population->id,
            'email' => $email,
            'token' => Str::uuid()->toString()
        ]);


        $this->withHeaders($this->headers)
            ->mutation('joinGroup', ['code' => $invitation->token, 'switch' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'joinGroup' => [
                    [
                        'id' => strval($group->id),
                        'is_current' => true
                    ]
                ]
            ]);
    }

    /**
     * Test user join group by email domain
     *
     * @return void
     */
    public function testUserJoinGroupByEmailDomain(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        $population = factory(GroupPopulation::class)->create(['group_id' => $group->id]);
        $email = 'john@doe.com';
        $group->allowed_domains()->create([
            'population_id' => $population->id,
            'domain' => explode('@', $email)[1]
        ]);

        $this->withHeaders($this->headers)
            ->mutation('sendGroupEmailVerification', ['email' => $email], null)
            ->assertJsonFragment([
                'sendGroupEmailVerification' => true
            ]);

        $invitation = GroupInvitation::where('email', $email)->first();

        $this->withHeaders($this->headers)
            ->mutation('joinGroup', ['code' => $invitation->token, 'switch' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'joinGroup' => [
                    [
                        'id' => strval($group->id),
                        'is_current' => true
                    ]
                ]
            ]);
    }

    /**
     * Test user join group when email is group manager
     *
     * @return void
     */
    public function testUserJoinGroupForManagers(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        factory(GroupPopulation::class)->create(['group_id' => $group->id]);
        $user = factory(User::class)->state('verified')->create(['current_group_portal' => $group->id]);
        $user->addInGroupManagers($group->id);


        $this->withHeaders($this->headers)
            ->mutation('sendGroupEmailVerification', ['email' => $user->email], null)
            ->assertJsonFragment([
                'sendGroupEmailVerification' => true
            ]);

        $invitation = GroupInvitation::where('email', $user->email)->first();

        $this->withHeaders($this->headers)
            ->mutation('joinGroup', ['code' => $invitation->token, 'switch' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'joinGroup' => [
                    [
                        'id' => strval($group->id),
                        'is_current' => true
                    ]
                ]
            ]);
    }

    /**
     * Test user join several group
     *
     * @return void
     */
    public function testUserJoinSeveralGroups(): void
    {
        // Group where user is manager
        $group_m = factory(Group::class)->states('onTrial')->create();
        factory(GroupPopulation::class)->create(['group_id' => $group_m->id]);
        $user = factory(User::class)->state('verified')->create(['current_group_portal' => $group_m->id]);
        $user->addInGroupManagers($group_m->id);

        // Group where user is invited
        $group_i = factory(Group::class)->states('onTrial')->create();
        $population = factory(GroupPopulation::class)->create(['group_id' => $group_i->id]);
        $i = GroupInvitation::create([
            'group_id' => $group_i->id,
            'population_id' => $population->id,
            'email' => $user->email,
            'token' => Str::uuid()->toString(),
        ]);

        $i->created_at = now()->subDay();
        $i->updated_at = now()->subDay();
        $i->save();

        // Group where user email match with allowed domain of one group
        $group_a = factory(Group::class)->states('onTrial')->create();
        $population = factory(GroupPopulation::class)->create(['group_id' => $group_a->id]);
        $group_a->allowed_domains()->create([
            'population_id' => $population->id,
            'domain' => explode('@', $user->email)[1]
        ]);

        // Send email verification
        $this->withHeaders($this->headers)
            ->mutation('sendGroupEmailVerification', ['email' => $user->email], null)
            ->assertJsonFragment([
                'sendGroupEmailVerification' => true
            ]);

        $invitations = GroupInvitation::where('email', $user->email)->get();

        $this->assertCount(3, $invitations);

        foreach ($invitations as $invitation)
            $this->withHeaders($this->headers)
                ->mutation('joinGroup', ['code' => $invitation->token], ['id'])
                ->assertJsonFragment([
                    'joinGroup' => [
                        [
                            'id' => strval($invitation->group_id)
                        ]
                    ]
                ]);
    }

    /**
     * Test user switch group
     *
     * @return void
     */
    public function testUserSwitchInGroup(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        $user = factory(User::class)->state('verified')->create(['current_group' => 1]);
        $user->addInGroup($group->id, 'email', $user->email);


        $this->withHeaders(["x-mrmld-device-id" => $user->device_id])
            ->mutation('switchGroup', ['group_id' => $group->id], ['id', 'is_current'])
            ->assertJsonFragment([
                'switchGroup' => [
                    'id' => strval($group->id),
                    'is_current' => true
                ]
            ]);
    }

    /**
     * Test user try to switch in closed group
     *
     * @return void
     */
    public function testUserSwitchInClosedGroup(): void
    {
        $group = factory(Group::class)->states('closed')->create();
        $user = factory(User::class)->state('verified')->create(['current_group' => 1]);
        $user->addInGroup($group->id, 'email', $user->email);


        $this->withHeaders(["x-mrmld-device-id" => $user->device_id])
            ->mutation('switchGroup', ['group_id' => $group->id], ['id', 'is_current'])
            ->assertJson([
                'data' => [
                    'switchGroup' => null
                ]
            ]);
    }

    /**
     * Test user tries to switch to a group in which he is not in.
     *
     * @return void
     */
    public function testUserSwitchInGroupIsNotIn(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        $user = factory(User::class)->state('verified')->create(['current_group' => 1]);


        $this->withHeaders(["x-mrmld-device-id" => $user->device_id])
            ->mutation('switchGroup', ['group_id' => $group->id], ['id', 'is_current'])
            ->assertJson([
                'data' => [
                    'switchGroup' => null
                ]
            ]);
    }

    /**
     * Test user join by MasterKey and leave group
     *
     * @return void
     */
    public function testUserLeaveGroup(): void
    {
        $group = factory(Group::class)->states('onTrial')->create();
        $population = factory(GroupPopulation::class)->create(['group_id' => $group->id]);

        $this->withHeaders($this->headers)
            ->mutation('joinGroup', ['code' => $population->master_key, 'switch' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'joinGroup' => [
                    [
                        'id' => strval($group->id),
                        'is_current' => true
                    ]
                ]
            ]);

        $this->withHeaders($this->headers)
            ->mutation('leaveGroup', ['group_id' => $group->id, 'delete_data' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'leaveGroup' => [
                    'id' => strval($group->id),
                    'is_current' => false
                ]
            ]);

        $this->withHeaders($this->headers)
            ->mutation('leaveGroup', ['group_id' => 1, 'delete_data' => true], ['id', 'is_current'])
            ->assertJsonFragment([
                'leaveGroup' => null
            ]);
    }

    /**
     * Test user join/leave group by master key
     *
     * @return void
     */
    public function testUserAnswers(): void
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->states( 'published')->create(['group_id' => 1, 'author_id' => $author->id]);
        $questions = factory(Question::class, 10)->create(['group_id' => 1, 'quizz_id' => $quizz->id, 'status' => true]);

        $answers = collect();

        foreach ($questions as $question)
            $answers->push([
                'question_id' => $question->id,
                'result' => boolval(rand(0, 1)),
                'is_enduro' => boolval(rand(0, 1)),
                'answered_at' => now()->toDateTimeString()
            ]);

        $result = collect();
        foreach ($answers as $answer)
            $result->push([
                'question' => [
                    'id' => strval($answer['question_id'])
                ],
                'result' => $answer['result'],
                'is_enduro' => $answer['is_enduro'],
                'answered_at' => $answer['answered_at'],

            ]);

        $this->withHeaders($this->headers)
            ->mutation('submitAnswers', ['input' => $answers->toArray()], ['question' => ['id'], 'result', 'is_enduro', 'answered_at'])
            ->assertJson([
                'data' => [
                    'submitAnswers' => $result->toArray()
                ]
            ]);

        $this->withHeaders($this->headers)
            ->query('viewer', ['answers' => ['data' => ['question' => ['id'], 'result', 'is_enduro', 'answered_at']] ])
            ->assertJson([
                'data' => [
                    'viewer' => [
                        'answers' => [
                            'data' => $result->toArray()
                        ]
                    ]
                ]
            ]);
    }
}
