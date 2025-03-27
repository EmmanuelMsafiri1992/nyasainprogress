<?php


namespace App\Models;

use App\Models\Traits\Common\AppendsTrait;
use App\Models\Traits\PermissionTrait;
use App\Observers\PermissionObserver;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Permission\Models\Permission as OriginalPermission;

#[ObservedBy([PermissionObserver::class])]
class Permission extends OriginalPermission
{
	use Crud, AppendsTrait;
	use PermissionTrait;
	
	/**
	 * @var array<int, string>
	 */
	protected $fillable = ['name', 'guard_name', 'updated_at', 'created_at'];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	/**
	 * Default Super Admin users permissions
	 *
	 * @return array<int, string>
	 */
	public static function getSuperAdminPermissions(): array
	{
		return [
			'permission-list',
			'permission-create',
			'permission-update',
			'permission-delete',
			'role-list',
			'role-create',
			'role-update',
			'role-delete',
		];
	}
	
	/**
	 * Default Staff users permissions
	 *
	 * @return array<int, string>
	 */
	public static function getStaffPermissions(): array
	{
		return [
			'dashboard-access',
		];
	}
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| ACCESSORS | MUTATORS
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
}
