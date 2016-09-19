<?php

use App\Services\TagService;
use App\Tag;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TagServiceTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @var User
     */
    public $user;

    /**
     * @var TagService
     */
    protected $tagService;

    public $tasks;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([]);
        // Set authenticated user
        $this->actingAs($this->user);
        $this->tagService = new TagService();
    }

    // Query Service Tests



    /**
     * Service retrieves all contexts
     *
     * @test
     */
    public function it_retrieves_all_contexts()
    {
        $this->generateUserTasks($this->user, 3, false, [], ['@foo', '@bar', 'bazz']);

        $contexts = $this->tagService->getContexts();

        $this->assertCount(2, $contexts);
        $this->assertArrayHasKey('taskCount', $contexts->first()->toArray());
    }

    /**
     * Service retrieves all contexts
     *
     * @test
     * @group fail
     */
    public function testGetTagSuggestions()
    {
        Tag::create([
            'name' => '@foo',
            'user_id' => $this->user->id,
            'is_context' => true,
        ]);
        Tag::create([
            'name' => 'bar',
            'user_id' => $this->user->id,
            'is_context' => false,
        ]);
        Tag::create([
            'name' => 'baz',
            'user_id' => $this->user->id,
            'is_context' => false,
        ]);

        $contextSuggestions = $this->tagService->getSuggestions('@f');
        $tagSuggestions = $this->tagService->getSuggestions('ba');

        $this->assertCount(1, $contextSuggestions);
        $this->assertEquals('@foo', $contextSuggestions[0]['value']);
        $this->assertCount(2, $tagSuggestions);
        $this->assertEquals('bar', $tagSuggestions[0]['value']);
        $this->assertEquals('baz', $tagSuggestions[1]['value']);
    }


    // Helper Methods

    /**
     * @param User  $user
     * @param int   $amount
     * @param bool  $complete
     * @param array $overrides
     * @param array $tags
     *
     * @return mixed
     */
    protected function generateUserTasks(User $user = null, $amount = 5, $complete = false, $overrides = [], $tags = ['@foo', 'bar'])
    {
        $user = $user ?: $this->user;
        $overrides['complete'] = $complete;
        $tasks = factory(Task::class, $amount)->make($overrides);
        $amount > 1 ? $user->tasks()->saveMany($tasks) : $user->tasks()->save($tasks);

        $tagModels = collect($tags)->map(function($tag) use ($user) {
            return Tag::firstOrCreate([
                'name' => $tag,
                'user_id' => $user->id,
                'is_context' => substr($tag,0,1) === '@' ?: false,
            ]);
        });

        $tasks->each(function($task) use ($tagModels) {
            $task->tags()->attach($tagModels->pluck('id')->all());
        });

        return $tasks;
    }

}
