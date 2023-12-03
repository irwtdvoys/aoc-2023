<?php
	declare(strict_types=1);

	namespace App\CubeConundrum;

	class Hand
	{
		/** @var int[] */
		public array $cubes = [];

		public function __construct(string $data)
		{
			$types = explode(", ", $data);

			foreach ($types as $type)
			{
				list($number, $colour) = explode(" ", $type);

				$this->cubes[$colour] = (int)$number;
			}
		}

		public function __toString(): string
		{
			$colours = [];

			foreach ($this->cubes as $colour => $count)
			{
				$colours[] = $count . " " . $colour;
			}

			return implode(", ", $colours);
		}
	}
?>
