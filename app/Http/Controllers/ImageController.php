<?php

namespace App\Http\Controllers;

use App\Models\ImageModel;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function create()
    {
        $image = ImageModel::latest()->first();
        return view('createimage', compact('image'));
    }

    public function store(Request $request)
    {         
        $this->validate($request, [
            'filename' =>'required|mimes:csv',
         ]);

        $csvFile =$request->file("filename");
        $infos=$this->readCSV($csvFile,array('delimiter' => ','));
        $header=$infos[0][1];
        $gun=$infos[1][1];
        $ay=$infos[2][1];
        $yil=$infos[3][1];

        $originalImage= Image::make(public_path().'/image/template2.jpg');
        $outputImage = Image::make($originalImage);
        $outputPath = public_path().'/output_images/';
        
        $outputImage->text($header, 960, 65, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(110);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($gun, 225, 420, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(200);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($ay, 225, 580, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(70);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($yil, 225, 660, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(150);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $y=0;
        $i=4;

        for($i;$i<count($infos);$i++){
            if($infos[$i][0]=="TITLE2"){
                $outputImage->text($infos[$i][1], 1210, 240, function($font) {
                    $font->file(public_path().'/text/text.ttf');
                    $font->size(50);
                    $font->color('#000000');
                    $font->valign('center');
                });
                $y=0;
                $i+=1;
                for($i;$i<count($infos);$i++){
                    $outputImage->text($infos[$i][0], 1210, 320+$y, function($font) {
                        $font->file(public_path().'/text/text.ttf');
                        $font->size(34);
                        $font->color('#ffffff');
                        $font->valign('top');
                    });
        
                    $outputImage->text($infos[$i][1], 1740, 325+$y, function($font) {
                        $font->file(public_path().'/text/text.ttf');
                        $font->size(32);
                        $font->color('#ffffff');
                        $font->align('right');
                        $font->valign('top');
                    });
                    $y+=57;
                }
                break;
            }
            if($infos[$i][0]=="TITLE1"){
                $outputImage->text($infos[$i][1], 530, 235, function($font) {
                    $font->file(public_path().'/text/text.ttf');
                    $font->size(50);
                    $font->color('#000000');
                    $font->valign('center');
                });
                continue;
            }
            $outputImage->text($infos[$i][0], 530, 320+$y, function($font) {
                $font->file(public_path().'/text/text.ttf');
                $font->size(34);
                $font->color('#ffffff');
                $font->valign('top');
            });

            $outputImage->text($infos[$i][1], 1060, 325+$y, function($font) {
                $font->file(public_path().'/text/text.ttf');
                $font->size(32);
                $font->color('#ffffff');
                $font->align('right');
                $font->valign('top');
            });

            $y+=57;
        }

        
        $outputImage->save($outputPath.time().".jpg"); 

        $imagemodel= new ImageModel();
        $imagemodel->filename=time().".jpg";
        $imagemodel->save();
        $image = ImageModel::latest()->first();
        return view('outputimage', compact('image'));

    }

    public function readCSV($csvFile, $array)
    {
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }
}
