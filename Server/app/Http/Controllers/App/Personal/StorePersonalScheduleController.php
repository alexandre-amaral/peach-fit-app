<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PersonalSchedule;

class StorePersonalScheduleController extends Controller
{
    /**
     * Armazena horários na agenda do personal trainer
     * @return jsonResponse
     */

   public function store(Request $request)
    {
        $data = $request->validate([
            'personal_id' => 'required|exists:personal_trainers,id',
            'datetimes' => 'required|array',
            'datetimes.*' => 'date_format:Y-m-d H:i:s',
        ]);

        $schedules = collect($data['datetimes'])->map(function ($datetime) use ($data) {
            return [
                'personal_id' => $data['personal_id'],
                'datetime' => $datetime,
                'status' => 'free',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();
        
        PersonalSchedule::insertOrIgnore($schedules);

        return response()->json(['message' => 'Horarios inseridos com sucesso.']);
    }
}
