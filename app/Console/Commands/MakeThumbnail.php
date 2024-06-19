<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\Traversable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class MakeThumbnail extends Command
{
    use Traversable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-thumbnail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes thumbnails of template images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dir = 'public/template_images';
        $files = Storage::files($dir);
        $manager = new ImageManager(new Driver());
        foreach ($files as $template_image) {
            $thumbDir = 'thumbnails';
            $scaledPath = (Storage::path($dir) . '/' . $thumbDir . '/' . $this->getEndName($template_image));
            if (!Storage::exists($dir . '/' . $thumbDir)) {
                Storage::makeDirectory($dir . '/' . $thumbDir);
            }
            try {
                //$imagefile = Storage::get($template_image);
                [$width, $height] = getimagesize(Storage::path($template_image));
                //if ($width > 1024 || $height > 1024) {
                $image = $manager->read(Storage::path($template_image));
                //$image->scale(width: 1024);
                $image->encode(new JpegEncoder(quality: 60))->save($scaledPath);
                $this->line("image is compressed : " . $scaledPath);
                // } else {
                //     $this->line("image is fine :" . $dir . '/' . $template_image);
                // }
            } catch (\Exception $e) {
                $this->error($e->getMessage() . ' on line: ' . $e->getLine());
            }
        }
    }
}
