<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string description
 * @property  int id_user
 */
class UserLog extends Model
{
    protected $table = 'users_logs';
    public $timestamps = true;
    protected $fillable = array(
        'id',
        'description',
        'id_user',
        'gtfs_id'
    );

    final public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
