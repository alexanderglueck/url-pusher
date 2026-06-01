<?php

namespace App\Actions\Urls;

use App\Models\Url;
use Embed\Embed;

class FetchUrlTitle
{
    public function __construct(private readonly Embed $embed) {}

    /**
     * Fetch the page title for the given URL and persist it.
     *
     * Falls back to the URL itself when no title can be determined.
     */
    public function handle(Url $url): void
    {
        $info = $this->embed->get($url->url);

        $url->title = $info->title ?: $url->url;
        $url->save();
    }
}
