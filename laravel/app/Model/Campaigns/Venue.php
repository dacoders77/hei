<?php

namespace App\Model\Campaigns;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'key',
        'meta',
    ];

    protected $table = 'venues';

    protected $appends = ['meta'];

    /**
     * Dynamically retrieve meta attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function meta($key=false) {
        $object = $this->hasMany('VenueMeta');
        if($key) {
            $object = $object->where('meta_key',$key)->first();
            if( $object ) return $object->meta_value;
        } else {
            $voucher_meta = [];
            foreach ($object->get()->toArray() as $meta) {
                if( substr( $meta['meta_key'], 0, 8 ) == '_secure_' ) {
                    $voucher_meta[$meta['meta_key']] = _jsondecrypt( $meta['meta_value'] );
                } else {
                    $voucher_meta[$meta['meta_key']] = $meta['meta_value'];
                }
            }
            return $voucher_meta;
        }
        return null;
    }

    public function getMetaAttribute()
    {
        return (object) [];
    }

    public function withMeta()
    {
        $meta = $this->meta();
        $object = (object) array_merge( $this->toArray(), ['meta' => $meta]);
        return $object;
    }

    public function venueMeta() {
        return $this->hasMany('VenueMeta');
    }

    static function scopeWhereMeta($q, $where) {
        $q->whereIn('id', function($query) use ($where) {
          return $query->select('venue_id')->from('venues_meta')
            ->where($where);
        });
    }
}
