<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2009 ILIAS open source, University of Cologne            |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/

require_once('./Services/Repository/classes/class.ilObjectPluginListGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/QuestionTypes/class.xlvoQuestionTypes.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/Pin/class.xlvoPin.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/Voter/class.xlvoVoter.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/Player/class.xlvoPlayer.php');

/**
 * ListGUI implementation for LiveVoting object plugin. This one
 * handles the presentation in container items (categories, courses, ...)
 * together with the corresponfing ...Access class.
 *
 * PLEASE do not create instances of larger classes here. Use the
 * ...Access class to get DB data and keep it small.
 *
 */
class ilObjLiveVotingListGUI extends ilObjectPluginListGUI {

	/**
	 * Init type
	 */
	public function initType() {
		$this->setType("xlvo");
		$this->copy_enabled = false;
	}


	/**
	 * @param $a_status
	 * @return bool
	 */
	public function enableCopy($a_status) {
		$this->copy_enabled = false;

		return false;
	}


	/**
	 * Get name of gui class handling the commands
	 */
	public function getGuiClass() {
		return "ilObjLiveVotingGUI";
	}


	/**
	 * Get commands
	 */
	public function initCommands() {
		return array(
			array(
				"permission" => "read",
				"cmd"        => "showContent",
				"default"    => true,
			),
			array(
				"permission" => "write",
				"cmd"        => "editProperties",
				"txt"        => $this->txt("xlvo_edit"),
				"default"    => false,
			),
		);
	}


	/**
	 * @param string $a_cmd
	 * @return string
	 */
	public function getCommandFrame($a_cmd) {
		if (!$this->checkCommandAccess("write", $a_cmd, $this->ref_id, $this->type)) {
			return '_blank';
		}

		return parent::getCommandFrame($a_cmd);
	}


	/**
	 * Get item properties
	 *
	 * @return    array        array of property arrays:
	 *                        "alert" (boolean) => display as an alert property (usually in red)
	 *                        "property" (string) => property name
	 *                        "value" (string) => property value
	 */
	public function getProperties() {
		$props = array();

		//		$props[] = array(
		//			"alert"    => false,
		//			"property" => 'Online',
		//			"value"    => xlvoVoter::count(xlvoPlayer::getInstanceForObjId($this->obj_id)),
		//		);
		//
		//		$props[] = array(
		//			"alert"    => false,
		//			"property" => 'PIN',
		//			"value"    => xlvoPin::lookupPin($this->obj_id),
		//		);

		require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/class.ilObjLiveVotingAccess.php');
		if (!ilObjLiveVotingAccess::checkOnline($this->obj_id)) {
			$props[] = array(
				"alert"    => true,
				"property" => $this->txt("obj_status"),
				"value"    => $this->txt("obj_offline"),
			);
		}

		return $props;
	}
}
