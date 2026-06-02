<?php

namespace App\Actions\Urls;

use App\Models\Url;
use App\Models\User;

class StoreUrl
{
    public function __construct(private readonly FetchUrlTitle $fetchUrlTitle) {}

    /**
     * Store a URL for the user against the given device and resolve its title.
     *
     * The device is referenced by its public ULID; we resolve it (scoped to the
     * user) to the internal foreign key.
     *
     * @param  array{url: string, device_id: string}  $attributes
     */
    public function handle(User $user, array $attributes): Url
    {
        $device = $user->devices()->where('ulid', $attributes['device_id'])->firstOrFail();

        $url = new Url(['url' => $attributes['url']]);
        $url->device_id = $device->id;
        $url->push_status = Url::PUSH_PENDING;

        $user->urls()->save($url);

        $this->fetchUrlTitle->handle($url);

        return $url;
    }
}
