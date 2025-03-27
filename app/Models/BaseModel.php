<?php


namespace App\Models;

use App\Models\Builders\HasGlobalBuilder;
use App\Models\Traits\Common\HasActiveColumn;
use App\Models\Traits\Common\HasVerifiedAtColumn;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
	use HasVerifiedAtColumn;
	use HasActiveColumn;
	use HasGlobalBuilder;
}
