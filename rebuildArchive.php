<?php
/**
 * Archive thumbnail/text rebuild script
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

ini_set('memory_limit','1G');

date_default_timezone_set('Europe/London');

require('core/setup.php');

$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\ArchiveFile', 'archive_file');
$manager->filter('part = "A"');
$manager->order('id', 'DESC');

$values = $manager->values();

if(!$values) {
	echo "Nothing to do.\n";
	return;
}

$folder = \FelixOnline\Core\Settings::get('archive_location');

putenv("PATH=/usr/local/bin:/usr/bin:/bin:/usr/local/sbin:/usr/sbin:/sbin");

foreach($values as $record) {
	$folderDest = $record->getFilename();
	$finalDest = $folder.$folderDest;

	$pngName = str_ireplace('.pdf', '.png', $record->getOnlyFilename());

	echo $record->getFilename()."\n";

	// Regenerate thumbnail
	try {
		$imagick = new \Imagick();
		if(!$imagick->readImage($finalDest.'[0]')) { throw new Exception('Could not load PDF file'); }
		if(!$imagick->thumbnailImage(400, 400, true)) { throw new Exception('failed to generate'); }
		if(!$imagick->setImageFormat('png')) { throw new Exception('failed to set format'); }
		if(!$imagick->writeImage($folder.'thumbs/'.$pngName)) { throw new Exception('failed to save'); }
	} catch(\Exception $e) {
		echo "-- Failed to update image.\n";
	}

	// Regenerate content
	try {
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($finalDest);
		$text = $pdf->getText();
		$record->setContent($text);
		$record->save();
	} catch(\Exception $e) {
		echo "-- Failed to update text.\n";
	}

	echo "\n";
}

echo "All done.\n";
