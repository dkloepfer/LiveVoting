<?php

require_once('./Services/ActiveRecord/class.ActiveRecord.php');

/**
 * Class xlvoVote
 *
 * @author  Daniel Aemmer <daniel.aemmer@phbern.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class xlvoVote extends ActiveRecord {

	const STAT_INACTIVE = 0;
	const STAT_ACTIVE = 1;
	const USER_ILIAS = 0;
	const USER_ANONYMOUS = 1;


	/**
	 * @param xlvoUser $xlvoUser
	 * @param $voting_id
	 * @param null $option_id
	 */
	public static function vote(xlvoUser $xlvoUser, $voting_id, $option_id = null) {
		$obj = self::getUserInstance($xlvoUser, $voting_id, $option_id);
		$obj->setStatus(self::STAT_ACTIVE);
		$obj->store();
	}


	/**
	 * @param xlvoUser $xlvoUser
	 * @param $voting_id
	 * @param null $option_id
	 */
	public static function unvote(xlvoUser $xlvoUser, $voting_id, $option_id = null) {
		$obj = self::getUserInstance($xlvoUser, $voting_id, $option_id);
		$obj->setStatus(self::STAT_INACTIVE);
		$obj->store();
	}


	public function store() {
		if (self::where(array( 'id' => $this->getId() ))->hasSets()) {
			$this->update();
		} else {
			$this->create();
		}
	}


	/**
	 * @param xlvoUser $xlvoUser
	 * @param $voting_id
	 * @return ActiveRecord[]
	 */
	public static function getVotesOfUser(xlvoUser $xlvoUser, $voting_id) {

		$where = array( 'voting_id' => $voting_id );
		if ($xlvoUser->isILIASUser()) {
			$where['user_id'] = $xlvoUser->getIdentifier();
		} else {
			$where['user_identifier'] = $xlvoUser->getIdentifier();
		}

		return self::where($where)->get();
	}


	/**
	 * @param xlvoUser $xlvoUser
	 * @param $voting_id
	 * @param $option_id
	 * @return ActiveRecord|xlvoVote
	 */
	protected static function getUserInstance(xlvoUser $xlvoUser, $voting_id, $option_id) {
		$where = array( 'voting_id' => $voting_id );
		if ($option_id) {
			$where = array( 'option_id' => $option_id );
		}
		if ($xlvoUser->isILIASUser()) {
			$where['user_id'] = $xlvoUser->getIdentifier();
		} else {
			$where['user_identifier'] = $xlvoUser->getIdentifier();
		}

		$vote = self::where($where)->first();

		if (!$vote instanceof self) {
			$vote = new self();
			if ($xlvoUser->isILIASUser()) {
				$vote->setUserIdType(self::USER_ILIAS);
				$vote->setUserId($xlvoUser->getIdentifier());
			} else {
				$vote->setUserIdType(self::USER_ILIAS);
				$vote->setUserIdentifier($xlvoUser->getIdentifier());
			}
		}

		return $vote;
	}


	/**
	 * @return string
	 */
	public static function returnDbTableName() {
		return 'rep_robj_xlvo_vote_n';
	}


	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $free_input;


	/**
	 * @return string
	 */
	public function getFreeInput() {
		return $this->free_input;
	}


	/**
	 * @param string $free_input
	 */
	public function setFreeInput($free_input) {
		$this->free_input = $free_input;
	}


	/**
	 * @var string
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
	protected $type;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $status;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $option_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $voting_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $user_id_type;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $user_identifier = 0;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $user_id = 0;


	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param int $type
	 */
	public function setType($type) {
		$this->type = $type;
	}


	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}


	/**
	 * @return int
	 */
	public function getOptionId() {
		return $this->option_id;
	}


	/**
	 * @param int $option_id
	 */
	public function setOptionId($option_id) {
		$this->option_id = $option_id;
	}


	/**
	 * @return int
	 */
	public function getVotingId() {
		return $this->voting_id;
	}


	/**
	 * @param int $voting_id
	 */
	public function setVotingId($voting_id) {
		$this->voting_id = $voting_id;
	}


	/**
	 * @return int
	 */
	public function getUserIdType() {
		return $this->user_id_type;
	}


	/**
	 * @param int $user_id_type
	 */
	public function setUserIdType($user_id_type) {
		$this->user_id_type = $user_id_type;
	}


	/**
	 * @return int
	 */
	public function getUserIdentifier() {
		return $this->user_identifier;
	}


	/**
	 * @param int $user_identifier
	 */
	public function setUserIdentifier($user_identifier) {
		$this->user_identifier = $user_identifier;
	}


	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->user_id;
	}


	/**
	 * @param int $user_id
	 */
	public function setUserId($user_id) {
		$this->user_id = $user_id;
	}
}