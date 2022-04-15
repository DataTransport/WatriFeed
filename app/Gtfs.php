<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(int $id)
 */
class Gtfs extends Model
{
    protected $table = 'gtfs';
    public $timestamps = true;
    protected $fillable = array('name','password','user_id');

    final public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    final public function agencies(): HasMany
    {
        return $this->hasMany(Agency::class);

    }
    final public function calendar_dates(): HasMany
    {
        return $this->hasMany(CalendarDate::class);

    }
    final public function calendars(): HasMany
    {
        return $this->hasMany(Calendar::class);

    }
    final public function fare_attributes(): HasMany
    {
        return $this->hasMany(FareAttribute::class);

    }
    final public function fare_rules(): HasMany
    {
        return $this->hasMany(FareRule::class);

    }
    final public function frequencies(): HasMany
    {
        return $this->hasMany(Frequency::class);

    }
    final public function levels(): HasMany
    {
        return $this->hasMany(Level::class);

    }
    final public function pathways(): HasMany
    {
        return $this->hasMany(Pathway::class);

    }

    final public function routes(): HasMany
    {
        return $this->hasMany(Route::class);

    }
    final public function shapes(): HasMany
    {
        return $this->hasMany(Shape::class);

    }
    final public function stops(): HasMany
    {
        return $this->hasMany(Stop::class);

    }
    final public function stoptimes(): HasMany
    {
        return $this->hasMany(Stoptime::class);

    }
    final public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);

    }
    final public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);

    }


}

