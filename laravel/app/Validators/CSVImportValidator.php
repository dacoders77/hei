<?php

namespace App\Validators;

use Validator;
use Exception;
use Illuminate\Validation\ValidationException;

/**
 * Validate CSV file
 * @see https://www.grok-interactive.com/blog/import-large-csv-into-mysql-with-php-part-2-csv-file-validation/
 *
 */

class CSVImportValidator
{

    /**
     * Validation rules for CsvImport
     *
     */
    private $rules = [
        'csv_extension'     => 'in:csv',
    ];

    public function validate($csv_file_path)
    {

        ini_set('auto_detect_line_endings', true);

        $csv_extension = $csv_file_path->getClientOriginalExtension();

        // Open file into memory
        if (!$opened_file = fopen($csv_file_path->getRealPath(), 'r')) {
            throw new Exception('File cannot be opened for reading');
        }

        // Close file and free up memory
        fclose($opened_file);

        try {
            $csv = csvToArray($csv_file_path);
        } catch (Exception $e) {
            $validator = Validator::make([], []);
            $validator->errors()->add('csv', 'invalid');
            throw new ValidationException($validator);
        }

        // Build our validation array
        $validation_array = [
            'csv_extension' => $csv_extension,
        ];

        // Return validator object
        return Validator::make($validation_array, $this->rules);
    }
}