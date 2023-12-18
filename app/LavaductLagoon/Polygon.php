<?php
	declare(strict_types=1);

	namespace App\LavaductLagoon;

	use AoC\Utils\Position2d;

	class Polygon
	{
		/** @var Position2d[] */
		public array $vertices;

		public function __construct(array $vertices)
		{
			$this->vertices = $vertices;
		}

		public function area(): int
		{
			$perimeter = 0;
			$area = 0;
			$numberOfVertices = count($this->vertices);

			$j = $numberOfVertices - 1;

			for ($i = 0; $i < $numberOfVertices; $i++)
			{
				$perimeter += abs(($this->vertices[$j]->x - $this->vertices[$i]->x) + ($this->vertices[$j]->y - $this->vertices[$i]->y));
				$area += ($this->vertices[$j]->x + $this->vertices[$i]->x) * ($this->vertices[$j]->y - $this->vertices[$i]->y);

				// j is previous vertex to i
				$j = $i;
			}

			return (abs($area) + $perimeter) / 2 + 1;
		}

		public function __toString(): string
		{
			$result = "";

			foreach ($this->vertices as $vertex)
			{
				$result .= $vertex . PHP_EOL;
			}

			return $result;
		}
	}
?>
