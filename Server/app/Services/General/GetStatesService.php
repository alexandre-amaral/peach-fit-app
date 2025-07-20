<?php

namespace App\Services\General;

use App\Models\IbgeState;

class GetStatesService
{
    public function getStates()
    {
        return IbgeState::all();
    }
}