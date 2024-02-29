<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Museum\MuseumIdResource;
use App\Http\Resources\API\Museum\MuseumsResource;
use App\Services\API\MuseumService;
use Illuminate\Http\Request;

class MuseumController extends Controller
{

    protected $museumService;

    public function __construct(MuseumService $museumService)
    {
        $this->museumService = $museumService;
    }

    public function getMuseum()
    {
        $museums = $this->museumService->getMuseum();
        $museumsRegionId = $museums->pluck('museum_geographical_location_id')->toArray();
        $museumsRegionListByIds = $this->museumService->getMuseumRegionListByIds($museumsRegionId);

        $data = [
            'museums' => $museums,
            'regions' => $museumsRegionListByIds,
        ];

        return new MuseumsResource($data);
    }

    public function getMuseumById($id)
    {
        $museum = $this->museumService->getMuseumById($id);

        return new MuseumIdResource($museum);
    }
}
