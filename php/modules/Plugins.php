<?php

###
# @name		Plugins Module
# @author		Tobias Reich
# @copyright	2014 by Tobias Reich
###

class Plugins implements \SplSubject {

	private $files		= array();
	private $observers	= array();

	public $action	= null;
	public $args	= null;

	public function __construct($files, $database) {

		if (!isset($files)) return false;

		# Init vars
		$plugins		= $this;
		$this->files	= $files;

		# Load plugins
		foreach ($this->files as $file) {
			if ($file==='') continue;
			include(__DIR__ . '/../../plugins/' . $file);
		}

		return true;

	}

	public function attach(\SplObserver $observer) {

		if (!isset($observer)) return false;

		# Add observer
		$this->observers[] = $observer;

		return true;

	}

	public function detach(\SplObserver $observer) {

		if (!isset($observer)) return false;

		# Remove observer
		$key = array_search($observer, $this->observers, true);
		if ($key) unset($this->observers[$key]);

		return true;

	}

	public function notify() {

		# Notify each observer
		foreach ($this->observers as $value) $value->update($this);

		return true;

	}

	public function activate($action, $args) {

		if (!isset($action, $args)) return false;

		# Save vars
		$this->action	= $action;
		$this->args		= $args;

		# Notify observers
		$this->notify();

		return true;

	}

}

?>