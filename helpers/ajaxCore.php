<?php

namespace FelixOnline\Admin\Ajax;

class Core {
	// paulferrett.com
	public function to_camel_case($str, $capitalise_first_char = true) {
		if($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
	}

	// Widgets should be an array of: ["name" => name, "reason" => reason for highlighting widget]
	public function error($message, $status = 500, $widgets = array()) {
		switch($status) {
			case 400:
				$error = 'Bad request';
				break;
			case 403:
				$error = 'Forbidden';
				break;
			case 404:
				$error = 'Not found';
				break;
			case 405:
				$error = 'Invalid method';
				break;
			case 500:
				$error = 'Internal server error';
				break;
			default:
				$error = 'Unknown error';
				break;
		}

		header("HTTP/1.1 $status $error");
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		echo json_encode(array("message" => $message, "widgets" => $widgets));

		session_write_close();
		exit;
	}

	public function success($response) {
		header("HTTP/1.1 200 OK");
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		echo json_encode($response);

		session_write_close();
		exit;
	}

	public function widgetValidator($page, $model, $submission, $pk) {
		$badFields = array();
		foreach($page->getPageData()['fields'] as $fieldName => $fieldInfo) {
			if($fieldName == $pk && $pk == 'id') {
				continue;
			}

			if($fieldInfo['required'] && ((!array_key_exists($fieldName, $_FILES) && $submission[$fieldName] == '') || (array_key_exists($fieldName, $_FILES) && $_FILES[$fieldName]['error'] == 4)) && ( ($fieldInfo['readOnly'] && !$fieldInfo['autoField']) || !$fieldInfo['readOnly'])) {
				$badFields[] = array('name' => $fieldName, 'reason' => $fieldInfo['label'].' is required.');

				continue;
			}

			$fieldType = get_class($model->fields[$fieldName]);

			if($fieldType == 'FelixOnline\Core\Type\Integerfield' && $submission[$fieldName] != '') {
				if(preg_match('/[^0-9.]/', $submission[$fieldName])) {
					$badFields[] = array('name' => $fieldName, 'reason' => $fieldInfo['label'].' can only be a number.');

					continue;
				}
			}

			if($fieldType == 'FelixOnline\Core\Type\DateTimeField') {
				if(!strtotime($submission[$fieldName]) && $submission[$fieldName] != '') {
					$badFields[] = array('name' => $fieldName, 'reason' => 'The value you specified for '.$fieldInfo['label'].' could not be understood. Try using the calendar popup.');

					continue;
				}
			}

			if(array_key_exists('validation', $fieldInfo)) {
				switch($fieldInfo['validation']) {
					case 'email':
						if(!is_email($submission[$fieldName]) && $submission[$fieldName] != '') {
							$badFields[] = array('name' => $fieldName, 'reason' => 'The value you specified for '.$fieldInfo['label'].' is not a valid email address.');

							continue;
						}
						break;
					default:
						$badFields[] = array('name' => $fieldName, 'reason' => 'Unknown validator type '.$fieldInfo['validation'].'.');
						break;
				}
			}

			// Check foreign key
			if($fieldType == 'FelixOnline\Core\Type\ForeignKey') {
				try {
					$fkType = $model->fields[$fieldName]->class;

					if($fkType == 'FelixOnline\Core\Text' || $submission[$fieldName] == '') {
						continue; // We are not changing the key so we do not need to validate the field's existance
					}

					$fk = new $fkType($submission[$fieldName]);
				} catch(\Exception $e) {
					$badFields[] = array('name' => $fieldName, 'reason' => $fieldInfo['label'].' could not find data.');
					continue;
				}
			}
		}

		return $badFields;
	}
}