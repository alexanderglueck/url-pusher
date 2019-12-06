<?php

namespace App\Console\Commands;

use Embed\Embed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchMissingTitles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'url-pusher:fetch-missing-titles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches missing titles from URLs that were pushed before titles were fetched';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('urls')->select('id', 'url')->whereNull('title')->chunkById(100, function ($urls) {
            foreach ($urls as $url) {

                $info = Embed::create($url->url);

                DB::table('urls')
                    ->where('id', $url->id)
                    ->update(['title' => $info->title ?: $url->url]);
            }
        });
    }
}
