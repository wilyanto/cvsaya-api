<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = ['identity_card','front_selfie','left_selfie','right_selfie','payslip'];
        foreach($documentTypes as $documentType){
            DocumentType::create([
                'name' => $documentType,
            ]);
        }
    }
}
