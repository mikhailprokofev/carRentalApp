<?php

declare(strict_types=1);

namespace App\Http\Controllers\FileSystem;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileSystem\ImportCarRequest;
use Illuminate\Http\Response;

final class ImportController extends Controller
{
    // di how container
//    public function __construct(
//        private string $fileDirectory,
//    ) {}

    /**
     * @param ImportCarRequest $request
     * @return Response
     */
    public function importCars(ImportCarRequest $request): Response
    {
        $request->validate($request->rules());

        $request->file('file');
        if ($file = $request->file('file')) {
            dd($file);
//            $fileModel = File::get($this->fileDirectory);
//            $fileModel->name = ;
        }

        // validate
//        $fileModel = new File;
//        if($req->file()) {
//            $fileName = time().'_'.$req->file->getClientOriginalName();
//            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');
//            $fileModel->name = time().'_'.$req->file->getClientOriginalName();
//            $fileModel->file_path = '/storage/' . $filePath;
//            $fileModel->save();
//            return back()
//                ->with('success','File has been uploaded.')
//                ->with('file', $fileName);
//        }
    }
}
