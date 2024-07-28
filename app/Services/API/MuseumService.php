<?php
namespace App\Services\API;

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

    public function getMuseum()
    {
        return $this->museumRepository->getApiMuseum();
    }

    public function getMuseumRegionListByIds($ids)
    {
        return Region::whereIn('id', $ids)->get()->pluck('name')->toArray();
    }

    public function getMuseumById($id)
    {
        return $this->museumRepository->getMuseumByLangAndId($id);
    }

    public function getMobileMuseumById($id)
    {
        return $this->museumRepository->getMobileMuseumById($id);
    }


}
