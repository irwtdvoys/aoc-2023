<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\HotSprings\Record;

	class HotSprings extends Helper
	{
		/** @var Record[] */
		public array $records;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->records = array_map(
				function ($element)
				{
					return new Record($element);
				},
				explode(PHP_EOL, $raw)
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->records as $record)
			{
				$count = $record->countArrangements();
				$result->part1 += $count;

				if ($this->verbose)
				{
					$this->output($record . " " . $count);
				}
			}

			foreach ($this->records as $record)
			{
				$record->unfold();
				$count = $record->countArrangements();
				$result->part2 += $count;

				if ($this->verbose)
				{
					$this->output($record . " " . $count);
				}
			}

			return $result;
		}
	}
?>
