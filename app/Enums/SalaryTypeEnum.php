<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self hourly()
 * @method static self daily()
 * @method static self monthly()
 * @method static self accepted()
 */

class SalaryTypeEnum extends Enum
{
	protected static function values(): array
	{
		return [
			'hourly' => 'hourly',
			'daily' => 'daily',
			'monthly' => 'monthly'
		];
	}
}
