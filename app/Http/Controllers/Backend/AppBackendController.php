<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Domains\Flux\Models\Category;
use App\Models\Pack;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class AppBackendController extends Controller
{
    public function showaCategories()
    {
        $categories = Category::all(); // Retrieve all categories

        return view('backend.photos.categories', compact('categories'));
    }

    public function editCategory(Category $category)
    {
        $categories = Category::all();
        return view('backend.photos.categories', compact('category', 'categories'));
    }

    public function storeCategory(Request $request)
    {

        $new_filename = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            //$fileInfo = pathinfo($file);
            //$extension = $fileInfo['extension'];
            
            $imagePath = storage_path("category");
            $extension = $file->getClientOriginalExtension();
            $new_filename = Str::random(10).".".$extension;
            $file->storeAs('category',$new_filename,'public');
            
        }

        if ($request->has('category_id')) {
            $validatedData = $request->validate([
                'name' => 'required|string|unique:categories,name,' . $request->category_id,
            ]);
            $category = Category::find($request->category_id);
            $description = $request->description;
            $featured = $request->featured;
            $validatedData['description'] = $description;
            $validatedData['featured'] = $featured;
            $category->update($validatedData);
            if ($request->hasFile('image')) {
                $category->image = $new_filename;
            }
            
            $category->save();
            return redirect()->back()->withType('success')->withMessage("Category updated successfully");
        }
        $validatedData = $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);

        $description = $request->description;
        $featured = $request->featured;
        $validatedData['description'] = $description;
        $validatedData['featured'] = $featured;
        $category = Category::create($validatedData);
        $category->image = $new_filename;
        $category->save();

        return redirect()->route('admin.categories.index')->withFlashSuccess("Category added successfully");
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->withFlashSuccess("Category deleted successfully");
    }

    public function storePack(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'feature_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Adjust max size as needed
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = $request->file('feature_image')->store('packs'); // Store the image in storage/app/packs directory
        $imageUrl = Storage::url($imagePath); // Get the URL of the stored image

        $pack = Pack::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'feature_image' => $imageUrl,
            'category_id' => $validatedData['category_id'],
        ]);

        return response()->json(['message' => 'Pack added successfully', 'pack' => $pack], 201);
    }
    public function storePhoto(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Adjust max size as needed
            'pack_id' => 'required|exists:packs,id',
        ]);

        // Store the image
        $imagePath = $request->file('image')->store('photos');

        // Get the URL of the stored image
        $imageUrl = Storage::url($imagePath);

        // Create the photo record in the database
        $photo = Photo::create([
            'name' => $validatedData['name'],
            'path' => $imagePath, // Store the image path
            'url' => $imageUrl, // Store the image URL
            'pack_id' => $validatedData['pack_id'],
        ]);

        return response()->json(['message' => 'Photo added successfully', 'photo' => $photo], 201);
    }

    public function swapLogs()
    {
        return view('backend.swaplogs.index');
    }


    public function deleteCat($catid=0)
    {
        DB::table("categories")->where('id',$catid)->delete();
        return redirect()->route('admin.categories.index')->withFlashSuccess("Category deleted successfully");
    }

}
