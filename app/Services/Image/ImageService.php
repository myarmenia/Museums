<?php
namespace App\Services\Image;
use App\Services\FileUploadService;


class ImageService
{
    public static function createImageble($data)
    {

        $mainPhotoName = $data['photos']['mainPhoto'];
        $photos = $data['photos']['image'];
        $museum = $data['museum'];

        
        foreach ($photos as $key => $photo) {
            
                $path = FileUploadService::upload($photo, 'images');
     
                $readyPhoto = [
                    'path' => $path,
                    'name' => $photo->getClientOriginalName(),
                    'main' => $photo->getClientOriginalName() == $mainPhotoName? true : false
                ];  

                $museum->images()->create($readyPhoto);

        }
        
    }

}
