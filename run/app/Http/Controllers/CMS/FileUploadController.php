<?php

namespace App\Http\Controllers\cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class FileUploadController extends Controller
{


    public function __construct()
    {
        Image::configure(array('driver' => 'imagick'));
    }

    public function file(Request $request)
    {
        $uploadedFile = $request->file('files');

        $ymd = date('Ymd');
        $folder = 'files/download/' . $ymd;
//      判断文件夹是否已存在
        if (!Storage::disk('public')->has($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        //判断文件是否有效
        if ($uploadedFile->isValid()) {
//            $filename = time() . $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $filename = time() . chr(rand(65, 90)) . chr(rand(65, 90)) . preg_replace("/(\p{Z}|\p{P}|\p{Han}|\p{Katakana}|\p{Hiragana})+/u", '', str_replace($extension, '', $uploadedFile->getClientOriginalName())) . '.' . $extension;

            Storage::disk('local')->putFileAs(
                'public/' . $folder . '/',
                $uploadedFile,
                $filename
            );

//            return response()->json([
//                'files' => 'storage/' . $folder . '/' . $filename,
//                'url' => asset('storage/' . $folder . '/' . $filename)
//            ]);
            return response()->json([
                'filename' => $filename,
                'file_path' => $folder . '/',
                'url' => $folder . '/' . $filename
            ]);
        }


    }

    public function image(Request $request)
    {
        request()->validate([
            'files' => 'required|image|mimes:jpeg,png,jpg,gif,png,svg|max:2048',
        ]);

        $max_width = $request->input('max_width');
        $max_height = $request->input('max_height');
        $ymd = date('Ymd');
        $originalPath = 'files/images/' . $ymd;
        $thumbnailPath = 'files/images/thumbnail/' . $ymd;
        $filename = '';
        //判断文件夹是否已存在
        if (!Storage::disk('public')->has($originalPath)) {
            Storage::disk('public')->makeDirectory($originalPath);
        }
        if (!Storage::disk('public')->has($thumbnailPath)) {
            Storage::disk('public')->makeDirectory($thumbnailPath);
        }
        if ($files = $request->file('files')) {

            // for save original image
            $ImageUpload = Image::make($files);
            $extension = $files->getClientOriginalExtension();
            $filename = time() . chr(rand(65, 90)) . chr(rand(65, 90)) . preg_replace("/(\p{Z}|\p{P}|\p{Han}|\p{Katakana}|\p{Hiragana})+/u", '', str_replace($extension, '', $files->getClientOriginalName())) . '.' . $extension;
            if ($extension == 'gif') {
                copy($files->getRealPath(), storage_path('app/public/' . $originalPath . '/' . $filename));
            } else {
                $ImageUpload->save(storage_path('app/public/' . $originalPath . '/' . $filename), 90, $extension);
            }
            // for save thumnail image
            $width = $max_width; // your max width
            $height = $max_height; // your max height
            if ($max_height > 0) {
                $ImageUpload->height() > $ImageUpload->width() ? $width = null : $height = null;
                $ImageUpload->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $ImageUpload->resizeCanvas($max_width, $max_height, 'center', false, '#ffffff');
            } else {
                $ImageUpload->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if ($extension == 'gif') {
                copy($files->getRealPath(), storage_path('app/public/' . $thumbnailPath . '/' . $filename));
            } else {
                $ImageUpload->save(storage_path('app/public/' . $thumbnailPath . '/' . $filename), 90, $extension);
            }

        }

        return response()->json([
            'filename' => $filename,
            'file_path' => 'storage/' . $originalPath . '/',
            'url' => 'storage/' . $originalPath . '/' . $filename
        ]);


    }

}
