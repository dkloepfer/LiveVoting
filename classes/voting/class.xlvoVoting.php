<?php

require_once('./Services/ActiveRecord/class.ActiveRecord.php');

/**
 *
 */
class xlvoVoting extends ActiveRecord {

	/**
	 * @return string
	 */
	public static function returnDbTableName() {
		// TODO change back name
		return 'rep_robj_xlvo_voting_n';
	}

	/*
	 * START
	 * xlvoSingleVoteVoting
	 */
	/**
	 * @var bool
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected $multi_selection;
	/**
	 * @var bool
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected $colors;


	/**
	 * @return boolean
	 */
	public function isMultiSelection() {
		return $this->multi_selection;
	}


	/**
	 * @param boolean $multi_selection
	 */
	public function setMultiSelection($multi_selection) {
		$this->multi_selection = $multi_selection;
	}


	/**
	 * @return boolean
	 */
	public function isColors() {
		return $this->colors;
	}


	/**
	 * @param boolean $colors
	 */
	public function setColors($colors) {
		$this->colors = $colors;
	}
	/*
	 * END
	 */

	//	public function afterObjectLoad() {
	//		// Aktionen wie bspw. alle VotingOptions suchen und hier als Member speichern
	//		// $this->setVotingOptions($array);
	//	}

	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 * @db_is_primary       true
	 * @con_sequence        true
	 */
	protected $id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $obj_id;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $title;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           4000
	 */
	protected $description;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           4000
	 */
	protected $question;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $voting_type;
	/**
	 * @var xlvoOption []
	 */
	// TODO AR
	protected $voting_options;


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return int
	 */
	public function getObjId() {
		return $this->obj_id;
	}


	/**
	 * @param int $obj_id
	 */
	public function setObjId($obj_id) {
		$this->obj_id = $obj_id;
	}


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}


	/**
	 * @return string
	 */
	public function getQuestion() {
		return $this->question;
	}


	/**
	 * @param string $question
	 */
	public function setQuestion($question) {
		$this->question = $question;
	}


	/**
	 * @return string
	 */
	public function getVotingType() {
		return $this->voting_type;
	}


	/**
	 * @param string $voting_type
	 */
	public function setVotingType($voting_type) {
		$this->voting_type = $voting_type;
	}


	/**
	 * @return xlvoOption[]
	 */
	public function getVotingOptions() {
		return $this->voting_options;
	}


	/**
	 * @param xlvoOption[] $voting_options
	 */
	public function setVotingOptions($voting_options) {
		$this->voting_options = $voting_options;
	}
}