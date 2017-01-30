<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 14/07/12
 * Time: 11:38 PM
 * To change this template use File | Settings | File Templates.
 */
class Choice {

	private $id;
	private $description;
	private $correctChoice;

	function __construct($id, $description, $correctChoice){
		$this->id = $id;
		$this->description = $description;
		$this->correctChoice = $correctChoice;
	}

	public function getCorrectChoice() {
		return $this->correctChoice;
	}

	public function getDescription() {
		return $this->description;
	}


	public function getId() {
		return $this->id;
	}
}
