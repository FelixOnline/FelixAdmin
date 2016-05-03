<?php

namespace FelixOnline\Admin\Actions;

class secure_blog extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		$this->validateAccess();

		$records = $this->getRecords($records);
		$string = '';

		foreach($records as $record) {
			try {
				$sprinkler = new \FelixOnline\Sprinkler('http://'.\FelixOnline\Core\Settings::get('sprinkler_host').':'.\FelixOnline\Core\Settings::get('sprinkler_port'), \FelixOnline\Core\Settings::get('sprinkler_admin'));
				$channel = $sprinkler->newChannel($record->getBlog()->getSprinklerPrefix());
				$listeners = $channel->resetKey();

				$string .= $record->getTitle().': Channel access code reset.<br>';
			} catch(\Exception $e) {
				$string .= $record->getTitle().': Unable to post to live blog streamer: '.$e->getMessage().'<br>';
			}
		}

		return $string;
	}
}