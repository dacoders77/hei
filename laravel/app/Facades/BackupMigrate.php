<?php

namespace App\Facades;

use Cornford\Backup\Facades\Backup;
use PharData;
use Phar;

class BackupMigrate
{

	public static function exportSQL()
	{
		return Backup::export( 'backup-' . date('Ymd-His') );
	}

	public static function restoreSQL($filename)
	{
		return Backup::restore(config('backup.path').'/'.$filename);
	}

	public static function exportFiles()
	{
		$dest = config('backup.path');
		$filename = config('backup.filename');

		try {
			$compressedFile = new PharData("$dest/$filename.files.tar");
			$compressedFile->buildFromDirectory( storage_path('app') );
			$compressedFile->compress(Phar::GZ, 'files.gz');
			unlink("$dest/$filename.files.tar");
		} catch (\BadMethodCallException | \PharException $e) {
			return $e->getMessage();
		}

		return true;
	}

	public static function restoreFiles($filename)
	{
		$dest = storage_path('app');

		try {
			$compressedFile = new PharData( config('backup.path') . '/' . $filename );

			$di = new \RecursiveDirectoryIterator($dest, \FilesystemIterator::SKIP_DOTS);
			$ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
			foreach ( $ri as $file ) {
			    $file->isDir() ?  rmdir($file) : unlink($file);
			}

			$compressedFile->extractTo($dest, null, true);

		} catch (\BadMethodCallException | \UnexpectedValueException $e) {
			return $e->getMessage();
		}

		return true;
	}
}