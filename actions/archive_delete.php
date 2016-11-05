<?php

namespace FelixOnline\Admin\Actions;

class archive_delete extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		foreach($records as $record) {
			$record = new \FelixOnline\Core\ArchiveIssue($record);
			$folder = \FelixOnline\Core\Settings::get('archive_location');

			foreach($record->getFiles() as $file) {
				@unlink($folder.$file->getFilename());
				@unlink($folder.'thumbs/'.$file->getThumbnail());
				$file->purge('Deleting as requested on webadmin');
			}

			$record->purge('Deleting as requested on webadmin');
		}

		return 'Issue(s) deleted and issue number released for new PDF';
	}
}