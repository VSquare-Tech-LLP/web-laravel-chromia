<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\Traversable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Illuminate\Support\Facades\DB;

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
        //$dir = 'public/template_images';
        $dir = 'public/source_images';
        $files = Storage::files($dir);
        $manager = new ImageManager(new Driver());
        foreach ($files as $template_image) {
            $this->line($template_image);
            $thumbDir = 'thumbnails';
            $pathInfo =  pathinfo($this->getEndName($template_image));
            $new_filename = $pathInfo['filename'];
            $org_filename = basename($this->getEndName($template_image));
            $extension = $pathInfo['extension'];
            $thumb_name='';
            if(in_array($extension,['jpg','png','jpeg'])){
                //dd($new_filename,$org_filename,$extension);
                $thumb_name = $new_filename.'_400x400.'.$extension;
            }
            
            //$scaledPath = (Storage::path($dir) . '/' . $thumbDir . '/' . $this->getEndName($template_image));
            $scaledPath = (Storage::path($dir) . '/' . $thumbDir . '/' . $thumb_name);
            
            if (!Storage::exists($dir . '/' . $thumbDir)) {
                Storage::makeDirectory($dir . '/' . $thumbDir);
            }
            try {
                //$imagefile = Storage::get($template_image);
                [$width, $height] = getimagesize(Storage::path($template_image));
                //if ($width > 1024 || $height > 1024) {
                $image = $manager->read(Storage::path($template_image));
                //$image->scale(width: 1024);
                $image->scale(width: 400);
                $image->scale(height: 400);
                $image->encode(new JpegEncoder(quality: 60))->save($scaledPath);
                $this->line("image is compressed : " . $scaledPath);
                if(in_array($extension,['jpg','png','jpeg'])){
                    DB::table("photos")->where('name',$org_filename)->update(['thumbnail'=>$thumb_name]);
                }
                
                
                // } else {
                //     $this->line("image is fine :" . $dir . '/' . $template_image);
                // }
            } catch (\Exception $e) {
                $this->error($e->getMessage() . ' on line: ' . $e->getLine());
            }
        }
    }
}
