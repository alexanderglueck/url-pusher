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
     * @param  array{url: string, device_id: int|string}  $attributes
     */
    public function handle(User $user, array $attributes): Url
    {
        $url = new Url(['url' => $attributes['url']]);
        $url->device_id = $attributes['device_id'];

        $user->urls()->save($url);

        $this->fetchUrlTitle->handle($url);

        return $url;
    }
}
