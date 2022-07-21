<?php

namespace App\Services;

use App\Enums\SalaryTypeEnum;
use App\Models\CompanySalaryType;
use App\Models\EmployeeSalaryType;
use App\Models\SalaryType;
use Spatie\QueryBuilder\QueryBuilder;

class SalaryTypeService
{
    public function getAll()
    {
        $salaryTypes = QueryBuilder::for(SalaryType::class)
            ->get();

        return $salaryTypes;
    }

    public function getById($id)
    {
        $query = SalaryType::where('id', $id);
        $salaryType = QueryBuilder::for($query)
            ->firstOrFail();

        return $salaryType;
    }

    public function createSalaryType($data)
    {
        $salaryType = SalaryType::create([
            'name' => $data->name,
            'code' => $data->code,
            'type' => $data->type,
            'company_id' => $data->company_id,
        ]);

        return $salaryType;
    }

    public function updateSalaryType($data, $id)
    {
        $salaryType = $this->getById($id);
        $salaryType->update([
            'name' => $data->name,
            'code' => $data->code,
            'type' => $data->type,
            'company_id' => $data->company_id,
        ]);

        return $salaryType;
    }

    public function deleteById($id)
    {
        $salaryType = SalaryType::where('id', $id)->firstOrFail();
        $salaryType->delete();
        return true;
    }

    public function seedByCompanyId($companyId)
    {
        // get salary types
        $salaryTypes = $this->salaryTypeService->getAll();

        $companySalaryTypes = [];
        foreach ($salaryTypes as $salaryType) {
            $companySalaryType = CompanySalaryType::create([
                'salary_type_id' => $salaryType->id,
                'company_id' => $companyId
            ]);
            array_push($companySalaryTypes, $companySalaryType);
        }

        return $companySalaryTypes;
    }

    public function seedDefaultSalaryType()
    {
        $salaryTypes = [
            // allowances
            [
                'name' => 'Gaji Pokok',
                'code' => 'A01',
                'type' => SalaryTypeEnum::allowance(),
                'is_adhocable' => false,
            ],
            [
                'name' => 'Tunjangan Transport',
                'code' => 'A02',
                'type' => SalaryTypeEnum::allowance(),
                'is_adhocable' => false,
            ],
            [
                'name' => 'Tunjangan Makan',
                'code' => 'A03',
                'type' => SalaryTypeEnum::allowance(),
                'is_adhocable' => false,
            ],

            // deductions
            [
                'name' => 'Keterlambatan',
                'code' => 'D01',
                'type' => SalaryTypeEnum::deduction(),
                'is_adhocable' => false,
            ],

            // ad hoc
            [
                'name' => 'Lembur',
                'code' => 'A04',
                'type' => SalaryTypeEnum::allowance(),
                'is_adhocable' => true,
            ],
            [
                'name' => 'THR',
                'code' => 'A05',
                'type' => SalaryTypeEnum::allowance(),
                'is_adhocable' => true,
            ],
            [
                'name' => 'Insentif',
                'code' => 'A06',
                'type' => SalaryTypeEnum::allowance(),
                'is_adhocable' => true,
            ],
        ];

        foreach ($salaryTypes as $salaryType) {
            SalaryType::create($salaryType);
        }
    }
}
