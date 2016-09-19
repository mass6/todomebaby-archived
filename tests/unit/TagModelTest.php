<?php

use App\Tag;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TagModelTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @var User
     */
    public $user;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([]);
        $this->actingAs($this->user);
    }

    /**
     * Queries are scoped to authenticated user
     *
     * @test
     * @group failing
     */
    public function it_scopes_queries_to_the_specified_user()
    {
        $this->generateTags(5);
        // create tags that belong to another user
        $unscopedUser = factory(User::class)->create([]);
        $this->generateTags(3, $unscopedUser);

        $scopedUsersTags = Tag::all();
        $this->assertCount(5, $scopedUsersTags);
    }

    // Helper Methods


    protected function generateTags($amount = 5, User $user = null, $overrides = [])
    {
        $overrides['user_id'] = $user ? $user->id : $this->user->id;

        return factory(Tag::class, $amount)->create($overrides);
    }

}

