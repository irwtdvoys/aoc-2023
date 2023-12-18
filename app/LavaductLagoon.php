<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use App\LavaductLagoon\Direction;
	use App\LavaductLagoon\Polygon;
	use App\LavaductLagoon\Step;

	class LavaductLagoon extends Helper
	{
		public array $plan;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$this->plan = array_map(
				function ($element)
				{
					return new Step($element);
				},
				explode(PHP_EOL, parent::load($override))
			);
		}

		public function dig(): int
		{
			$current = new Position2d();
			$points = [];
			$perimeter = 0;

			foreach ($this->plan as $step)
			{
				$perimeter += $step->distance;

				switch ($step->direction)
				{
					case Direction::UP:
						$x = 0;
						$y = $step->distance;
						break;
					case Direction::RIGHT:
						$x = $step->distance;
						$y = 0;
						break;
					case Direction::DOWN:
						$x = 0;
						$y = -$step->distance;
						break;
					case Direction::LEFT:
						$x = -$step->distance;
						$y = 0;
						break;
				}

				$current = new Position2d($current->x + $x, $current->y + $y);
				$points[] = $current;
			}

			$points = array_reverse($points);
			$polygon = new Polygon($points);

			return $polygon->area();
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$result->part1 = $this->dig();

			foreach ($this->plan as $step)
			{
				$step->convert();
			}

			$result->part2 = $this->dig();

			return $result;
		}
	}
?>
