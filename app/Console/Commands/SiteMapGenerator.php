<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class SiteMapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        // Add homepage
        $sitemap .= '    <url>'.PHP_EOL;
        $sitemap .= '        <loc>'.URL::to('/').'</loc>'.PHP_EOL;
        $sitemap .= '    </url>'.PHP_EOL;

        // Add user profiles
        $users = User::all();
        foreach ($users as $user) {
            $sitemap .= '    <url>'.PHP_EOL;
            $sitemap .= '        <loc>'.URL::to('/profile/'.$user->username).'</loc>'.PHP_EOL;
            $sitemap .= '    </url>'.PHP_EOL;
        }

        $sitemap .= '</urlset>'.PHP_EOL;

        // Save the sitemap to the root public directory
        file_put_contents(public_path('sitemap.xml'), $sitemap);

        $this->info('Sitemap generated successfully.');

        return Command::SUCCESS;
    }
}
