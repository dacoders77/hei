<?php

namespace App\Model\Campaigns;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'domain',
		'controller',
		'data',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'campaigns';
    protected $appends = ['meta'];

    /**
     * Dynamically retrieve meta attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function meta($key=false,$regex=false) {
        $object = $this->hasMany('CampaignMeta');
        if($key) {
            if($regex) {
                $object = $object->where('meta_key','REGEXP',$key)->pluck('meta_value');
                if( count($object) ) return $object->toArray();
            } else {
                $object = $object->where('meta_key',$key)->first();

                if($object && $key == 'form_content') {
                    return json_decode( $object->meta_value );
                }

                if( $object ) return $object->meta_value;
            }
        } else {
            $meta = $object->pluck('meta_value', 'meta_key');
            foreach ($meta as $key => $value) {
                if( substr( $key, 0, 8 ) == '_secure_' ) {
                    $meta[$key] = _jsondecrypt( $value );
                } else if($key == 'form_content') {
                    $meta[$key] = json_decode($value);
                }
            }
            return (object) $meta->toArray();
        }
        return null;
    }

    // Get Submissions for campaign
    public function submissions() {
        return $this->hasMany('Submission');
    }

    //
    public function getMetaAttribute()
    {
        return (object) [];
    }

    /**
     *
     * Check if form content contains recaptcha
     *
     */
    public function hasCaptcha() {
        foreach ($this->meta('form_content') as $element) {
            if($element->type == 'recaptcha') {
                return true;
                break;
            }
        }
        return false;
    }

    /**
     * Update campaign meta values.
     *
     * @param  array  $meta
     * @return this
     */
    public function updateMeta($meta) {
        if(!is_array($meta)&&!is_object($meta)) {
            throw new \Exception("Error. Parameter requires Array or Object, String given.", 1);
        }

        foreach ($meta as $key => $value) {
            $c = \CampaignMeta::firstOrNew([
                'campaign_id' => $this->id,
                'meta_key' => $key,
            ]);
            $c->meta_value = $value;
            $c->save();
        }

        return $this;
    }

    /**
     * Dynamically clone campaign.
     *
     * @param  string  $url
     * @return mixed
     */
    public function clone($url) {
        $object = $this->hasMany('CampaignMeta');
        $meta = $object->pluck('meta_value', 'meta_key');

        $clone = $this->replicate();
        $clone->url = $url;
        $clone->status = 0;
        $clone->save();

        $clone->updateMeta($meta);

        return $clone;
    }
}
