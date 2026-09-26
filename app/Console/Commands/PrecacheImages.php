<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PrecacheImages extends Command
{
    protected $signature = 'images:precache {--widths=320,480,640,800,1200,1920}';

    protected $description = 'Pre-generate optimized image variants';

    public function handle(): int
    {
        $widths = collect(explode(',', (string) $this->option('widths')))
            ->map(fn ($value) => (int) trim($value))
            ->filter(fn ($value) => $value > 0)
            ->unique()
            ->values()
            ->all();

        $kernel = app(Kernel::class);

        $paths = array_merge(
            $this->collectRelativePaths(storage_path('app/public'), ''),
            $this->collectRelativePaths(public_path('img'), 'img/')
        );

        $paths = array_values(array_unique($paths));

        $this->info('Found ' . count($paths) . ' images.');

        $bar = $this->output->createProgressBar(count($paths));
        $bar->start();

        foreach ($paths as $relative) {
            $this->hit($kernel, $relative, null);

            foreach ($widths as $w) {
                $this->hit($kernel, $relative, $w);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return self::SUCCESS;
    }

    private function collectRelativePaths(string $root, string $prefix): array
    {
        if (!File::isDirectory($root)) {
            return [];
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
        $paths = [];

        foreach (File::allFiles($root) as $file) {
            $relative = Str::replace('\\', '/', $file->getRelativePathname());

            if (str_contains($relative, '/.imgcache/') || str_starts_with($relative, '.imgcache/')) {
                continue;
            }

            $ext = strtolower($file->getExtension());
            if (!in_array($ext, $allowed, true)) {
                continue;
            }

            $paths[] = $prefix . $relative;
        }

        return $paths;
    }

    private function hit(Kernel $kernel, string $relative, ?int $w): void
    {
        $query = $w ? ('?w=' . $w) : '';
        $request = Request::create('/i/' . $relative . $query, 'GET', [], [], [], [
            'HTTP_ACCEPT' => 'image/avif,image/webp,image/*;q=0.8,*/*;q=0.5',
        ]);

        $response = $kernel->handle($request);
        $response->sendContent();
        $kernel->terminate($request, $response);
    }
}

