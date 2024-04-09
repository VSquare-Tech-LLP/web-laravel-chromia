<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\Traversable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class CompressImages extends Command
{
    use Traversable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:compress-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dir = 'public/template_images';
        $files = Storage::files($dir);
        $manager = new ImageManager(new Driver());
        foreach ($files as $template_image) {
            $scaledPath = (Storage::path($dir) . $this->getEndName($template_image));
            try {
                //$imagefile = Storage::get($template_image);
                [$width, $height] = getimagesize(Storage::path($template_image));
                if ($width > 1024 || $height > 1024) {
                    $image = $manager->read(Storage::path($template_image));
                    $image->scale(width: 1024);
                    $image->toPng()->save($scaledPath);
                    $this->line("image is resized : " . $scaledPath);
                    //$image->place('images/watermark.png');
                    // $img->resize(1024, null, function ($constraint) {
                    //     $constraint->aspectRatio();
                    // });
                    // $img->stream($request->image->extension());
                    // $file = $filePath . "/" . Str::uuid() . '.' . $request->image->extension();
                    // Storage::disk('public')->put($file, $img);
                } else {
                    $this->line("image is fine :" . $dir . '/' . $template_image);
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage() . ' on line: ' . $e->getLine());
            }
        }
    }
}
