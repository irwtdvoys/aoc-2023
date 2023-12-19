<?php
	declare(strict_types=1);

	namespace App\TheFloorWillBeLava;

	use AoC\Utils\Position2d;

	class Beam
	{
		public function __construct(
			public Position2d $origin,
			public Direction $direction
		)
		{}

		public function __toString(): string
		{
			return $this->origin . " " . $this->direction->name;
		}
	}
?>
