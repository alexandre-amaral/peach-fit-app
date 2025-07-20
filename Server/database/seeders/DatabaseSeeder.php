<?php

namespace Database\Seeders;

use App\Models\IbgeCity;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\IbgeState;
use App\Models\User;
use Illuminate\Database\Seeder;
use NFePHP\Ibge\Ibge;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (User::where('email', 'betofreitas16@gmail.com')->first() == null) {
            User::factory()->create([
                'name' => 'Egberto Carvalho',
                'email' => 'betofreitas16@gmail.com',
                'password' => 'asx123',
                'role' => 'admin',
            ]);
        }


        if (count(IbgeState::get()) == 0) {
            $ibge = new Ibge();

            $states = json_decode($ibge->estados()->refresh()->get());

            foreach ($states as $state) {
                IbgeState::factory()->create([
                    'ibge_id' => $state->id,
                    'uf' => $state->sigla,
                    'name' => $state->nome
                ]);

                $cities = json_decode($ibge->cidades($state->id)->get());

                foreach ($cities as $city) {

                    IbgeCity::factory()->create([
                        'ibge_state_id' => $state->id,
                        'ibge_id' => $city->id,
                        'name' => $city->nome,
                    ]);
                }
            }
        }
    }
}
