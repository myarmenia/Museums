<?php
namespace App\Services\Link;
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

}
