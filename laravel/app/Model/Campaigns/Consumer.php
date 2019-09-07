<?php

namespace App\Model\Campaigns;

use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'meta',
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'consumers';

    protected $appends = ['meta','updateMeta'];

    /**
     * Dynamically retrieve meta attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function meta($key=false,$regex=false) {
        $object = $this->hasMany('ConsumerMeta');
        if($key) {
            if($regex) {
                $object = $object->where('meta_key','REGEXP',$key)->first();
            } else {
                $object = $object->where('meta_key',$key)->first();
            }
            if( $object ) return $object->meta_value;
        } else {
            $meta = $object->pluck('meta_value', 'meta_key');
            foreach ($meta as $key => $value) {
                if( substr( $key, 0, 8 ) == '_secure_' ) {
                    $meta[$key] = _jsondecrypt( $value );
                }
            }
            return (object) $meta->toArray();
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

    public function consumerMeta() {
        return $this->hasMany('ConsumerMeta');
    }

    static function scopeWhereMeta($q, $where) {
        $q->whereIn('id', function($query) use ($where) {
          return $query->select('consumer_id')->from('consumers_meta')
            ->where($where);
        });
    }

    /**
     * Update consumer meta values.
     *
     * @param  array  $meta
     * @return this
     */
    public function updateMeta($meta) {
        if(!is_array($meta)&&!is_object($meta)) {
            throw new \Exception("Error. Parameter requires Array or Object, String given.", 1);
        }

        foreach ($meta as $key => $value) {
            $c = \ConsumerMeta::firstOrNew([
                'consumer_id' => $this->id,
                'meta_key' => $key,
            ]);
            $c->meta_value = $value;
            $c->save();
        }

        return $this;
    }
}
