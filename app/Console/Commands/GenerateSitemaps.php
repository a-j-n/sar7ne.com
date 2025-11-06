<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class GenerateSitemaps extends Command
{
    protected $signature = 'sitemaps:generate {--disk=public} {--chunk=50000} {--prefix=sitemap} {--gzip}';

    protected $description = 'Generate chunked sitemap files (posts and profiles) and a sitemap index.';

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

        // Helper to write a single sitemap buffer and push index entry
        $writePart = function (string $name, string $buffer) use ($gzip, $fs, &$indexEntries) {
            $filename = $name.'.xml';
            $contents = $buffer;
            if ($gzip) {
                $filename .= '.gz';
                $contents = gzencode($contents, 6);
            }
            $fs->put($filename, $contents);
            $indexEntries[] = [
                'loc' => $this->absoluteUrl($fs->url($filename)),
                'lastmod' => Carbon::now()->toAtomString(),
            ];
        };

        // Generate posts sitemaps
        $this->generateModelSitemaps(
            query: Post::query()->whereNull('deleted_at')->orderBy('id')->select(['id', 'updated_at']),
            loc: fn ($post) => route('posts.show', $post),
            partPrefix: $prefix.'-posts',
            chunkSize: $chunkSize,
            writer: $writePart,
        );

        // Generate profiles sitemaps
        $this->generateModelSitemaps(
            query: User::query()->orderBy('id')->select(['id', 'username', 'updated_at']),
            loc: fn ($user) => route('profiles.show', $user),
            partPrefix: $prefix.'-profiles',
            chunkSize: $chunkSize,
            writer: $writePart,
        );

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

        $this->info('Sitemaps generated for posts and profiles. Index: '.$this->absoluteUrl($fs->url($indexName)));

        return self::SUCCESS;
    }

    protected function generateModelSitemaps($query, callable $loc, string $partPrefix, int $chunkSize, callable $writer): void
    {
        $fileIndex = 0;
        $urlCountInFile = 0;
        $buffer = '';

        $open = function () use (&$buffer) {
            $buffer = '<?xml version="1.0" encoding="UTF-8"?>\n';
            $buffer .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
        };
        $close = function () use (&$buffer) {
            $buffer .= '</urlset>';
        };
        $flush = function () use (&$writer, &$buffer, &$fileIndex, $partPrefix) {
            $fileIndex++;
            $writer($partPrefix.'-'.$fileIndex, $buffer);
            $buffer = '';
        };

        $open();
        $query->chunkById(5000, function ($items) use (&$buffer, &$urlCountInFile, $chunkSize, $open, $close, $flush, $loc) {
            foreach ($items as $item) {
                $url = $loc($item);
                $lastmod = optional($item->updated_at)->toAtomString() ?? now()->toAtomString();
                $buffer .= '  <url>\n';
                $buffer .= '    <loc>'.e($url).'</loc>\n';
                $buffer .= '    <lastmod>'.$lastmod.'</lastmod>\n';
                $buffer .= '  </url>\n';
                $urlCountInFile++;
                if ($urlCountInFile >= $chunkSize) {
                    $close();
                    $flush();
                    $urlCountInFile = 0;
                    $open();
                }
            }
        }, 'id');

        if ($urlCountInFile > 0 && $buffer !== '') {
            $close();
            $flush();
        }
    }

    protected function absoluteUrl(string $url): string
    {
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return config('app.url').'/'.ltrim($url, '/');
    }
}
