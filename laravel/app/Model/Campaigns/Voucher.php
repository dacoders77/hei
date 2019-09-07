<?php

namespace App\Model\Campaigns;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
	protected $fillable = [
        'code',
        'meta'
    ];

    protected $table = 'vouchers';

    protected $appends = ['meta','updateMeta'];

    /**
     * Dynamically retrieve meta attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function meta($key=false) {
        $object = $this->hasMany('VoucherMeta');
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
            return (object) $voucher_meta;
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

    public function voucherMeta() {
        return $this->hasMany('VoucherMeta');
    }

    static function scopeWhereMeta($q, $where) {
        $q->whereIn('id', function($query) use ($where) {
          return $query->select('voucher_id')->from('vouchers_meta')
            ->where($where);
        });
    }

    /**
     * Update meta values.
     *
     * @param  array  $meta
     * @return this
     */
    public function updateMeta($meta) {
        if(!is_array($meta)&&!is_object($meta)) {
            throw new \Exception("Error. Parameter requires Array or Object, String given.", 1);
        }

        foreach ($meta as $key => $value) {
            $c = \VoucherMeta::firstOrNew([
                'voucher_id' => $this->id,
                'meta_key' => $key,
            ]);
            $c->meta_value = $value;
            $c->save();
        }

        return $this;
    }
}
