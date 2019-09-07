<?php

namespace App\Model\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Campaigns\Submission;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign', 'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = ['meta'];

    /**
     * Dynamically retrieve meta attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function meta($key) {
        if (property_exists($this, $key) || isset($this->$key)) {
            return $this->getAttribute($key);
        }
        return null;
    }

    public function getMetaAttribute()
    {
        $user_meta = [
            'campaign_id' => null,
            'campaign_uuid' => null,
            'status' => null,
            'status_change' => null,
            '_secure_data' => null,
            'payment_method' => null,
            'payment_withdrawal' => null,
        ];
        foreach ($this->hasMany('UserMeta')->get()->toArray() as $meta) {
            if( substr( $meta['meta_key'], 0, 8 ) == '_secure_' ) {
                $user_meta[$meta['meta_key']] = _jsondecrypt( $meta['meta_value'] );
            } else {
                $user_meta[$meta['meta_key']] = $meta['meta_value'];
            }
        }
        return (object) $user_meta;
    }

    public function userMeta() {
        return $this->hasMany('UserMeta');
    }

    public function submissions() {
        return $this->hasMany('Submission');
    }

}
