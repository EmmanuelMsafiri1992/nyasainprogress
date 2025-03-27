<?php


namespace App\Models;

use App\Models\Scopes\LocalizedScope;
use App\Models\Traits\Common\AppendsTrait;
use App\Models\Traits\Common\HasCountryCodeColumn;
use App\Models\Traits\SubAdmin2Trait;
use App\Observers\SubAdmin2Observer;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([SubAdmin2Observer::class])]
#[ScopedBy([LocalizedScope::class])]
class SubAdmin2 extends BaseModel
{
	use Crud, AppendsTrait, HasFactory, HasCountryCodeColumn, HasTranslations;
	use SubAdmin2Trait;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'subadmin2';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'code';
	protected $keyType = 'string';
	public $incrementing = false;
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;
	
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $guarded = ['id'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = ['country_code', 'subadmin1_code', 'code', 'name', 'active'];
	
	/**
	 * @var array<int, string>
	 */
	public array $translatable = ['name'];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function subAdmin1(): BelongsTo
	{
		return $this->belongsTo(SubAdmin1::class, 'subadmin1_code', 'code');
	}
	
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
	protected function id(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => $this->code ?? ($this->attributes['code'] ?? $value),
		);
	}
	
	protected function name(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				if (isset($this->attributes['name']) && !isJson($this->attributes['name'])) {
					return $this->attributes['name'];
				}
				
				return $value;
			},
		);
	}
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
}
