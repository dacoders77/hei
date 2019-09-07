<?php

namespace App\Http\Controllers\Importers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validators\CSVImportValidator;
use Exception;
use Illuminate\Support\Facades\Input;
use App\Model\Importers\Importer;

class ImporterController extends Controller
{
    private $csv_import_validator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->csv_import_validator = new CSVImportValidator;
    }

    private function countLines($file)
    {
        $f = fopen($file, 'rb');
        $lines = 0;

        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }

        fclose($f);

        return $lines;
    }

    /**
     * Move File to a temporary storage directory for processing
     * temporary directory must have 0755 permissions in order to be processed
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $csv_import
     * @return Symfony\Component\HttpFoundation\File $moved_file
     */
    private function moveFile($csv_import)
    {
        // Check if directory exists make sure it has correct permissions, if not make it
        if (is_dir($destination_directory = storage_path('imports/tmp'))) {
            chmod($destination_directory, 0755);
        } else {
            mkdir($destination_directory, 0755, true);
        }

        // Get file's original name
        $original_file_name = $csv_import->getClientOriginalName();

        // Return moved file as File object
        return $csv_import->move($destination_directory, $original_file_name);
    }

    private function removeFile($tmp_file)
    {
        unlink($tmp_file) or die("Couldn't delete file");
        return true;
    }

    /**
     * Convert file line endings to uniform "\r\n" to solve for EOL issues
     * Files that are created on different platforms use different EOL characters
     * This method will convert all line endings to Unix uniform
     *
     * @param string $file_path
     * @return string $file_path
     */
    private function normalize($file_path)
    {
        //Load the file into a string
        $string = @file_get_contents($file_path);

        if (!$string) {
            return $file_path;
        }

        //Convert all line-endings using regular expression
        $string = preg_replace('~\r\n?~', "\n", $string);

        file_put_contents($file_path, $string);

        return $file_path;
    }

    /**
     * Import method used for saving file and importing it using a database query
     * 
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $csv_import
     * @return int number of lines imported
     */
    private function import($csv_import)
    {
        try {
            // Save file to temp directory
            $moved_file = $this->moveFile($csv_import);

            // Normalize line endings
            $this->normalize($moved_file);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function store($controller,$field,$request=null)
    {

        if (Input::hasFile($field)) {
            $csv_file = Input::file($field);

            if ($csv_file->isValid()) {

                // Run validation with input file
                $validator = $this->csv_import_validator->validate($csv_file);

                if ($validator->fails()) {
                    throw new Exception($validator->errors());
                }

                $original_filename = $csv_file->getClientOriginalName();

                if ($this->import($csv_file)) {

                    $csv_import = Importer::create([
                        'controller' => $controller,
                        'original_filename' => $original_filename,
                        'status' => 'pending',
                        'row_offset' => 0,
                        'row_count' => $this->countLines( storage_path("imports/tmp/$original_filename") ),
                        'request' => $request
                    ]);

                    return true;

                }

            }
        }

        return false;
    }

    // public function foo(Importer $importer)
    // {
    //     $csv = csvToArray( storage_path("imports/tmp/{$importer->original_filename}") );

    //     for ($i=$importer->row_offset; $i < $importer->row_count; $i++) {

    //         \User::create([
    //             'email' => $csv[$i]['rand'],
    //             'password' => $csv[$i]['uniqid'],
    //         ]);

    //         $importer->row_offset++;
    //         $importer->save();
    //     }

    // }

}
