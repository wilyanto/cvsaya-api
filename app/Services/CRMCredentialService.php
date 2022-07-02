<?php

namespace App\Services;

use App\Http\Common\Filter\FilterCredentialSearch;
use App\Http\Common\Sort\LastMessageCredentialSort;
use App\Http\Common\Sort\MessageCountCredentialSort;
use App\Models\CRMCredential;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CRMCredentialService
{
    protected $CRMCredentialQuotaTypeService;

    public function __construct(
        CRMCredentialQuotaTypeService $CRMCredentialQuotaTypeService
    ) {
        $this->CRMCredentialQuotaTypeService = $CRMCredentialQuotaTypeService;
    }

    public function getAll($size)
    {
        $credentials = QueryBuilder::for(CRMCredential::class)
            ->allowedSorts([
                AllowedSort::custom(
                    'last-message',
                    new LastMessageCredentialSort()
                ),
                AllowedSort::custom(
                    'message-count',
                    new MessageCountCredentialSort()
                )
            ])
            ->allowedFilters(
                [
                    AllowedFilter::custom('search', new FilterCredentialSearch),
                    AllowedFilter::exact('is_active'),
                ]
            )
            ->paginate($size);

        return $credentials;
    }

    public function getById($id)
    {
        $query = CRMCredential::where('id', $id);
        $CRMCredential = QueryBuilder::for($query)
            ->allowedIncludes([
                'blastTypes',
                'quotas.quotaType',
                'blastLogs',
                'recentMessages'
            ])
            ->firstOrFail();

        return $CRMCredential;
    }

    public function createCredential($data)
    {
        $CRMCredential = CRMCredential::create([
            'name' => $data->name,
            'key' => $data->key,
            'country_code' => $data->country_code,
            'phone_number' => $data->phone_number,
            'is_active' => $data->is_active,
            'expired_at' => $data->expired_at
        ]);

        // assign quotas
        $this->CRMCredentialQuotaTypeService->syncCredentialQuotaType($CRMCredential->id, $data->key);

        return $CRMCredential;
    }

    public function updateCredential($data, $id)
    {
        $CRMCredential = $this->getById($id);
        $CRMCredential->update([
            'name' => $data->name,
            'is_active' => $data->is_active,
            'expired_at' => $data->expired_at
        ]);

        return $CRMCredential;
    }

    public function updateCredentialStatus($isActive, $id)
    {
        $CRMCredential = $this->getById($id);
        $CRMCredential->update([
            'is_active' => $isActive
        ]);

        return $CRMCredential;
    }

    public function syncCredential($credential)
    {
        $data = json_decode(json_encode($this->getCredentialDataByKey($credential->key)));
        // update credential
        if (count($data) === 0) {
            return
                $credential->load([
                    'blastTypes',
                    'quotas.quotaType',
                    'blastLogs',
                    'recentMessages'
                ]);
        }

        $credential->update([
            'expired_at' => $data->expired_at,
            'scheduled_message_count' => $data->scheduled_message_count,
            'last_updated_at' => now(),
        ]);

        // update quotas
        $this->CRMCredentialQuotaTypeService->syncCredentialQuotaType($credential->id, $credential->key);

        return $credential->load([
            'blastTypes',
            'quotas',
            'blastLogs',
            'recentMessages'
        ]);
    }

    public function getCredentialDataByKey($key)
    {
        $url = env('ECRM_URL') . "/api/v1/whatsapp-devices/$key/key";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->acceptJson()
            ->get($url);

        if ($response->failed()) {
            $data = [];
        } else {
            $data = json_decode($response->body(), true)['data'];
        }
        return $data;
    }

    public function indexForReport($request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $timeFrame = $request->time_frame;

        $query = DB::table('crm_blast_logs')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $data = [];
        $values = [];

        switch ($timeFrame) {
            case 'daily':
                $query->select(
                    DB::raw('COUNT(*) as total_blast'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('WEEK(created_at) as week'),
                    DB::raw('DATE(created_at) as date')
                )
                    ->groupBy('year', 'month', 'week', 'date')
                    ->get()->map(function ($drones) use (&$values) {
                        $values[] = [
                            'y_axis_value' => (float) $drones->total_blast,
                            'x_axis_value' => $drones->date,
                        ];
                    });;

                break;
            case 'weekly':
                $query->select(
                    DB::raw('COUNT(*) as total_blast'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('WEEK(created_at) as week'),
                )
                    ->groupBy('year', 'week')
                    ->get()->map(function ($drones) use (&$values) {
                        if ($drones->week > 0) {
                            $date = now();
                            $date->setISODate($drones->year, $drones->week);
                            $firstDateOfTheWeek = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                            $values[] = [
                                'y_axis_value' => (float) $drones->total_blast,
                                'x_axis_value' => $firstDateOfTheWeek,
                            ];
                        }
                    });;

                break;
            case 'monthly':
                $query->select(
                    DB::raw('COUNT(*) as total_blast'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                )
                    ->groupBy('year', 'month')
                    ->get()->map(function ($drones) use (&$values) {
                        $monthOfTheYear = Carbon::createFromDate($drones->year, $drones->month, 1);
                        $monthOfTheYear = $monthOfTheYear->format('Y-m-d');
                        $values[] = [
                            'y_axis_value' => (float) $drones->total_blast,
                            'x_axis_value' => $monthOfTheYear,
                        ];
                    });;

                break;
        }

        $data['y_axis_label'] = 'Blast';
        $data['values'] = $values;

        return $data;
    }

    public function showForReport($request, $credentialId)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $timeFrame = $request->time_frame;

        $query = DB::table('crm_blast_logs')
            ->where('credential_id', $credentialId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $data = [];
        $values = [];

        switch ($timeFrame) {
            case 'daily':
                $query->select(
                    DB::raw('COUNT(*) as total_blast'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('WEEK(created_at) as week'),
                    DB::raw('DATE(created_at) as date')
                )
                    ->groupBy('year', 'month', 'week', 'date')
                    ->get()->map(function ($drones) use (&$values) {
                        $values[] = [
                            'y_axis_value' => (float) $drones->total_blast,
                            'x_axis_value' => $drones->date,
                        ];
                    });;

                break;
            case 'weekly':
                $query->select(
                    DB::raw('COUNT(*) as total_blast'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('WEEK(created_at) as week'),
                )
                    ->groupBy('year', 'week')
                    ->get()->map(function ($drones) use (&$values) {
                        if ($drones->week > 0) {
                            $date = now();
                            $date->setISODate($drones->year, $drones->week);
                            $firstDateOfTheWeek = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                            $values[] = [
                                'y_axis_value' => (float) $drones->total_blast,
                                'x_axis_value' => $firstDateOfTheWeek,
                            ];
                        }
                    });;

                break;
            case 'monthly':
                $query->select(
                    DB::raw('COUNT(*) as total_blast'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                )
                    ->groupBy('year', 'month')
                    ->get()->map(function ($drones) use (&$values) {
                        $monthOfTheYear = Carbon::createFromDate($drones->year, $drones->month, 1);
                        $monthOfTheYear = $monthOfTheYear->format('Y-m-d');
                        $values[] = [
                            'y_axis_value' => (float) $drones->total_blast,
                            'x_axis_value' => $monthOfTheYear,
                        ];
                    });;

                break;
        }

        $data['y_axis_label'] = 'Blast';
        $data['values'] = $values;

        return $data;
    }
}
