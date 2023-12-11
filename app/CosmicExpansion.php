<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Dimensions2d;
	use AoC\Utils\Position2d;

	class CosmicExpansion extends Helper
	{
		/** @var Position2d[] */
		public array $galaxies;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$lines = explode(PHP_EOL, $raw);

			for ($y = 0; $y < count($lines); $y++)
			{
				$line = str_split($lines[$y]);

				for ($x = 0; $x < count($line); $x++)
				{
					$value = $line[$x];

					if ($value === "#")
					{
						$this->galaxies[] = new Position2d($x, $y);
					}
				}
			}
		}

		private function getRanges(array $galaxies): Dimensions2d
		{
			$result = new Dimensions2d();

			foreach ($galaxies as $galaxy)
			{
				$result->x->add($galaxy->x);
				$result->y->add($galaxy->y);
			}

			return $result;
		}


		private function expand(int $value = 1): array
		{
			$ranges = $this->getRanges($this->galaxies);

			$countsX = array_fill($ranges->x->min, $ranges->x->count(), true);
			$countsY = array_fill($ranges->y->min, $ranges->y->count(), true);

			foreach ($this->galaxies as $galaxy)
			{
				$countsX[$galaxy->x] = null;
				$countsY[$galaxy->y] = null;
			}

			$filteredX = array_keys(
				array_filter(
					$countsX
				)
			);

			$filteredY = array_keys(
				array_filter(
					$countsY
				)
			);

			$galaxies = [];

			foreach ($this->galaxies as $galaxy)
			{
				$adjustmentX = 0;

				foreach ($filteredX as $next)
				{
					if ($galaxy->x > $next)
					{
						$adjustmentX += $value;
					}
				}

				$adjustmentY = 0;

				foreach ($filteredY as $next)
				{
					if ($galaxy->y > $next)
					{
						$adjustmentY += $value;
					}
				}

				$galaxies[] = new Position2d($galaxy->x + $adjustmentX, $galaxy->y + $adjustmentY);
			}

			return $galaxies;
		}

		private function draw(array $galaxies): void
		{
			$index = [];
			$ranges = $this->getRanges($galaxies);

			foreach ($galaxies as $galaxy)
			{
				$index[$galaxy->x . "," . $galaxy->y] = $galaxy;
			}

			for ($y = $ranges->y->min; $y < $ranges->y->count(); $y++)
			{
				$output = "";

				for ($x = $ranges->x->min; $x < $ranges->x->count(); $x++)
				{
					$output .= isset($index[$x . "," . $y]) ? "#" : ".";
				}

				$this->output($output);
			}

			$this->output("");
		}

		private function calculate(array $galaxies): int
		{
			$result = 0;

			while (count($galaxies) > 1)
			{
				$current = array_shift($galaxies);

				foreach ($galaxies as $next)
				{
					$result += $this->manhattanDistance($current, $next);
				}
			}

			return $result;
		}

		private function manhattanDistance(Position2d $a, Position2d $b): int
		{
			return abs($a->x - $b->x) + abs($a->y - $b->y);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			if ($this->verbose)
			{
				$this->draw($this->galaxies);
			}

			$galaxies = $this->expand();

			if ($this->verbose)
			{
				$this->draw($galaxies);
			}

			$result->part1 = $this->calculate($galaxies);

			$galaxies = $this->expand(999999);
			$result->part2 = $this->calculate($galaxies);

			return $result;
		}
	}
?>
