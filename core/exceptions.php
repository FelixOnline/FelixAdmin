<?php
	namespace FelixOnline\Admin\Exceptions;

	use FelixOnline\Exceptions\UniversalException;

	/**
	 * For if a page does not exist
	 */
	class PageNotFoundException extends UniversalException {
		protected $page;
		
		public function __construct(
			$page,
			$code = 201,
			\Exception $previous = null
		) {
			$this->page = $page;
			
			parent::__construct('Page not found', $code, $previous);
		}
		
		public function getPage() {
			return $this->page;
		}
	}

	/**
	 * For if you cannot access part of a page or ajax
	 */
	class HelperNotFoundException extends UniversalException {
		protected $helper;
		
		public function __construct(
			$helper,
			$code = 202,
			\Exception $previous = null
		) {
			$this->helper = $helper;
			
			parent::__construct('Helper not found', $code, $previous);
		}
		
		public function getHelper() {
			return $this->helper;
		}
	}

	/**
	 * For if you cannot access a record
	 */
	class RecordNotFoundException extends UniversalException {
		protected $record;
		
		public function __construct(
			$record,
			$code = 203,
			\Exception $previous = null
		) {
			$this->record = $record;
			
			parent::__construct('Record not found', $code, $previous);
		}
		
		public function getRecord() {
			return $this->record;
		}
	}

	/**
	 * For if you dont have permission to access a page
	 */
	class ForbiddenPageException extends UniversalException {
		protected $page;
		protected $pageType;

		public function __construct(
			$page,
			$pageType = null,
			$code = 211,
			\Exception $previous = null
		) {
			$this->page = $page;
			$this->pageType = $page;
			
			parent::__construct('No access to page', $code, $previous);
		}
		
		public function getPage() {
			return $this->page;
		}

		public function getPageType() {
			return $this->pageType;
		}
	}

	/**
	 * For if you do not have permission to a record
	 */
	class ForbiddenRecordException extends UniversalException {
		protected $record;
		
		public function __construct(
			$record,
			$code = 212,
			\Exception $previous = null
		) {
			$this->record = $record;
			
			parent::__construct('No access to record', $code, $previous);
		}
		
		public function getRecord() {
			return $this->record;
		}
	}