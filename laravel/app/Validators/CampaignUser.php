<?php

namespace App\Validators;

use User;

class CampaignUser
{
    public function validate(
        $attribute,
        $value,
        $parameters,
        $validator
    ){

        if(!empty($value)) {

            // Get User if exists
            $user = User::where('email',$value)->whereHas('userMeta', function($query) use ($parameters) {
                return $query->where('meta_key','campaign_id')->where('meta_value',$parameters[0]);
            })->whereHas('userMeta', function($query) {
                return $query->where('meta_key','status')->where('meta_value',2);
            })->first();

            return $user ? true : false;
        }

        return false;
    }

}