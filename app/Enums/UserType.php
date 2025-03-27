<?php


namespace App\Enums;

enum UserType: int
{
	use EnumToArray;
	
	case EMPLOYER = 1;
	case JOB_SEEKER = 2;
	
	public function label(): string
	{
		return match ($this) {
			self::EMPLOYER => trans('enum.employer'),
			self::JOB_SEEKER => trans('enum.job_seeker'),
		};
	}
}
