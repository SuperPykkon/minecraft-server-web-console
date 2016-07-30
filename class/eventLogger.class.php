<?php
/**
 *    event logger
 */

class eventLogger {
	  public function __construct() {
			  $this->file = "..\logs\logs.log";
		}
    public function elog($log) {
		    file_put_contents($this->file, date("d.m h:i:s") . " (" . $_SERVER["REMOTE_ADDR"] . "): " . $log . "\n", FILE_APPEND);
	  }
}

?>
