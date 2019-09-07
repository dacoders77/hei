<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\User::all() as $user) {
            $keymap = [
                ['campaign','campaign_id'],
                ['uuid','campaign_uuid'],
                ['status','status'],
                ['status_change','status_change'],
                ['secure_data','_secure_data'],
            ];
            $datamap = [
                ['method','payment_method'],
                ['payout','payment_withdrawal'],
            ];

            foreach ($keymap as $kmap) {
                if(!$user->{$kmap[0]}) continue;
                $usermeta = new \UserMeta;
                $usermeta->user_id = $user->id;
                $usermeta->meta_key = $kmap[1];
                $usermeta->meta_value = $user->{$kmap[0]};
                $usermeta->save();
            }

            foreach ($datamap as $dmap) {
                if(!_json( $dmap[0], $user->data, false )) continue;
                $usermeta = new \UserMeta;
                $usermeta->user_id = $user->id;
                $usermeta->meta_key = $dmap[1];
                $usermeta->meta_value = _json( $dmap[0], $user->data, false );
                $usermeta->save();
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['campaign','uuid','status','status_change','secure_data','data']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
