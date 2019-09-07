<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Timestamp extends Model
{
    protected $fillable = [
    	'campaign_id',
        'timestamp',
    ];

    protected $table = 'timestamps';
    protected $appends = ['reset'];

    public static function reset($campaign_id) {
    	$timestamps = self::where('campaign_id',$campaign_id)->get();
    	if(!$timestamps->count()) return false;

    	foreach( $timestamps as $timestamp ) {
    		$timestamp->delete();
    	}

    	return true;
    }
}
