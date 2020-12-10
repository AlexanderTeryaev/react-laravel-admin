<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create a group
     *
     * @return void
     */
    public function testCreateGroup()
    {
        $this->mutation('createGroup', [
                'input' => [
                    'name' => 'group_test',
                    'first_name' => 'first_name_test',
                    'last_name' => 'last_name_test',
                    'email' => 'email_test@test.com',
                    'phone_number' => '+33606060606',
                    'password' => 'password_test_45'
                ]
            ])
            ->assertJson([
                'data' => [
                    'createGroup' => true
                ]
            ]);
    }

    /**
     * Test update a group
     *
     * @return void
     */
    public function testUpdateGroup()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('updateGroup', [
            'input' => [
                'name' => 'group_name_test',
                'description' => 'desc_test'
            ]
        ], ['name', 'description'])
            ->assertJson([
                'data' => [
                    'updateGroup' => [
                        'name' => 'group_name_test',
                        'description' => 'desc_test',
                    ]
                ]
            ]);
    }
}
