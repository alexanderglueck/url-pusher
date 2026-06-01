<?php

namespace App\Actions\Urls;

use App\Models\Url;
use Embed\Embed;

class FetchUrlTitle
{
    public function __construct(private readonly Embed $embed) {}

    /**
     * Fetch the page title, description and preview image for the URL and
     * persist them. Falls back to the URL itself when no title is found.
     */
    public function handle(Url $url): void
    {
        $info = $this->embed->get($url->url);

        $url->title = $info->title ?: $url->url;
        $url->description = $info->description;
        $url->image = $info->image ? (string) $info->image : null;
        $url->save();
    }
}
