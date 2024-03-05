<?php
namespace App\Services\Museum;

use App\Models\Museum;
use App\Models\MuseumStaff;
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

        if(array_key_exists('photos', $data) && count($data['photos']) > 3) {
            session(['errorMessage' => 'Նկարների քանակը չպետք է գերազանցի 3ը']);
            return redirect()->back();
        }

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

            MuseumStaff::where('admin_id', auth()->id())->update(['museum_id' => $getCreatedMuseumId]);
            session(['success' => 'Թանգարանը հաջողությամբ ավելացված է']);

            DB::commit();

            return $getCreatedMuseumId;
        } catch (\Exception $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            dd($e->getMessage());
            return false;
        } catch (\Error $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }

    }

    public function getMuseumByUd($id)
    {
        return $this->museumRepository->getMuseumByUd($id);

    }

    public function updateMuseum($data, $id)
    {

        if(array_key_exists('photos', $data) && !$check = $this->checkMuseumPhotoCount($id, count($data['photos']), 3)) {
            if(!$check) {
                session(['errorMessage' => 'Նկարների քանակը չպետք է գերազանցի 3ը']);
                return redirect()->back();
            }
        }

        try {
            DB::beginTransaction();
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
        session(['success' => 'Թանգարանը հաջողությամբ փոփոխված է']);

        DB::commit();

        return true;
        } catch (\Exception $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            dd($e->getMessage());
            return false;
        } catch (\Error $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function getMuseumByAuthUser()
    {
        return Museum::where('user_id', auth()->id())->first()->id;
    }

    public function checkMuseumPhotoCount($id, $count, $countMax)
    {
        if($count > $countMax) {
            return false;
        }

        $museum = Museum::find($id);

        if((count($museum->images->where('main', false)) + $count > $countMax)) {
            return false;
        }

        return true;

    }



}
