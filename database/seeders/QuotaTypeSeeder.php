<?php

namespace Database\Seeders;

use App\Enums\QuotaTypeEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuotaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quota_types')->insert([
            [
                'name' => QuotaTypeEnum::daily(),
                'priority' => 1,
                'start_time' => '00:00:00',
                'end_time' => '23:59:59'
            ],
            [
                'name' => QuotaTypeEnum::weekly(),
                'priority' => 2,
                'start_time' => '00:00:00',
                'end_time' => '23:59:59'
            ],
            [
                'name' => QuotaTypeEnum::monthly(),
                'priority' => 3,
                'start_time' => '00:00:00',
                'end_time' => '23:59:59'
            ],
            [
                'name' => QuotaTypeEnum::weekday(),
                'priority' => 4,
                'start_time' => '00:00:00',
                'end_time' => '23:59:59'
            ],
            [
                'name' => QuotaTypeEnum::weekend(),
                'priority' => 5,
                'start_time' => '00:00:00',
                'end_time' => '23:59:59'
            ],
            [
                'name' => QuotaTypeEnum::morning(),
                'priority' => 6,
                'start_time' => '07:00:00',
                'end_time' => '11:59:59'
            ],
            [
                'name' => QuotaTypeEnum::afternoon(),
                'priority' => 7,
                'start_time' => '12:00:00',
                'end_time' => '16:59:59'
            ],
            [
                'name' => QuotaTypeEnum::evening(),
                'priority' => 8,
                'start_time' => '17:00:00',
                'end_time' => '23:59:59'
            ],
            [
                'name' => QuotaTypeEnum::midnight(),
                'priority' => 9,
                'start_time' => '00:00:00',
                'end_time' => '06:59:59'
            ],
            [
                'name' => QuotaTypeEnum::officeHour(),
                'priority' => 10,
                'start_time' => '08:00:00',
                'end_time' => '17:00:00'
            ],
        ]);
    }
}
