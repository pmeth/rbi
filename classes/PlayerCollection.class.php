<?php

/**
 * Description of PlayerCollection
 *
 * @author Peter Meth
 */
class PlayerCollection implements Countable, Iterator {

	protected $players;
	protected $index;

	function __construct() {
		$this->players = array();
		$this->index = 0;
	}

	public function count() {
		return count($this->players);
	}

	public function getPlayers() {
		return $this->players;
	}

	public function addPlayer(Player $player) {
		$this->players[] = $player;
	}

	public function key() {
		return $this->index;
	}

	public function next() {
		$this->index++;
	}

	public function prev() {
		$this->index--;
	}

	public function current() {
		return $this->players[$this->index];
	}

	public function rewind() {
		$this->index = 0;
	}

	public function valid() {
		return isset($this->players[$this->index]);
	}

	public function toHTMLTable() {

		$return = "
			<table border='1'>
			<tr>
				<th>Offset</th>
				<th>Team</th>
				<th>Name</th>
				<th>Lineup #</th>
				<th>Type</th>
				<th>Speed</th>
			</tr>
		";

		$this->rewind();
		while($this->valid()) {
			$player = $this->current();
			$speed = $player->getType() == 'hitter' ?  $player->getSpeed() : '';
			$return .= "
				<tr
					onclick='window.location=\"player.view.php?offset=" . $player->getOffset() . "\";'
					onmouseover='this.style.backgroundColor=\"green\"; this.style.cursor=\"pointer\";'
					onmouseout='this.style.backgroundColor=\"transparent\";'
				>
					<td>" . $player->getOffset() . "</td>
					<td>" . $player->getTeam() . "</td>
					<td>" . $player->getName() . "</td>
					<td>" . $player->getLineupNumber() . "</td>
					<td>" . $player->getType() . "</td>
					<td>" . $speed . "</td>
				</tr>
			";
			$this->next();
		}
		$return .= "
			</table>
		";

		return $return;
	}

}


