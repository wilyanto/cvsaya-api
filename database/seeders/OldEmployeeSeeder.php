<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class OldEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $users = User::whereRaw('LENGTH(telpon) > 7')->get();
            Log::info('Kustomer : ' . count($users));
            $administrators = DB::connection('cvsaya')->table('administrator')->get();
            Log::info('Administrator : ' . count($administrators));
            $userPhoneNumbers = $users->pluck('telpon');
            $administratorPhoneNumbers = $administrators->pluck('no_telp');
            $intersectedAdministrators = array_intersect($administratorPhoneNumbers->toArray(), $userPhoneNumbers->toArray());

            $filteredUsers = $users->whereIn('telpon', $intersectedAdministrators);
            $filteredAdministrators = $administrators->whereIn('no_telp', $intersectedAdministrators);

            $employees = [];
            foreach ($filteredUsers as $index => $user) {
                $administrator = $filteredAdministrators->where('no_telp', $user->telpon)->first();
                if ($administrator) {
                    $isDeleted = null;
                    if ($administrator->blokir == 'Y') {
                        $isDeleted = now();
                    }
                    $employees[] = [
                        'user_id' => $user->id_kustomer,
                        'position_id' => null,
                        'type' => 'daily',
                        'is_default' => true,
                        'joined_at' => null,
                        'created_at' => $administrator->TglPost ?? now(),
                        'updated_at' =>  $administrator->TglPost ?? now(),
                        'deleted_at' => $isDeleted,

                    ];
                }
                Log::info('Index ke: ' . $index);
            }

            Employee::insert($employees);
        } catch (Exception $e) {
            Log::info('Exception : ' . $e);
        }
    }
}
