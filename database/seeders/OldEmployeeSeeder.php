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
            $employees = [];
            foreach ($users as $index => $user) {
                $administrator = $administrators->where('no_telp', $user->telpon)->first();
                if ($administrator) {
                    $isDeleted = null;
                    if ($administrator->blokir == 'Y') {
                        $isDeleted = date('Y-m-d\TH:i:s.v\Z', time());
                    }
                    $employees[] = [
                        'user_id' => $user->id_kustomer,
                        'position_id' => null,
                        'salary' => 0,
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
