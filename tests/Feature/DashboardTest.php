<?php

namespace Tests\Feature;

use App\Models\Device;
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
}
