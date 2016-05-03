<?php

namespace FelixOnline\Admin\Actions;

class pull_blog extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) != 1) {
			throw new \Exception('Please only select one post');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);
		$record = $records[0];

		// Get article
		$article = new \FelixOnline\Core\Article($pullThrough);

		// Post to blog
		$post = json_encode(array('type' => 'delete', 'delete' => $record->getId()));

		try {
			$sprinkler = new \FelixOnline\Sprinkler('http://'.\FelixOnline\Core\Settings::get('sprinkler_host').':'.\FelixOnline\Core\Settings::get('sprinkler_port'), \FelixOnline\Core\Settings::get('sprinkler_admin'));
			$channel = $sprinkler->newChannel($article->getBlog()->getSprinklerPrefix());
			$listeners = $channel->post($post);

			$record->delete();

			return 'Deleted! Post removed from all '.$listeners.' connected client(s).';
		} catch(\Exception $e) {
			return 'Unable to post to live blog streamer: '.$e->getMessage();
		}		

	}
}