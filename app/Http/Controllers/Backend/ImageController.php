<?php

//namespace App\Http\Controllers;
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

//use App\Models\Meme;
use Illuminate\Support\Facades\DB;
use App\Domains\Auth\Models\Image;
use App\Domains\Auth\Models\Pack;
use App\Domains\Flux\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //dd(public_path('storage/meme_images'));
        
        return view('backend.images.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categorylist = Category::all();
        return view('backend.images.create',['categorylist'=>$categorylist]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'category_id' => 'required|numeric',
            //'image' => 'required'
        ]);

        $category_id = $request->category_id;

        /* if (!file_exists(public_path("storage/category_images/$pack_id"))) {
            mkdir(public_path("storage/category_images/$pack_id"), 0777);
        } */

        if($request->hasFile('image_bulk')) {
            $image_rows = [];
            $newfilename = "";
            $bulkImages = $request->file('image_bulk');

            foreach($bulkImages as $memImage){
                
                $thumbfile = $memImage;

                $randomString = Str::random(12);
                $extension = $thumbfile->getClientOriginalExtension();
                $newfilename = $randomString.".".$extension;
                $thumbfile->storeAs("public/source_images",$newfilename);
                $thumburl = url("storage/source_images/$newfilename");

                $image_rows[] = array('category_id'=>$category_id,'name'=>$newfilename,'url'=>$thumburl);
                
            }
            
            if(count($image_rows) > 0){
                DB::table("photos")->insert($image_rows);
            }

            return redirect()->route('admin.images.index')->withFlashSuccess(__('Category Images successfully uploaded.'));
            
        }

        $newfilename = "";
        $thumburl = "";
        if($request->hasFile('image')) {
            $thumbfile = $request->file('image');

            $randomString = Str::random(12);
            $extension = $thumbfile->getClientOriginalExtension();
            $newfilename = $randomString.".".$extension;
         
            $thumbfile->storeAs("public/source_images",$newfilename);
            $thumburl = url("storage/source_images/$newfilename");
            
        }

        $validatedData['name'] = $newfilename;
        $validatedData['url'] = $thumburl;
        $newImage = Image::create($validatedData);

        // Get the last inserted ID
        $lastInsertId = $newImage->id;
        

        return redirect()->route('admin.images.index')->withFlashSuccess(__('Category Image is successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
        //dd("here",$image);
        $categorylist = Category::all();
        return view('backend.images.edit',['image'=>$image,'categorylist'=>$categorylist]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
        $validatedData = $request->validate([
            'category_id' => 'required',
            //'image' => 'required',
            
        ]);
        //dd($image);
        //$image_id = $request->image_id;
        //$imageMode = $request->mode;

        $newfilename = $request->old_thumb;
        if($request->hasFile('image')) {
            $thumbfile = $request->file('image');

            $randomString = Str::random(12);
            $extension = $thumbfile->getClientOriginalExtension();
            $newfilename = $randomString.".".$extension;

            $thumbfile->storeAs("public/source_images",$newfilename);
            $thumburl = url("storage/source_images/$newfilename");

            $validatedData['name'] = $newfilename;
            $validatedData['url'] = $thumburl;

            /* $old_filename = $request->old_thumb;
            $meme_img_path = storage_path("app/public/source_images/$old_filename");
            if(trim($old_filename) != '' && file_exists($meme_img_path)){
                unlink($meme_img_path);
            } */
        }
        
        
        $image->update($validatedData);

        return redirect()->route('admin.images.index')->withFlashSuccess(__('Image is successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
        $thubmnail = $image->image;
        if($thubmnail != null && $thubmnail != ""){
            $thumbnail_path = storage_path("app/public/source_images/$thubmnail");
            //dd($user_photo_path);
            if(file_exists($thumbnail_path)){
               unlink($thumbnail_path);
            }
        }
       
        $image->delete();
        return redirect()->route('admin.images.index')->withFlashSuccess(__('Category Image is successfully deleted.'));
    }
}
