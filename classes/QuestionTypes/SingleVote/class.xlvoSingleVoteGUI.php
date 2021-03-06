<?php
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/QuestionTypes/class.xlvoQuestionTypesGUI.php');

/**
 * Class xlvoSingleVoteGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy xlvoSingleVoteGUI: xlvoVoter2GUI
 */
class xlvoSingleVoteGUI extends xlvoQuestionTypesGUI {

	/**
	 * @description add JS to the HEAD
	 */
	public function initJS() {
		// TODO: Implement initJS() method.
	}


	/**
	 * @description Vote
	 */
	protected function submit() {
		$this->manager->vote($_GET['option_id']);
	}


	/**
	 * @return string
	 */
	public function getMobileHTML() {
		$tpl = new ilTemplate('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/templates/default/QuestionTypes/SingleVote/tpl.single_vote.html', false, true);
		$answer_count = 64;
		foreach ($this->manager->getVoting()->getVotingOptions() as $xlvoOption) {
			$answer_count ++;
			$this->ctrl->setParameter($this, 'option_id', $xlvoOption->getId());
			$tpl->setCurrentBlock('option');
			$tpl->setVariable('TITLE', $xlvoOption->getText());
			$tpl->setVariable('LINK', $this->ctrl->getLinkTarget($this, self::CMD_SUBMIT));
			$tpl->setVariable('OPTION_LETTER', chr($answer_count));
			if ($this->manager->hasUserVotedForOption($xlvoOption)) {
				$tpl->setVariable('ACTIVE', 'active');
				$tpl->setVariable('ACTION', $this->txt('unvote'));
			} else {
				$tpl->setVariable('ACTION', $this->txt('vote'));
			}
			$tpl->parseCurrentBlock();
		}

		return $tpl->get();
	}
}
