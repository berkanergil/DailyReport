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
        $header2=$infos[4][1];
        $nisan=$infos[5][0];
        $mayıs=$infos[6][0];
        $mayıs_be=$infos[7][0];
        $nisan_value=$infos[5][1];
        $mayıs_value=$infos[6][1];
        $mayıs_be_value=$infos[7][1];

        $originalImage= Image::make(public_path().'/image/template9.jpg');
        $outputImage = Image::make($originalImage);
        $outputPath = public_path().'/output_images/';
        
        $outputImage->text($header, 250, 300, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(75);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($gun, 240, 420, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(200);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($ay, 235, 580, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(70);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($yil, 235, 660, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(150);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($header2,240, 820, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(50);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($nisan, 100, 900, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(30);
            $font->color('#ffffff');
            $font->valign('center');
        });

        $outputImage->text($mayıs, 100, 960, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(30);
            $font->color('#ffffff');
            $font->valign('center');
        });

        $outputImage->text($mayıs_be, 100, 1020, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(30);
            $font->color('#ffffff');
            $font->valign('center');
        });

        $outputImage->text($nisan_value, 360, 900, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(30);
            $font->color('#ffffff');
            $font->valign('center');
        });

        $outputImage->text($mayıs_value, 360, 960, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(30);
            $font->color('#ffffff');
            $font->valign('center');
        });

        $outputImage->text($mayıs_be_value, 360, 1020, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(30);
            $font->color('#ffffff');
            $font->valign('center');
        });

       /*  $outputImage->text($mayıs, 225, 820, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(150);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });

        $outputImage->text($mayıs_be, 225, 900, function($font) {
            $font->file(public_path().'/text/text.ttf');
            $font->size(150);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        }); */

        $y=0;
        $i=8;

        for($i;$i<count($infos);$i++){
            if($infos[$i][0]=="TITLE2"){
                $outputImage->text($infos[$i][1], 1380, 60, function($font) {
                    $font->file(public_path().'/text/text.ttf');
                    $font->size(50);
                    $font->color('#000000');
                    $font->valign('center');
                });
                $y=0;
                $i+=1;
                for($i;$i<count($infos);$i++){
                    $outputImage->text($infos[$i][0], 1200, 100+$y, function($font) {
                        $font->file(public_path().'/text/text.ttf');
                        $font->size(25);
                        $font->color('#ffffff');
                        $font->valign('top');
                    });
        
                    $outputImage->text($infos[$i][1], 1810, 105+$y, function($font) {
                        $font->file(public_path().'/text/text.ttf');
                        $font->size(24);
                        $font->color('#ffffff');
                        $font->align('right');
                        $font->valign('top');
                    });
                    $y+=31.5;
                }
                break;
            }
            if($infos[$i][0]=="TITLE1"){
                $outputImage->text($infos[$i][1], 700, 60, function($font) {
                    $font->file(public_path().'/text/text.ttf');
                    $font->size(50);
                    $font->color('#000000');
                    $font->valign('center');
                });
                continue;
            }
            $outputImage->text($infos[$i][0], 520, 100+$y, function($font) {
                $font->file(public_path().'/text/text.ttf');
                $font->size(25);
                $font->color('#ffffff');
                $font->valign('top');
            });

            $outputImage->text($infos[$i][1], 1130, 105+$y, function($font) {
                $font->file(public_path().'/text/text.ttf');
                $font->size(24);
                $font->color('#ffffff');
                $font->align('right');
                $font->valign('top');
            });

            $y+=31.5;
        }

        
        $outputImage->save($outputPath.$gun."_".$ay."_".$yil.".jpg"); 

        $imagemodel= new ImageModel();
        $imagemodel->filename=$gun."_".$ay."_".$yil.".jpg";
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


