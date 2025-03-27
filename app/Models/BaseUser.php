<?php


namespace App\Models;

use App\Models\Builders\HasGlobalBuilder;
use App\Models\Traits\Common\HasVerifiedAtColumn;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BaseUser extends Authenticatable
{
	use HasVerifiedAtColumn;
	use HasGlobalBuilder;
}
