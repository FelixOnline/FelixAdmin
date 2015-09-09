<?php

namespace FelixOnline\Admin\Actions;

class archive_upload extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) != 1) {
			throw new \Exception('Expecting one record');
		}

		$this->validateAccess();

		$this->validateClass($records, 'FelixOnline\Core\ArchiveIssue');
		$record = new \FelixOnline\Core\ArchiveIssue($records[0]);

		// Handle the uploaded file
		$allowedExts = array("pdf");
		$extension = end(explode(".", $_FILES["pdf"]["name"]));
		if((($_FILES["pdf"]["type"] == "application/pdf")
		 || ($_FILES["pdf"]["type"] == "application/x-pdf")
		 || ($_FILES["pdf"]["type"] == "application/acrobat")
		 || ($_FILES["pdf"]["type"] == "application/vnd.pdf")
		 || ($_FILES["pdf"]["type"] == "text/pdf")
		 || ($_FILES["pdf"]["type"] == "text/x-pdf"))
		&& in_array($extension, $allowedExts))
		{
			if($_FILES["pdf"]["error"] > 0) {
				$record->delete();
				return 'Postprocessing failed, so your entry has been deleted - you did not upload a valid PDF.';
			}

			$folder = \FelixOnline\Core\Settings::get('archive_location');

			$date = explode('-', $_POST['date']);
			error_reporting(E_ALL);
			if(!file_exists($folder.'IC_'.$date[0])) {
				mkdir($folder.'IC_'.$date[0]);
			}

			if(!file_exists($folder.'thumbs')) {
				mkdir($folder.'thumbs');
			}

			$folderDest = 'IC_'.$date[0].'/'.$date[0].'_'.($record->getPublication()->getId()).'_'.($record->getIssue()).'_A.pdf';
			$finalDest = $folder.$folderDest;

			putenv("PATH=/usr/local/bin:/usr/bin:/bin:/usr/local/sbin:/usr/sbin:/sbin");

			if (move_uploaded_file($_FILES['pdf']['tmp_name'], $finalDest)) {
				// Extract thumbnail
				try {
					$imagick = new \Imagick();
					if(!$imagick->readImage($finalDest.'[0]')) { throw new Exception('Could not load PDF file'); }
					if(!$imagick->thumbnailImage(200, 200, true)) { throw new Exception('failed to generate'); }
					if(!$imagick->setImageFormat('png')) { throw new Exception('failed to generate 2'); }
					if(!$imagick->writeImage($folder.'thumbs/'.$date[0].'_'.($record->getPublication()->getId()).'_'.($record->getIssue()).'_A.png')) { throw new Exception('failed to save'); }
				} catch(\Exception $e) {
					$record->delete();
					return 'Postprocessing failed, so your entry has been deleted - could not extract PDF thumbnail: '.$e->getMessage();
				}

				$file = new \FelixOnline\Core\ArchiveFile();
				$file->setIssueId($record);
				$file->setPart('A');
				$file->setFilename($folderDest);

				// Now extract PDF content
				try {
					$parser = new \Smalot\PdfParser\Parser();
					$pdf = $parser->parseFile($finalDest);
					$text = $pdf->getText();
					$file->setContent($text);
				} catch(\Exception $e) { } // Ignore extract errors

				$file->save();
			} else {
				$record->delete();
				return 'Postprocessing failed, so your entry has been deleted - could not save PDF.';
			}
		} else {
			$record->delete();
			return 'Postprocessing failed, so your entry has been deleted - you did not upload a valid PDF.';
		}

		return 'Issue uploaded and PDF prepared, enjoy!';
	}
}