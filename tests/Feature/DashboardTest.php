<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_the_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_the_dashboard_only_lists_pushable_devices(): void
    {
        $user = User::factory()->create();

        Device::factory()->create(['user_id' => $user->id, 'device_token' => 'token']);
        Device::factory()->create(['user_id' => $user->id, 'device_token' => null]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->has('devices', 1)
                ->has('urls', 0)
            );
    }

    public function test_devices_are_listed_newest_first(): void
    {
        $user = User::factory()->create();

        $older = Device::factory()->create([
            'user_id' => $user->id,
            'device_token' => 'token',
            'created_at' => now()->subDay(),
        ]);
        $newer = Device::factory()->create([
            'user_id' => $user->id,
            'device_token' => 'token',
            'created_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('devices.0.id', $newer->ulid)
                ->where('devices.1.id', $older->ulid)
            );
    }

    public function test_the_history_can_be_searched(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);
        Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id, 'title' => 'Apple keynote']);
        Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id, 'title' => 'Banana bread']);

        $this->actingAs($user)
            ->get(route('dashboard', ['q' => 'Apple']))
            ->assertInertia(fn (Assert $page) => $page
                ->has('urls', 1)
                ->where('urls.0.title', 'Apple keynote')
            );
    }

    public function test_the_history_can_be_filtered_to_favorites(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);
        Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id, 'is_favorite' => true]);
        Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id, 'is_favorite' => false]);

        $this->actingAs($user)
            ->get(route('dashboard', ['favorites' => 1]))
            ->assertInertia(fn (Assert $page) => $page->has('urls', 1));
    }

    public function test_the_history_is_capped_and_can_load_more(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);
        Url::factory()->count(16)->create(['user_id' => $user->id, 'device_id' => $device->id]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page->has('urls', 15)->where('canLoadMore', true));

        $this->actingAs($user)
            ->get(route('dashboard', ['limit' => 30]))
            ->assertInertia(fn (Assert $page) => $page->has('urls', 16)->where('canLoadMore', false));
    }
}
