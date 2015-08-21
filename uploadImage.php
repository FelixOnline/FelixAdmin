<?php
	// Image upload script

	require('core/setup.php');

	global $currentuser;
	$currentuser = new \FelixOnline\Core\CurrentUser();

	if(!$currentuser->isLoggedIn() && $_POST['q'] != 'login') {
		header('HTTP/1.1 403 Forbidden');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		echo "Please log in.";
		exit;
	}

	// We can assume just one file is being uploaded
	if (!empty($_FILES)) {
		$tempFile = $_FILES['file']['tmp_name'];

		// Assess the file type, is it an image?
		if(!check_image_type($tempFile)) {
			header('HTTP/1.1 400 Bad Request');
			header("Cache-Control: no-cache, must-revalidate", false);
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
			header("Content-Type: text/plain", false);

			echo 'You may only upload GIF, JPEG, or PNG files.';
			exit;
		}

		$targetPath = UPLOAD_DIRECTORY.'img/upload/';
		$fileName = generate_filename($_FILES['file']['name']);
		$targetFile = $targetPath.$fileName;

		if(!move_uploaded_file($tempFile, $targetFile)) {
			header('HTTP/1.1 500 Internal Server Error');
			header("Cache-Control: no-cache, must-revalidate", false);
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
			header("Content-Type: text/plain", false);

			echo 'An error occured when uploading this file.';
			exit;
		}

		// Now to create the Image object
		try {
			$object = new \FelixOnline\Core\Image();
			$userObject = $currentuser;

			$object->setTitle($_FILES['file']['name']);
			$object->setUser($userObject);
			$object->setUri('img/upload/'.$fileName);
			$object->setDescription('');
			$object->setVOffset('');
			$object->setHOffset('');

			$sizeInfo = get_image_size($targetFile);

			$object->setWidth($sizeInfo['width']);
			$object->setHeight($sizeInfo['height']);
			$object->save();
		} catch(Exception $e) {
			header('HTTP/1.1 500 Internal Server Error');
			header("Cache-Control: no-cache, must-revalidate", false);
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
			header("Content-Type: text/plain", false);

			echo 'An error occured when saving this file\'s data.'.$e->getMessage();
			exit;
		}

		// Report image ID number
		header('HTTP/1.1 200 OK');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/plain", false);

		echo $object->getId();
		exit;
	}

	function check_image_type($file) {
		$sizeInfo = getimagesize($file);

		if(!$sizeInfo) {
			return false;
		}

		$mimeType = $sizeInfo[2]; // Yay for PHP logic

		if(in_array($mimeType , array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
			return true;
		}
		return false;
	}

	function get_image_size($file) {
		$sizeInfo = getimagesize($file);

		if(!$sizeInfo) {
			return false;
		}

		return array('width' => $sizeInfo[0], 'height' => $sizeInfo[1]);
	}

	function generate_filename($baseName) {
		global $currentuser;

		$date = date('YmdHi');

		return $date.'-'.$currentuser->getUser().'-'.$baseName;
	} 