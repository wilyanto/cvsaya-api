<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self allowance()
 * @method static self deduction()
 */

class SalaryTypeEnum extends Enum
{
	protected static function values(): array
	{
		return [
			'allowance' => 'allowance',
			'deduction' => 'deduction',
		];
	}
}
