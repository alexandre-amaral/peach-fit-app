<?php

namespace App\Http\Controllers\General\city;

use App\Http\Controllers\Controller;
use App\Services\General\GetCitiesService;
use Illuminate\Http\Request;

class GetCitiesController extends Controller
{
    public function getCitiesByState(Request $request)
    {
        $return  = (new GetCitiesService())->getCitiesByState($request->state_id);
        return $return;
    }
}
