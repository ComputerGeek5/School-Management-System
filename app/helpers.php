<?php

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

if(!function_exists("image_string")) {
    function image_string(Request $request, string &$fileNameToStore)
    {
        // Handle image upload
        if ($request->hasFile("image")) {
            // Get full name
            $fileNameWithExt = $request->file("image")->getClientOriginalName();
            // Get only name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file("image")->getClientOriginalExtension();
            // File name to store
            $fileNameToStore = $fileName . "_" . time() . "." . $extension;
            // Upload image
            $request->file("image")->storeAs("public/images", $fileNameToStore);
        }
    }
}

if(!function_exists("image_create")) {
    function image_create(Request $request, Model $model) {
        $fileNameToStore = "noimage.jpg";

        image_string($request, $fileNameToStore);

        $model->image = $fileNameToStore;
    }
}

if(!function_exists("image_update")) {
    function image_update(Request $request, Model $model) {
        $fileNameToStore = $model->image;

        image_string($request, $fileNameToStore);

        // Update image if image selected
        if($request->hasFile("image")) {
            Storage::delete("public/images/".$model->image);
            $model->image = $fileNameToStore;
        }
    }
}

if(!function_exists("image_delete")) {
    function image_delete(Model $model) {
        // Delete image if default not selected
        if($model->image !== "noimage.jpg") {
            Storage::delete("public/images/".$model->image);
        }
    }
}
