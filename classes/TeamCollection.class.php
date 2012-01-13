<?php

/**
 * Description of TeamCollection
 *
 * @author Peter Meth
 */
class TeamCollection implements Countable, Iterator {

	protected $teams;
	protected $index;

	function __construct() {
		$this->teams = array();
		$this->index = 0;
	}

	public function count() {
		return count($this->teams);
	}

	public function getTeams() {
		return $this->teams;
	}

	public function addTeam(Team $team) {
		$this->teams[] = $team;
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
		return $this->teams[$this->index];
	}

	public function rewind() {
		$this->index = 0;
	}

	public function valid() {
		return isset($this->teams[$this->index]);
	}

	public function toHTMLTable() {

		$return = "
			<table border='1'>
			<tr>
				<th>Offset</th>
				<th>Abbr</th>
				<th>Name</th>
			</tr>
		";

		$this->rewind();
		while($this->valid()) {
			$team = $this->current();
			$return .= "
				<tr
					onclick='window.location=\"team.view.php?offset=" . $team->getOffset() . "\";'
					onmouseover='this.style.backgroundColor=\"green\"; this.style.cursor=\"pointer\";'
					onmouseout='this.style.backgroundColor=\"transparent\";'
				>
					<td>" . $team->getOffset() . "</td>
					<td>" . $team->getAbbreviation() . "</td>
					<td>" . $team->getName() . "</td>
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


