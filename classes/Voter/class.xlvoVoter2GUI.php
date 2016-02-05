<?php
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/class.xlvoGUI.php');
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/Pin/class.xlvoPin.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/Player/class.xlvoPlayer.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/Voting/class.xlvoVoting.php');
require_once('./Services/jQuery/classes/class.iljQueryUtil.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/QuestionTypes/class.xlvoInputMobileGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/QuestionTypes/class.xlvoQuestionTypes.php');

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/Voting/class.xlvoVotingManager.php');

/**
 * Class xlvoVoter2GUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy xlvoVoter2GUI: ilUIPluginRouterGUI
 */
class xlvoVoter2GUI extends xlvoGUI {

	const CMD_CHECK_PIN = 'checkPin';
	const F_PIN_INPUT = 'pin_input';
	const CMD_START_VOTER_PLAYER = 'startVoterPlayer';
	const CMD_GET_VOTING_DATA = 'getVotingData';
	/**
	 * @var string
	 */
	protected $pin = '';
	/**
	 * @var xlvoVotingManager2
	 */
	protected $manager;


	public function __construct() {
		parent::__construct();
		$this->pin = xlvoInitialisation::getCookiePIN();
		$this->manager = new xlvoVotingManager2($this->pin);
	}


	/**
	 * @param $key
	 * @return string
	 */
	protected function txt($key) {
		return $this->pl->txt('voter_' . $key);
	}


	public function executeCommand() {
		$nextClass = $this->ctrl->getNextClass();
		switch ($nextClass) {
			case '':
				parent::executeCommand();
				break;
			default:
				// Question-types
				require_once($this->ctrl->lookupClassPath($nextClass));
				$gui = new $nextClass();
				if ($gui instanceof xlvoQuestionTypesGUI) {
					$gui->setVoting($this->manager->getVoting());
				}
				$this->ctrl->forwardCommand($gui);
				break;
		}
	}


	protected function index() {
		if ($this->manager->getObjId()) {
			$this->ctrl->redirect($this, self::CMD_START_VOTER_PLAYER);
		}
		$pin_form = new ilPropertyFormGUI();
		$pin_form->setFormAction($this->ctrl->getLinkTarget($this, self::CMD_CHECK_PIN));
		$pin_form->addCommandButton(self::CMD_CHECK_PIN, $this->txt('send'));

		$te = new ilTextInputGUI($this->txt(self::F_PIN_INPUT), self::F_PIN_INPUT);
		$te->setCssClass('xlvo_pin_field');
		$pin_form->addItem($te);

		$this->tpl->setContent($pin_form->getHTML());
	}


	protected function checkPin() {
		if (!xlvoPin::checkPin($_POST[self::F_PIN_INPUT])) {
			ilUtil::sendFailure($this->pl->txt('msg_validation_error_pin'));
			$this->index();
		} else {
			xlvoInitialisation::setCookiePIN($_POST[self::F_PIN_INPUT]);
			$this->ctrl->redirect($this, self::CMD_START_VOTER_PLAYER);
		}
	}


	protected function startVoterPlayer() {
		$this->initJs();
		$this->tpl->addCss('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/templates/default/Voting/display/default.css');
		$tpl = new ilTemplate('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/templates/default/Voter/tpl.voter_player.html', true, false);
		$this->tpl->setContent($tpl->get());
	}


	protected function getVotingData() {
		xlvoJsResponse::getInstance($this->manager->getPlayer()->getStdClassForVoter())->send();
	}


	protected function initJs() {
		iljQueryUtil::initjQueryUI();
		$settings = array(
			'player_id' => '#xlvo_voter_player',
			'obj_id' => $this->manager->getObjId(),
			'cmd_voting_data' => self::CMD_GET_VOTING_DATA
		);
		xlvoJs::getInstance()->api($this, array( 'ilUIPluginRouterGUI' ))->name('Voter')->addSettings($settings)->init()->call('run');
		foreach (xlvoQuestionTypes::getActiveTypes() as $type) {
			xlvoQuestionTypesGUI::getInstance($type)->initJS();
		}
	}


	protected function getHTML() {
		$tpl = new ilTemplate('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/templates/default/Voter/tpl.inner_screen.html', true, true);
		switch ($this->manager->getPlayer()->getStatus(true)) {
			case xlvoPlayer::STAT_STOPPED:
				$tpl->setVariable('TITLE', $this->txt('header_stopped'));
				$tpl->setVariable('DESCRIPTION', $this->txt('info_stopped'));;
				break;
			case xlvoPlayer::STAT_RUNNING:
				$tpl->setVariable('TITLE', $this->manager->getVoting()->getTitle());
				$tpl->setVariable('DESCRIPTION', $this->manager->getVoting()->getDescription());

				$xlvoQuestionTypesGUI = xlvoQuestionTypesGUI::getInstance($this->manager->getVoting()->getVotingType(), $this->manager->getVoting());

				$tpl->setVariable('QUESTION', $xlvoQuestionTypesGUI->getMobileHTML());
				break;
			case xlvoPlayer::STAT_START_VOTING:
				$tpl->setVariable('TITLE', $this->txt('header_start'));
				$tpl->setVariable('DESCRIPTION', $this->txt('info_start'));
				$tpl->touchBlock('spinner');
				break;
			case xlvoPlayer::STAT_END_VOTING:
				$tpl->setVariable('TITLE', $this->txt('header_end'));
				$tpl->setVariable('DESCRIPTION', $this->txt('info_end'));;
				break;
			case xlvoPlayer::STAT_FROZEN:
				$tpl->setVariable('TITLE', $this->manager->getVoting()->getTitle() . ': ' . $this->txt('header_frozen'));
				$tpl->setVariable('DESCRIPTION', $this->txt('info_frozen'));
				$tpl->touchBlock('spinner');
				break;
		}
		echo $tpl->get();
		exit;
	}
}
