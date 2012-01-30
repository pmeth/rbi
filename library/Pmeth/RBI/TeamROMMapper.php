<?php

/**
 * Description of TeamROMMapper
 *
 * @author Peter Meth
 */
 namespace Pmeth\RBI;
class TeamROMMapper extends BaseROMMapper {

	function __construct($rom) {
		parent::__construct($rom, 'Team');
	}

	public function get($offset) {
		$teams = $this->getTeams();
		$team = new Team($offset);
		$team->setAbbreviation($teams[$offset]);
		$team->setName($teams[$offset]);
		return $team;
	}

	public function getAllTeams() {
		$teams = $this->getTeams();
		$teamscollection = new TeamCollection();
		foreach ($teams as $offset => $teamname) {
			$team = new Team($offset);
			$team->setAbbreviation($teamname);
			$team->setName($teamname);
			$teamscollection->addTeam($team);
		}
		return $teamscollection;
	}

	protected function getTeams() {
      // todo: come up with a better check
      if($this->rom->getMaxPlayerNameLength() == 6) { // this means we are dealing with RBI Rom
         
         return array_fill(1,35,'Team');
      }
		$start = $this->rom->getTeamStart();
		$end = $this->rom->getTeamEnd();
		$numcharacters = $end - $start;

		$newstring = $this->rom->getHexString($start, $numcharacters);

		$teamshex = str_split($newstring, 4);
		$teamHexToChar = $this->rom->getTeamHexToChar();
		// remove teams 30 & 31, they're not used
		unset($teamshex[29]);
		unset($teamshex[30]);

		$teams = array();

		foreach ($teamshex as $teamnum => $teamhex) {
			$teams[$teamnum + 1] = $teamHexToChar[substr($teamhex, 0, 2)] . $teamHexToChar[substr($teamhex, 2, 2)];
		}
		return $teams;
	}

}

