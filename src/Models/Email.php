<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Email extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table   = 'email';
    protected $appends = [ 'receive' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tieude',
        'noidung',
        'files',
        'nguoigui',
        'status',
    ];

    public function __construct( array $attributes = [] )
    {
        $this->table = config('ovic.table.email', 'email');

        parent::__construct($attributes);
    }

    public function scopehasTable( $query )
    {
        if ( Schema::hasTable($this->table) ) {
            return true;
        }

        return false;
    }

    public function scopeTableName( $query )
    {
        return $this->table;
    }

    /**
     * Get the user that owns the phone.
     */
    public function receive()
    {
        return $this->hasMany(EmailReceive::class);
    }

    public function getReceiveAttribute()
    {
        return $this->receive()->get()->toArray();
    }

    /**
     * Destroy the models for the given IDs.
     *
     * @param  Collection|array|int  $ids
     * @return int
     */
    public static function destroy( $ids )
    {
        // We'll initialize a count here so we will return the total number of deletes
        // for the operation. The developers can then check this number as a boolean
        // type value or get this total count of records deleted for logging, etc.
        $count = 0;

        if ( !is_numeric($ids) && !is_array($ids) && is_string($ids) ) {
            $ids = explode(',', $ids);
        }

        if ( $ids instanceof BaseCollection ) {
            $ids = $ids->all();
        }

        $ids = is_array($ids) ? $ids : func_get_args();

        // We will actually pull the models from the database table and call delete on
        // each of them individually so that their events get fired properly with a
        // correct set of attributes in case the developers wants to check these.
        $key = ( $instance = new static )->getKeyName();

        foreach ( $instance->whereIn($key, $ids)->get() as $model ) {
            EmailReceive::where(
                [
                    'email_id'  => $model->$key,
                    'nguoinhan' => $model->receive[0]['nguoinhan']
                ]
            )->update(
                [
                    'status' => -1
                ]
            );
            $count++;
        }

        return $count;
    }
}
