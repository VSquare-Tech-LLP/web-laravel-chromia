<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Pack;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadSourceImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-source-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uploads Images from source to db';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $directory = 'source_images';
        $files = $this->traverseDirectory($directory);
        foreach ($files as $main_category => $categories) {
            $this->line("Main Category: " . $main_category);
            try {
                foreach ($categories as $category => $images) {
                    $this->line("Category: " . $category);
                    if (!is_array($images)) {
                        continue;
                    }
                    foreach ($images as $image) {
                        $this->line("Image: " . $image);
                        //Storage::put('template_images/' . $key . '/' . $value, Storage::get($directory . '/' . $key . '/' . $value));
                        $db_category = Category::firstOrCreate(['name' => $main_category]);
                        $uniqueFilename = time() . '_' . str()->random(20) . '.' . pathinfo($image, PATHINFO_EXTENSION);
                        $db_pack = Pack::firstOrCreate(
                            [
                                'name' => $category,
                                'category_id' => $db_category->id
                            ],
                            [
                                'fetured_image' => asset(Storage::url('template_images/' . $uniqueFilename))
                            ]
                        );
                        $file = Storage::get($directory . '/' . $main_category . '/' . $category . '/' . $image);

                        $copiedPath = 'public/template_images/' . $uniqueFilename;
                        $this->line('source_images/' . $directory . '/' . $main_category . '/' . $category . '/' . $image . " - to - " . $copiedPath);
                        Storage::copy($directory . '/' . $main_category . '/' . $category . '/' . $image, $copiedPath);

                        // Generate URL
                        $url = asset('storage/template_images/' . $uniqueFilename);
                        // Storage::putFile('template_images/' . $uniqueFilename, Storage::get($directory . '/' . $main_category . '/' . $category . '/' . $image));
                        $db_pack->photos()->create(['name' => $uniqueFilename, 'path' => $copiedPath, 'url' => $url]);
                        $this->line("Image Uploaded: " . $image);
                    }
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage() . ' on line: ' . $e->getLine());
            }
        }
    }

    private function traverseDirectory($directory)
    {
        $directories = Storage::directories($directory);
        $files = [];

        foreach ($directories as $dir) {
            $nestedDirName = $this->getDirectoryName($dir);
            $files[$nestedDirName] = $this->traverseDirectory($dir);
            $nestedFiles = Storage::files($dir);
            foreach ($nestedFiles as $file) {
                $files[$nestedDirName][] = $this->getFileName($file);
            }
        }

        return $files;
    }

    private function getDirectoryName($path)
    {
        $parts = explode('/', $path);
        return end($parts);
    }

    private function getFileName($path)
    {
        $parts = explode('/', $path);
        return end($parts);
    }
}
