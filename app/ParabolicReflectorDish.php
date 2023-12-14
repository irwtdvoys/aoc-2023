<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\ParabolicReflectorDish\Platform;

	class ParabolicReflectorDish extends Helper
	{
		public Platform $platform;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->platform = new Platform($raw);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$hash = $this->platform->hash();

			$cache = [
				$hash => 0
			];

			$this->platform->tiltNorth();
			$result->part1 = $this->platform->score();

			if ($this->verbose)
			{
				$this->platform->draw();
			}

			$cycles = 1000000000;
			$count = 1;

			while ($count <= $cycles)
			{
				$this->platform->cycle();

				if ($this->verbose)
				{
					$this->platform->draw();
				}

				$hash = $this->platform->hash();

				if (isset($cache[$hash]))
				{
					$start = $cache[$hash];
					$period = $count - $cache[$hash];

					$target = $start + ($cycles - $start) % $period;

					$this->platform->restore(array_search($target, $cache));
					$result->part2 = $this->platform->score();
					break;
				}

				$cache[$hash] = $count;

				$count++;

			}

			return $result;
		}
	}
?>
