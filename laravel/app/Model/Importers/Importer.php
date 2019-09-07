<?php

namespace App\Model\Importers;

use Illuminate\Database\Eloquent\Model;

class Importer extends Model
{
    protected $fillable = [
        'controller',
		'original_filename',
		'status',
		'row_count',
		'row_offset',
		'request',
    ];

    protected $table = 'importers';
}
