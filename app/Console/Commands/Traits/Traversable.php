<?php

namespace App\Console\Commands\Traits;

use Illuminate\Support\Facades\Storage;

trait Traversable
{

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

  public function getEndName($path)
  {
    $parts = explode('/', $path);
    return end($parts);
  }
}
