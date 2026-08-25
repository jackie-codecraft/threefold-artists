<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\LeadershipMemberResource;
use App\Filament\Resources\LeadershipMemberResource\Pages\ListLeadershipMembers;
use App\Models\LeadershipMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LeadershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_displays_only_visible_leadership_members_in_display_order(): void
    {
        $second = LeadershipMember::query()->create([
            'name' => 'Zoe Second',
            'title' => 'Executive Director',
            'biography' => '<p>Second biography.</p>',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $first = LeadershipMember::query()->create([
            'name' => 'Ada First',
            'title' => 'Artistic Director',
            'biography' => '<p>First biography. '.str_repeat('A long leadership biography should remain available without overwhelming the About page. ', 8).'</p>',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $hidden = LeadershipMember::query()->create([
            'name' => 'Hidden Member',
            'title' => 'Former Director',
            'biography' => '<p>This must not appear.</p>',
            'sort_order' => 0,
            'is_active' => false,
        ]);

        $response = $this->get(route('about'));

        $response->assertOk()
            ->assertSeeInOrder(['How It Started', 'Leadership', 'Our Three Promises'])
            ->assertSeeInOrder([$first->name, $second->name])
            ->assertSee($first->title)
            ->assertSee('First biography.', false)
            ->assertSee('Read full biography')
            ->assertSee('<details', false)
            ->assertDontSee($hidden->name);
    }

    public function test_new_leadership_members_append_to_the_end_of_the_display_order(): void
    {
        $first = LeadershipMember::query()->create([
            'name' => 'First Member',
            'title' => 'Director',
            'biography' => '<p>Biography</p>',
        ]);

        $second = LeadershipMember::query()->create([
            'name' => 'Second Member',
            'title' => 'Director',
            'biography' => '<p>Biography</p>',
        ]);

        $this->assertSame(1, $first->sort_order);
        $this->assertSame(2, $second->sort_order);
    }

    public function test_admin_can_access_leadership_management_and_create_action(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(LeadershipMemberResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Leadership Member');

        Livewire::actingAs($user)
            ->test(ListLeadershipMembers::class)
            ->assertSuccessful()
            ->assertActionExists('create')
            ->assertActionHasLabel('create', 'Add leadership member');
    }
}
