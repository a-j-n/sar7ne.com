<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class GeneratePostsSitemap extends Command
{
    protected $signature = 'posts:sitemap {--disk=public} {--chunk=50000} {--prefix=sitemap-posts} {--gzip}';

    protected $description = 'Generate chunked sitemap files for posts and a sitemap index.';

    public function handle(): int
    {
        $disk = (string) $this->option('disk');
        $chunkSize = (int) $this->option('chunk');
        $prefix = (string) $this->option('prefix');
        $gzip = (bool) $this->option('gzip');

        if ($chunkSize < 1 || $chunkSize > 50000) {
            $this->warn('Chunk size must be between 1 and 50000. Using 50000.');
            $chunkSize = 50000;
        }

        $fs = Storage::disk($disk);

        $indexEntries = [];
        $fileIndex = 0;
        $urlCountInFile = 0;
        $buffer = '';

        $openSitemap = function () use (&$buffer) {
            $buffer = '<?xml version="1.0" encoding="UTF-8"?>\n';
            $buffer .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
        };
        $closeSitemap = function () use (&$buffer) {
            $buffer .= '</urlset>';
        };

        $writeSitemap = function () use (&$buffer, &$fileIndex, &$indexEntries, $fs, $prefix, $gzip, $disk) {
            if ($buffer === '') {
                return;
            }
            $fileIndex++;
            $filename = $prefix.'-'.$fileIndex.'.xml';
            $contents = $buffer;
            if ($gzip) {
                $filename .= '.gz';
                $contents = gzencode($contents, 6);
            }
            $fs->put($filename, $contents);
            $absoluteUrl = $this->absoluteUrl($fs->url($filename));
            $indexEntries[] = [
                'loc' => $absoluteUrl,
                'lastmod' => Carbon::now()->toAtomString(),
            ];
            // reset buffer and counters for next file
            $buffer = '';
        };

        $openSitemap();

        Post::query()
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->select(['id', 'updated_at'])
            ->chunkById(5000, function ($posts) use (&$buffer, &$urlCountInFile, $chunkSize, $openSitemap, $closeSitemap, $writeSitemap) {
                foreach ($posts as $post) {
                    $loc = route('posts.show', $post);
                    $lastmod = optional($post->updated_at)->toAtomString() ?? now()->toAtomString();
                    $buffer .= '  <url>\n';
                    $buffer .= '    <loc>'.e($loc).'</loc>\n';
                    $buffer .= '    <lastmod>'.$lastmod.'</lastmod>\n';
                    $buffer .= '  </url>\n';
                    $urlCountInFile++;

                    if ($urlCountInFile >= $chunkSize) {
                        $closeSitemap();
                        $writeSitemap();
                        $urlCountInFile = 0;
                        $openSitemap();
                    }
                }
            }, 'id');

        if ($urlCountInFile > 0 && $buffer !== '') {
            $closeSitemap();
            $writeSitemap();
        }

        // Write sitemap index
        $indexBuffer = '<?xml version="1.0" encoding="UTF-8"?>\n';
        $indexBuffer .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
        foreach ($indexEntries as $entry) {
            $indexBuffer .= '  <sitemap>\n';
            $indexBuffer .= '    <loc>'.e($entry['loc']).'</loc>\n';
            $indexBuffer .= '    <lastmod>'.$entry['lastmod'].'</lastmod>\n';
            $indexBuffer .= '  </sitemap>\n';
        }
        $indexBuffer .= '</sitemapindex>';

        $indexName = $prefix.'-index.xml';
        $fs->put($indexName, $indexBuffer);

        $this->info('Generated '.count($indexEntries).' sitemap file(s) + index on disk "'.$disk.'".');
        $this->line('Index: '.$this->absoluteUrl($fs->url($indexName)));

        return self::SUCCESS;
    }

    protected function absoluteUrl(string $url): string
    {
        // Ensure absolute URL (Storage::url may already be absolute when using CDN)
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return config('app.url').'/'.ltrim($url, '/');
    }
}

