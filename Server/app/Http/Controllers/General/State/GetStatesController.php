<?php

namespace App\Http\Controllers\General\State;

use App\Http\Controllers\Controller;
use App\Services\General\GetStatesService;
use Illuminate\Http\Request;

class GetStatesController extends Controller
{
    public function getStates()
    {
        return (new GetStatesService())->getStates();
    }
}
