<?php
namespace App\Services\Museum;

use App\Models\Museum;
use App\Models\MuseumTranslation;
use App\Models\Region;
use App\Repositories\Museum\MuseumRepository;
use App\Services\Image\ImageService;
use App\Services\Link\LinkService;
use App\Services\Phone\PhoneService;
use Illuminate\Support\Facades\DB;

class MuseumService
{

    protected $museumRepository;

    public function __construct(MuseumRepository $museumRepository)
    {
        $this->museumRepository = $museumRepository;
    }

    public function addMuseum($data)
    {
        $museum = Museum::create($data);

        return $museum;
    }

    public function createMuseum($data)
    {
        try {
            DB::beginTransaction();
            $languages = languages();
            $museumTranslations = [];

            $regionId = Region::where('name', $data['region'])->first()->id;
            $museum = [
                'user_id' => auth()->id(),
                'museum_geographical_location_id' => $regionId,
                'email' => $data['email'],
                'account_number' => $data['account_number'],
            ];

            $getCreatedMuseum = $this->museumRepository->createMuseum($museum);
            $getCreatedMuseumId = $getCreatedMuseum->id;

            foreach ($languages as $key => $lang) {
                $museumTranslations[] = [
                    'museum_id' => $getCreatedMuseumId,
                    'lang' => $lang,
                    'name' => $data['name'][$lang],
                    'description' => $data['description'][$lang],
                    'working_days' => $data['work_days'][$lang],
                    'director_name' => $data['owner'][$lang],
                    'address' => $data['address'][$lang],
                ];
            }

            $museumTranslations = $this->museumRepository->createMuseumTranslations($museumTranslations);

            if (array_key_exists('photos', $data)) {
                $imagesData = [
                    'photos' => [
                        'image' => $data['photos'],
                    ],
                    'museum' => $getCreatedMuseum
                ];

                ImageService::createImageble($imagesData);
            }

            $imagesData = [
                'photos' => [
                    'image' => [$data['general_photo']],
                ],
                'museum' => $getCreatedMuseum
            ];

            ImageService::createImageble($imagesData, true);

            $createLinkData = [
                'link' => $data['link'],
                'museum' => $getCreatedMuseum
            ];

            LinkService::createLink($createLinkData);

            $phoneData = [
                'museum_id' => $getCreatedMuseumId,
                'phones' => $data['phones']
            ];

            PhoneService::createPhone($phoneData);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            return false;
        }

    }

    public function getMuseumByUd($id)
    {
        return $this->museumRepository->getMuseumByUd($id);

    }

    public function updateMuseum($data, $id)
    {

        // try {
        //     DB::beginTransaction();
        $languages = languages();

        $regionId = Region::where('name', $data['region'])->first()->id;

        $museum = [
            'museum_geographical_location_id' => $regionId,
            'email' => $data['email'],
            'account_number' => $data['account_number'],
        ];

        $getCreatedMuseum = $this->museumRepository->updateMuseum($museum, $id);

        // $getCreatedMuseumId = $getCreatedMuseum->id;

        foreach ($languages as $key => $lang) {
            $museumTranslations = [
                'name' => $data['name'][$lang],
                'description' => $data['description'][$lang],
                'working_days' => $data['work_days'][$lang],
                'director_name' => $data['owner'][$lang],
                'address' => $data['address'][$lang],
            ];

            $this->museumRepository->updateMuseumTranslations($museumTranslations, $lang, $id);
        }

        $createLinkData = [
            'link' => $data['link'],
        ];

        LinkService::updateLink($createLinkData, $id);

        $phoneData = [
            'phones' => $data['phones']
        ];

        PhoneService::updatePhone($phoneData, $id);

        if (array_key_exists('photos', $data) || array_key_exists('general_photo', $data)) {
            // dd($data);
            $imagesData = [
                'museum' => Museum::find($id)
            ];

            if (array_key_exists('photos', $data)) {
                $imagesData['photos'] = [
                    'image' => $data['photos'],
                ];
                ImageService::createImageble($imagesData);
            }

            if (array_key_exists('general_photo', $data)) {
                $imagesData['photos'] = [
                    'image' => [$data['general_photo']],
                ];
                ImageService::createImageble($imagesData, true);
            }
        }
        // DB::commit();

        return true;
        // } catch (\Exception $e) {
        //     session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        //     DB::rollBack();
        //     return false;
        // } catch (\Error $e) {
        //     session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        //     DB::rollBack();
        //     return false;
        // }
    }

    public function getMuseumByAuthUser()
    {
        return Museum::where('user_id', auth()->id())->first()->id;
    }



}
