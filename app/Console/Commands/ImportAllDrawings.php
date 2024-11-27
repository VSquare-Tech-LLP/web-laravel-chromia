<?php

namespace App\Console\Commands;

use App\Domains\Flux\Models\Category;
use App\Domains\Flux\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportAllDrawings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-all-drawings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports drawing in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Load prompts from the CSV
        $this->loadPromptsFromCSV();

        $sourceDirectory = 'ALL_DRAWIG';
        $destinationDirectory = 'source_images';

        $files = $this->traverseDirectory($sourceDirectory);

        foreach ($files as $mainCategory => $images) {
            $this->line("Main Category: " . $mainCategory);

            try {
                // Create or retrieve the main category
                $dbCategory = Category::firstOrCreate(['name' => $mainCategory]);

                foreach ($images as $index => $image) {
                    $this->line("Processing Image: " . $image);

                    // Generate a unique filename for the image
                    $uniqueFilename = time() . '_' . str()->random(20) . '.' . pathinfo($image, PATHINFO_EXTENSION);

                    // Copy image to destination directory
                    $sourcePath = $sourceDirectory . '/' . $mainCategory . '/' . $image;
                    $destinationPath = $destinationDirectory . '/' . $uniqueFilename;

                    if (Storage::disk('public')->exists($sourcePath)) {
                        Storage::disk('public')->copy($sourcePath, $destinationPath);
                    } else {
                        $this->error("File not found: " . $sourcePath);
                        continue;
                    }

                    // Get the prompt for the current image
                    $prompt = $this->prompts[$mainCategory][$index] ?? 'No prompt available';

                    // Generate a public URL for the image
                    $url = asset(Storage::url($destinationPath));

                    // Create a photo entry in the database
                    Photo::create([
                        'name' => $uniqueFilename,
                        'category_id' => $dbCategory->id,
                        'prompt' => $prompt, // Assign the prompt
                        'url' => $url,
                    ]);

                    $this->line("Image Imported: " . $uniqueFilename . " with Prompt: " . $prompt);
                }
            } catch (\Exception $e) {
                $this->error("Error: " . $e->getMessage() . " on line: " . $e->getLine());
            }
        }
    }

    /**
     * Load prompts from CSV file.
     */
    private function loadPromptsFromCSV()
    {
        $csvPath = storage_path('app/ar-drawing-prompts.csv'); // Update the CSV file path
        if (!file_exists($csvPath)) {
            $this->error("CSV file not found: " . $csvPath);
            return;
        }

        $csvData = array_map('str_getcsv', file($csvPath));
        $headers = array_map('trim', $csvData[0]); // Get headers (categories)
        unset($csvData[0]); // Remove header row

        foreach ($headers as $headerIndex => $category) {
            $category = trim($category);
            if (empty($category)) {
                continue;
            }

            foreach ($csvData as $row) {
                $this->prompts[$category][] = $row[$headerIndex] ?? null;
            }
        }

        $this->info("Prompts loaded successfully.");
    }

    /**
     * Recursively traverse the directory to get files grouped by folder structure.
     */
    private function traverseDirectory($directory)
    {
        $categories = Storage::disk('public')->directories($directory);
        $files = [];

        foreach ($categories as $categoryDir) {
            $categoryName = $this->getDirectoryName($categoryDir);

            // Get files directly inside this category
            $categoryFiles = Storage::disk('public')->files($categoryDir);

            $files[$categoryName] = [];

            foreach ($categoryFiles as $file) {
                $files[$categoryName][] = $this->getFileName($file);
            }
        }

        return $files;
    }

    /**
     * Extract the directory name from the path.
     */
    private function getDirectoryName($path)
    {
        $parts = explode('/', $path);
        return end($parts);
    }

    /**
     * Extract the file name from the path.
     */
    private function getFileName($path)
    {
        $parts = explode('/', $path);
        return end($parts);
    }
}
