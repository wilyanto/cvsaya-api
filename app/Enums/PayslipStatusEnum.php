<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self unpaid()
 * @method static self paid()
 */

class PayslipStatusEnum extends Enum
{
	protected static function values(): array
	{
		return [
			'unpaid' => 'unpaid',
			'paid' => 'paid',
		];
	}
}
