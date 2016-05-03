<?php

namespace FelixOnline\Admin\Actions;

class post_blog extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) != 1) {
			throw new \Exception('Expecting one record');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);
		$record = $records[0];

		// Get article
		$article = new \FelixOnline\Core\Article($pullThrough);

		$record->setBlog($article->getBlog());
		$record->save();

		// Post to blog
		$post = json_encode(array('type' => 'post', 'post' => array('id' => $record->getId(), 'breaking' => $record->getBreaking(), 'title' => $record->getTitle(), 'timestamp' => $record->getTimestamp(), 'data' => json_decode($record->getContent())->data[0])));

		try {
			$sprinkler = new \FelixOnline\Sprinkler('http://'.\FelixOnline\Core\Settings::get('sprinkler_host').':'.\FelixOnline\Core\Settings::get('sprinkler_port'), \FelixOnline\Core\Settings::get('sprinkler_admin'));
			$channel = $sprinkler->newChannel($article->getBlog()->getSprinklerPrefix());
			$listeners = $channel->post($post);

			return 'Posted to '.$listeners.' listeners on this blog';
		} catch(\Exception $e) {
			return 'Unable to post to live blog streamer: '.$e->getMessage();
		}		

	}
}