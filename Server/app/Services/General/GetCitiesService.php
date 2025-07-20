<?php

namespace App\Services\General;

use App\Models\IbgeCity;

class GetCitiesService
{
    public function getCitiesByState($state)
    {
        $cities = IbgeCity::where("ibge_state_id",'=',$state)->get();
        return response()->json($cities);
    }
}