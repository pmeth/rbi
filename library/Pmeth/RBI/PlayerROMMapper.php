<?php

/**
 * Description of PlayerROMMapper
 *
 * @author Peter Meth
 */
 namespace Pmeth\RBI;
class PlayerROMMapper extends BaseROMMapper {

	protected $offsetSize = 36;
	protected $type;

	function __construct(RBI3Rom $rom, $entityname = 'Player') {
		parent::__construct($rom, $entityname);
		$this->type = $entityname;
	}

	public function get($offset) {
		$playerhex = $this->getPlayerHex($offset);
		if ($this->valid($playerhex)) {
			if ($offset >= $this->rom->getHitterStart() && $offset <= $this->rom->getHitterEnd()) {
				// this must be a hitter
				$player = new Hitter($offset);
			} else {
				$player = new Pitcher($offset);
			}
			
			$player->setLineupNumber($this->getLineupNumberFromHex($playerhex));
			$player->setName(trim($this->getNameFromHex($playerhex)));
			$teammapper = new TeamROMMapper($this->rom);
			$player->setTeam($teammapper->get($this->getTeamOffset($offset)));
			return $player;
		}
	}

	public function save(Player $player) {
		$newhex = $this->getPlayerHex($player->getOffset());
		
		//lineupnumber
		$newhex = substr_replace($newhex, $this->decToHex($player->getLineupNumber()), 0, 2);
		
		//name
		$namearray = $this->getHexArrayFromName($player->getName(), $this->rom->getMaxPlayerNameLength());
		$newhex = substr_replace($newhex, $namearray[0], 2, 2);
		$newhex = substr_replace($newhex, $namearray[1], 4, 2);
		$newhex = substr_replace($newhex, $namearray[2], 6, 2);
		$newhex = substr_replace($newhex, $namearray[3], 8, 2);
		$newhex = substr_replace($newhex, $namearray[4], 10, 2);
		$newhex = substr_replace($newhex, $namearray[5], 12, 2);
		$newhex = substr_replace($newhex, $namearray[6], 32, 2);
		$newhex = substr_replace($newhex, $namearray[7], 34, 2);
		
		$this->rom->setHexString($newhex, $player->getOffset());
		$this->rom->save();
	}
	
	protected function getTeamOffset($offset) {
		if ($offset >= $this->rom->getHitterStart() && $offset <= $this->rom->getHitterEnd()) {
			// this must be a hitter
			$start = $this->rom->getHitterStart();
		} else {
			$start = $this->rom->getPitcherStart();
		}
		$playeroffset = ($offset - $start) / $this->offsetSize;
		$teamoffset = floor($playeroffset / 14) + 1;
		return $teamoffset;
	}

	protected function getPlayerHex($offset) {
		return $this->rom->getHexString($offset, $this->offsetSize);
	}

	protected function getLineupNumberFromHex($playerhex) {
		return $this->hexToDec(substr($playerhex, 0, 2));
	}

	protected function getNameFromHex($playerhex) {
		$name = "";
		//TODO: make this less ugly
		$lookuptable = $this->rom->getNameHexToChar();

		$name = $lookuptable[substr($playerhex, 2, 2)];
		$name .= $lookuptable[substr($playerhex, 4, 2)];
		$name .= $lookuptable[substr($playerhex, 6, 2)];
		$name .= $lookuptable[substr($playerhex, 8, 2)];
		$name .= $lookuptable[substr($playerhex, 10, 2)];
		$name .= $lookuptable[substr($playerhex, 12, 2)];
		$name .= $lookuptable[substr($playerhex, 32, 2)];
		$name .= $lookuptable[substr($playerhex, 34, 2)];

		return $name;
	}
	protected function getHexArrayFromName($name, $maxlength) {
		$hex = array();
		
		// we want the name to be exactly $maxlength characters long, so we fill it with single spaces if needed
		$name = str_pad($name, $maxlength, ' ');
		$name = substr($name, 0, $maxlength);
		//TODO: make this less ugly
		$lookuptable = $this->rom->getNameCharToHex();

		for($i = 0; $i < strlen($name); $i++) {
			$hex[$i] = $lookuptable[$name[$i]];
		}

		return $hex;
	}

	public function writeToRom(Player $player) {
		$this->rom->setHexString($this->getPlayerHex($offset));
		$this->rom->save();
	}

	protected function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

	protected function decToHex($dec) {
		return str_pad(base_convert($dec, 10, 16), 2, "0", STR_PAD_LEFT);
	}

	protected function valid($playerhex) {
		return strlen($playerhex) == 36 && $playerhex != "000000000000000000000000000000000000";
	}

	public function getPlayersByTeam($team) {
		$players = new PlayerCollection();
		foreach ($this->getAllPlayers() as $player) {
			if ($player->getTeam()->getOffset() == $team->getOffset()) {
				$players->addPlayer($player);
			}
		}
		return $players;
	}

	public function getAllPlayers() {
		if (isset($this->allPlayersCollection)) {
			return $this->allPlayersCollection;
		}

		$players = new PlayerCollection();
		foreach (array('hitter', 'pitcher') as $playertype) {
			switch ($playertype) {
				case 'hitter':
					$start = $this->rom->getHitterStart();
					$end = $this->rom->getHitterEnd();
					//$onEachTeam = 14;
					break;
				case 'pitcher':
					$start = $this->rom->getPitcherStart();
					$end = $this->rom->getPitcherEnd();
					//$onEachTeam = 10;
					break;
				default:
					throw new Exception('invalid playertype');
			}
			for ($offset = $start; $offset < $end; $offset += 36) {

				$playeroffset = ($offset - $start) / 36;
				//$teamoffset = floor($playeroffset / $onEachTeam);
				$teamoffset = floor($playeroffset / 14);

				if ($teamoffset == 29 || $teamoffset == 30) {
					continue;
				}
//				$player = new Player($this, $offset);
				if ($playertype == 'hitter') {
					$mapper = new HitterROMMapper($this->rom);
				} else {
					$mapper = new PitcherROMMapper($this->rom);
				}
				$player = $mapper->get($offset);
				if (!$player) {
					continue;
				}
				//$player->setTeam($this->teams[$teamoffset + 1]);
				$players->addPlayer($player);
			}
		}
		$this->allPlayersCollection = $players;
		return $players;
	}

}
