<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self fixed()
 * @method static self percentage()
 */

class EmployeeSalaryTypeEnum extends Enum
{
	protected static function values(): array
	{
		return [
			'fixed' => 'fixed',
			'percentage' => 'percentage',
		];
	}
}
