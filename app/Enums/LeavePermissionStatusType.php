<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self declined()
 * @method static self waiting()
 * @method static self needMoreDetails()
 * @method static self accepted()
 */

class LeavePermissionStatusType extends Enum
{
	protected static function values(): array
	{
		return [
			'declined' => 'declined',
			'waiting' => 'waiting',
			'needMoreDetails' => 'need_more_details',
			'accepted' => 'accepted'
		];
	}
}
