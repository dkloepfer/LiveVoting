<?php
include_once "Services/Context/classes/class.ilContext.php";

/**
 * Class srContext
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class srContext extends ilContext {

	public function __construct() {
		self::init('srContextLvo');
	}


	/**
	 * @param int $context
	 *
	 * @return bool|void
	 */
	public static function init($context) {
		include_once('srContextLvo.php');
		self::$class_name = 'srContextLvo';
		self::$type = - 1;
	}
}

?>