<?php

class BaseConvert {
	public function hexToDec($hex) {
		return base_convert($hex, 16, 10);
	}

}