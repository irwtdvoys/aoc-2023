<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\WaitForIt\Race;
	use Bolt\Maths;

	class WaitForIt extends Helper
	{
		/** @var Race[] */
		public array $races;
		public Race $final;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			[$timeData, $distanceData] = explode(PHP_EOL, $raw);
			preg_match_all("/\d+/", $timeData, $matches);
			$times = array_map("intval", $matches[0]);

			preg_match_all("/\d+/", $distanceData, $matches);
			$distances = array_map("intval", $matches[0]);

			for ($index = 0; $index < count($times); $index++)
			{
				$this->races[] = new Race($times[$index], $distances[$index]);
			}

			$this->final = new Race((int)implode("", $times), (int)implode("", $distances));
		}

		public function race(Race $race): int
		{
			$min = 0;
			$max = 0;

			// Find first winning distance
			for ($x = 0; $x <= $race->time; $x++)
			{
				$distance = $x * ($race->time - $x);

				if ($distance > $race->distance)
				{
					$min = $x;
					break;
				}
			}

			// Find last winning distance
			for ($x = $race->time; $x >= 0; $x--)
			{
				$distance = $x * ($race->time - $x);

				if ($distance > $race->distance)
				{
					$max = $x;
					break;
				}
			}

			return $max - $min + 1;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$wins = [];

			foreach ($this->races as $race)
			{
				$wins[] = $this->race($race);
			}

			$result->part1 = array_product($wins);
			$result->part2 = $this->race($this->final);

			return $result;
		}
	}
?>
