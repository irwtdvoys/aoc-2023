<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Range;
	use App\IfYouGiveASeedAFertilizer\Map;

	class IfYouGiveASeedAFertilizer extends Helper
	{
		/** @var int[] */
		public array $seeds;
		/** @var Map[] */
		public array $maps;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$sections = explode(PHP_EOL . PHP_EOL, $raw);

			$this->seeds = array_map("intval", explode(" ", substr(array_shift($sections), 7)));

			$this->maps = array_map(
				function ($element)
				{
					return new Map($element);
				},
				$sections
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$locations = [];

			foreach ($this->seeds as $seed)
			{
				$current = $seed;

				if ($this->verbose)
				{
					$this->output("seed " . $seed);
				}

				foreach ($this->maps as $map)
				{
					$current = $map->adjust($current);

					if ($this->verbose)
					{
						$this->output($map->to . " " . $current);
					}
				}

				$locations[] = $current;
			}

			$result->part1 = min($locations);

			$seedBlocks = array_map(
				function ($element)
				{
					return new Range($element[0], $element[0] + $element[1] - 1);
				},
				array_chunk($this->seeds, 2)
			);

			$maps = array_reverse($this->maps);

			for ($location = 0; $location < 1000000000; $location++)
			{
				$current = $location;

				foreach ($maps as $map)
				{
					$current = $map->reverse($current);

					if ($this->verbose)
					{
						$this->output($map->from . " " . $current);
					}
				}

				// Check against seed ranges
				foreach ($seedBlocks as $block)
				{
					if ($block->contains($current))
					{
						$result->part2 = $location;
						break 2;
					}
				}
			}

			return $result;
		}
	}
?>
