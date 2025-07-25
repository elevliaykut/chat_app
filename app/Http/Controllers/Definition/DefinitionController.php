<?php

namespace App\Http\Controllers\Definition;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\Definitions\CityResource;
use App\Http\Resources\Definitions\DistrictResource;
use App\Models\Definitions\City;
use App\Models\Definitions\District;

class DefinitionController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function cities()
    {
        $cities = City::all();
        return API::success()->response(CityResource::collection($cities));
    }

    /**
     * Undocumented function
     *
     * @param integer $cityId
     * @return void
     */
    public function districts(int $cityId)
    {
        $districts = District::where('city_id', $cityId)->get();

        return API::success()->response(DistrictResource::collection($districts));
    }
}
