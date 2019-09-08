<?php

namespace App\Model\Campaigns;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'meta',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'submissions';
    private static $plural = 'submission';

    protected $appends = ['meta'];

    /**
     * Dynamically retrieve meta attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function meta($key=false,$regex=false) {
        $object = $this->hasMany('SubmissionMeta');
        if($key) {
            if($regex) {
                $object = $object->where('meta_key','REGEXP',$key)->pluck('meta_value');
                if( count($object) ) return $object->toArray();
            } else {
                $object = $object->where('meta_key',$key)->first();
                if( $object ) return $object->meta_value;
            }
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
        $object = collect( array_merge( $this->toArray(), ['meta' => $meta]) );
        return $object;
    }

    public function submissionMeta() {
        return $this->hasMany('SubmissionMeta');
    }

    static function scopeWhereMeta($q, $where) {
        $table = self::$plural;
        $q->whereIn('id', function($query) use ($table,$where) {
            $query->select("{$table}_id")->from("{$table}s_meta");
            return $query->where($where);
        });
    }

    static function scopeWhereMetaValue($q, $where1, $where2=null, $where3=null) {
        $table = self::$plural;
        if (is_array($where1)) {
            foreach ($where1 as $where) {
                $q->whereIn('id', function($query) use ($table,$where) {
                    $query->select("{$table}_id")->from("{$table}s_meta");

                    if(!isset($where[2])) {
                        return $query->where('meta_key',$where[0])
                            ->where('meta_value',$where[1]);
                    } else {
                        return $query->where('meta_key',$where[0])
                            ->where('meta_value',$where[1],$where[2]);
                    }
                });
            }
        } else {
            $q->whereIn('id', function($query) use ($table,$where1,$where2,$where3) {
                $query->select("{$table}_id")->from("{$table}s_meta");

                if(!isset($where3)) {
                    return $query->where('meta_key',$where1)
                        ->where('meta_value',$where2);
                } else {
                    return $query->where('meta_key',$where1)
                        ->where('meta_value',$where2,$where3);
                }
            });
        }
    }

    static function scopeWhereMetaKey($q, $where1, $where2=null) {
        $table = self::$plural;
        if (is_array($where1)) {
            foreach ($where1 as $where) {
                $q->whereIn('id', function($query) use ($table,$where) {
                    $query->select("{$table}_id")->from("{$table}s_meta");

                    if(!isset($where[1])) {
                        return $query->where('meta_key',$where[0]);
                    } else {
                        return $query->where('meta_key',$where[0],$where[1]);
                    }
                });
            }
        } else {
            $q->whereIn('id', function($query) use ($table,$where1,$where2) {
                $query->select("{$table}_id")->from("{$table}s_meta");

                if(!isset($where2)) {
                    return $query->where('meta_key',$where1);
                } else {
                    return $query->where('meta_key',$where1,$where2);
                }
            });
        }
    }

    /**
     * Update submission meta values.
     *
     * @param  array  $meta
     * @return this
     * @throws
     */
    public function updateMeta($meta) {
        if(!is_array($meta)&&!is_object($meta)) {
            throw new \Exception("Error. Parameter requires Array or Object, String given.", 1);
        }

        foreach ($meta as $key => $value) {
            $s = \SubmissionMeta::firstOrNew([
                'submission_id' => $this->id,
                'meta_key' => $key,
            ]);
            $s->meta_value = $value;
            $s->save();
        }
        return $this;
    }
}
