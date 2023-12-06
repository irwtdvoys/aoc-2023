<?php
	namespace App\WaitForIt;

	class Race
	{
		public function __construct(
			public int $time,
			public int $distance
		) {}
	}
?>