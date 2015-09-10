<?php

class DateTimeView {


   /**
	* Shows date and time
	* @param $timestamp int
	* @return string
	*/
	public function show($timestamp = null) {

		// Set to current time if not set
		if (!isset($timestamp)) {
			$timestamp = time();
		}

		$timeString = date('l, \t\h\e jS \of F Y, \T\h\e \t\i\m\e \i\s H:i:s', $timestamp);

		return '<p>' . $timeString . '</p>' . time();


	}
}