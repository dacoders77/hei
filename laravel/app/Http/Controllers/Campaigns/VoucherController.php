<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Importers\ImporterController;
use App\Model\Importers\Importer;
use Voucher;

class VoucherController extends Controller
{
	public function process_vouchers(Importer $importer)
	{

		$request = json_decode($importer->request);
        $mapping = (array) $request->mapping;

        // Convert the CSV to a usable array
        $csv = csvToArray( storage_path("imports/tmp/{$importer->original_filename}") );

        for ($i=$importer->row_offset; $i < $importer->row_count; $i++) {

            $row = $csv[$i];

            if(!array_filter($row)) {
                $importer->row_offset++;
                $importer->save();
                continue;
            }

            $code = $row[$mapping['_map_code']];
            $url = $row[$mapping['_map_url']];
            if( !Voucher::where('code',$code)->first() ) {
            	$voucher = Voucher::create([
            		'code' => $code,
            	]);

            	$voucher->updateMeta([
	            	'status' => 0,
	            	'campaign_id' => $request->campaign_id,
                    'url' => $url,
	            ]);
            }

            $importer->row_offset++;
            $importer->save();

        }

        return redirect()->back()->with('success', 'Records imported and updated successfully');

	}
}