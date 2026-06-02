<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DeviceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_device_list_is_rendered(): void
    {
        $user = User::factory()->create();
        Device::factory()->count(2)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('devices.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Devices/Index')
                ->has('devices', 2)
            );
    }

    public function test_a_device_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('devices.store'), ['name' => 'My phone'])
            ->assertRedirect(route('devices.index'));

        $this->assertDatabaseHas('devices', [
            'user_id' => $user->id,
            'name' => 'My phone',
        ]);
    }

    public function test_a_device_can_be_updated(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->put(route('devices.update', $device), ['name' => 'Renamed'])
            ->assertRedirect();

        $this->assertSame('Renamed', $device->fresh()->name);
    }

    public function test_a_device_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('devices.destroy', $device))
            ->assertRedirect(route('devices.index'));

        $this->assertModelMissing($device);
    }

    public function test_deleting_a_device_keeps_its_pushes(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);
        $url = Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id]);

        $this->actingAs($user)
            ->delete(route('devices.destroy', $device))
            ->assertRedirect(route('devices.index'));

        // The push must survive (the FK is SET NULL, not CASCADE) and remain
        // a live row, not a soft-deleted one.
        $this->assertModelMissing($device);
        $url->refresh();
        $this->assertNull($url->deleted_at);
        $this->assertNull($url->device_id);
    }

    public function test_a_user_cannot_update_another_users_device(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->actingAs($user)
            ->put(route('devices.update', $device), ['name' => 'Hijacked'])
            ->assertForbidden();

        $this->assertNotSame('Hijacked', $device->fresh()->name);
    }

    public function test_a_user_cannot_delete_another_users_device(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->actingAs($user)
            ->delete(route('devices.destroy', $device))
            ->assertForbidden();

        $this->assertModelExists($device);
    }
}
