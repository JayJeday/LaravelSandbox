<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ItsIrv\ImageUploader\imageuploader as ImageUploader;
use ItsIrv\ImageUploader\Image;

class ImageController extends Controller
{

    public function index()
    {
        return view('imageloader::images');
    }

    public function upload(Request $request, ImageUploader $imageUploader)
    {
        $image = $imageUploader->getImageOrFail('image');

        $variations = $image->createSizeVariations(
            [100, 100], // thumbnail,
            [400, 400], // small
            [$image->width(), $image->height()], // full
        );

        // We want batches to start with the same base name.
        $baseName = $variations[0]->generateRandomName();
        $baseName = str_replace('.png', '', $baseName);

        $imagesCreated = collect();

        foreach ($variations as $image) {
            $storageObject = $imageUploader->uploadImageToDrive(
                $image,
                $image->nameWithSizeAppended($baseName)
            );

            if ($storageObject) {
                $image->name = $storageObject->name();
                $image->bucket = $storageObject->identity()['bucket'];

                $image->save();

                $imagesCreated->push($image);
            }
        }
        return response()->json([$imagesCreated->toArray()]);
    }

    public function GetAll()
    {
        $images = Image::all();
        return response()->json($images);
    }
}
