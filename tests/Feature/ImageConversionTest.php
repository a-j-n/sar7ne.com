<?php

declare(strict_types=1);

use App\Support\ImageConversion;
use Illuminate\Http\UploadedFile;

it('converts png to webp', function () {
    // Create a small PNG in memory
    $im = imagecreatetruecolor(10, 10);
    imagesavealpha($im, true);
    $trans = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $trans);
    ob_start();
    imagepng($im);
    $png = ob_get_clean();
    imagedestroy($im);

    $file = UploadedFile::fake()->createWithContent('test.png', $png);

    $res = ImageConversion::toWebp($file, 82, 50, 50);
    expect($res['mime'])->toBe('image/webp');
    expect($res['filename'])->toEndWith('.webp');
    expect(strlen($res['contents']))->toBeGreaterThan(0);
});
