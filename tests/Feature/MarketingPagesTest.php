<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_features_page_is_public(): void
    {
        $this->get(route('features'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Features'));
    }

    public function test_the_how_it_works_page_is_public(): void
    {
        $this->get(route('how-it-works'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('HowItWorks'));
    }

    public function test_the_faq_page_is_public(): void
    {
        $this->get(route('faq'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Faq'));
    }

    public function test_the_apk_download_url_is_shared_with_pages(): void
    {
        config(['app.android_apk_url' => '/storage/url-pusher.apk']);

        $this->get(route('welcome'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('apkDownloadUrl', '/storage/url-pusher.apk')
            );
    }
}
