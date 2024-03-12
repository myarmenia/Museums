<?php
namespace App\Services\Link;
use App\Models\Link;
use App\Models\Museum;
use App\Services\FileUploadService;


class LinkService
{
    public static function createLink($data)
    {
        $links = $data['link'];
        $museum = $data['museum'];
        
        foreach ($links as $key => $link) {
            if($link){
                $readyLink = [
                    'link' => $link,
                    'name' => $key,
                ];  

                $museum->links()->create($readyLink);
            }
        }
        return;

    }

    public static function updateLink($data, $id)
    {
        $links = $data['link'];

        $museum = Museum::find($id);
        
        foreach ($links as $key => $link) {

            $readyLink = [
                'link' => $link ?? '',
            ];
              
            $museum->links()->where('name', $key)->update($readyLink);

        }
        return;

    }


    

}
