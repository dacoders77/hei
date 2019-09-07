<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('submissions', function(Blueprint $table) {
            $table->renameColumn('campaign', 'campaign_id');
            $table->renameColumn('user', 'user_id');
        });

        foreach (\Submission::all() as $submission) {
            $datamap = [
                ['status','claim_status'],
                ['claim_fuel','claim_fuel'],
                ['claim_amount','claim_amount'],
                ['claim_date','claim_date'],
                ['claim_receipt','claim_receipt'],
                ['comment','claim_comment'],
            ];

            foreach ($datamap as $dmap) {
                if(!_json( $dmap[0], $submission->data, false )) continue;
                $submissionmeta = new \SubmissionMeta;
                $submissionmeta->submission_id = $submission->id;
                $submissionmeta->meta_key = $dmap[1];
                $submissionmeta->meta_value = _json( $dmap[0], $submission->data, false );
                $submissionmeta->save();
            }
        }

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['data']);
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
