<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class MirageMaintenance extends Helper
	{
		/** @var string[] */
		public array $lines;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->lines = array_map(
				function ($element)
				{
					return array_map("intval", explode(" ", $element));
				},
				explode(PHP_EOL, $raw)
			);
		}

		private function draw(array $rows): void
		{
			foreach ($rows as $numbers)
			{
				$this->output(implode(" ", $numbers));
			}
		}

		private function process(array $line): array
		{
			$isComplete = false;
			$current = $line;
			$lines = [$current];

			while (!$isComplete)
			{
				$new = [];

				for ($index = 0; $index < count($current) - 1; $index++)
				{
					$new[] = $current[$index + 1] - $current[$index];
				}

				if (array_keys(array_count_values($new)) === [0])
				{
					$isComplete = true;
					$new[] = 0;
				}

				$lines[] = $new;
				$current = $new;
			}

			for ($index = count($lines) - 1; $index > 0; $index--)
			{
				$lines[$index - 1][] = end($lines[$index - 1]) + end($lines[$index]);
			}

			return $lines;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->lines as $line)
			{
				$lines = $this->process($line);

				$result->part1 += end($lines[0]);

				if ($this->verbose)
				{
					$this->draw($lines);
					$this->output("");
				}
			}

			foreach ($this->lines as $line)
			{
				$lines = $this->process(array_reverse($line));

				$result->part2 += end($lines[0]);

				if ($this->verbose)
				{
					$this->draw($lines);
					$this->output("");
				}
			}

			return $result;
		}
	}
?>
