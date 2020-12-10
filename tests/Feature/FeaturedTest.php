<?php


namespace Tests\Feature;

use App\Featured;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FeaturedTest extends TestCase
{
    use RefreshDatabase;

    private $headers = ["x-mrmld-device-id" => "test-device-id"];

    /**
     * Test user create featured
     *
     * @return void
     */
    public function testCreateFeatured()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('createFeatured', [
                'input' => [
                    'name' => 'feat_test',
                    'description' => 'feat_test_desc',
                    'order_id' => 1,
                    'is_published' => true
                ]
            ], ['name', 'description', 'order_id', 'is_published'])
            ->assertJsonFragment([
                'name' => 'feat_test',
                'description' => 'feat_test_desc',
                'order_id' => 1,
                'is_published' => true,
            ]);
    }

    /**
     * Test user update featured
     *
     * @return void
     */
    public function testUpdateFeatured()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $featured = factory(Featured::class)->state('unpublished')->create(['group_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('updateFeatured', [
                'featured_id' => $featured->id,
                'input' => [
                    'name' => 'feat_test',
                    'description' => 'feat_test_desc',
                    'order_id' => 2,
                    'is_published' => false
                ]
            ], ['name', 'description', 'order_id', 'is_published'])
            ->assertJsonFragment([
                'name' => 'feat_test',
                'description' => 'feat_test_desc',
                'order_id' => 2,
                'is_published' => false
            ]);
    }

    /**
     * Test user delete featured
     *
     * @return void
     */
    public function testDeleteFeatured()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $featured = factory(Featured::class)->create(['group_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('deleteFeatured', ['featured_id' => $featured->id], ['id'])
            ->assertJson([
                'data' => [
                    'deleteFeatured' => [
                        'id' => $featured->id
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('featured', [
            'id' => $featured->id
        ]);
    }

    /**
     * Test user get featured
     *
     * @return void
     */
    public function testGetAllFeatured()
    {
        factory(Featured::class, 5)->create(['group_id' => 1]);
        $featured_formatted = collect();

        foreach (Featured::published()->orderBy('order_id', 'ASC')->get() as $f)
            $featured_formatted->push([
                'id' => strval($f->id),
                'name' => $f->name,
                'pic_url' => $f->pic_url,
                'created_at' => $f->created_at->toDateTimeString(),
                'order_id' => $f->order_id
            ]);

        $this->withHeaders($this->headers)
            ->query('featured', ['id', 'name', 'pic_url', 'created_at', 'order_id'])
            ->assertJson([
                'data' => [
                    'featured' => $featured_formatted->toArray()
                    ]
            ]);
    }

    /**
     * Test user get featured
     *
     * @return void
     */
    public function testGetFeatured()
    {
        $feat = factory(Featured::class)->create(['group_id' => 1]);

        $this->withHeaders($this->headers)
            ->query('getFeatured', ['id' => $feat->id], ['id', 'name', 'pic_url', 'created_at'])
            ->assertJsonFragment([
                'id' => strval($feat->id),
                'name' => $feat->name,
                'pic_url' => $feat->pic_url,
                'created_at' => $feat->created_at->toDateTimeString(),
            ]);
    }
}