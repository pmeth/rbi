<?php

/**
 * Description of RBI3AndyBRom
 *
 * @author Peter Meth
 */

namespace Pmeth\RBI;

class RBI30TeamRom extends RBI3Rom {

   function __construct($filename) {
      parent::__construct($filename);
      $this->romType = 'RBI 30 Team';
      $this->hexOffsets = array(
          'hitterstart' => '6010',
          'hitterend' => '7e0f',
          'pitcherstart' => '6010',
          'pitcherend' => '7e0f',
          'teamstart' => '9e1d',
          'teamend' => '9e5d',
          'era1start' => '19d88',
          'era1end' => '19e88',
          'era2start' => '19f48',
      );
      $this->offsets = array();
      foreach ($this->hexOffsets as $key => $value) {
         $this->offsets[$key] = $this->offsetHexToDec($value);
      }

      $this->playerSize = 32;
      $this->maxPlayerNameLength = 6;
      $this->hittersPerTeam = 12;
      $this->pitchersPerTeam = 4;
   }

}
