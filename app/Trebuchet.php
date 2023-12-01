<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class Trebuchet extends Helper
	{
		/** @var string[] */
		private array $lines;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->lines = explode(PHP_EOL, $raw);
		}

		/**
		 * @param string[] $lines
		 */
		private function calculate(array $lines): int
		{
			$result = 0;
			$filtered = preg_replace("/\D/", "", $lines);

			foreach ($filtered as $index => $next)
			{
				$value = (int)(substr($next, 0, 1) . substr($next, -1, 1));
				$result += $value;

				if ($this->verbose)
				{
					echo($lines[$index] . " -> " . $value . PHP_EOL);
				}
			}

			return $result;
		}

		/**
		 * @param string[] $lines
		 */
		private function wordReplace(array $lines): array
		{
			return preg_replace(
				[
					"/one/",
					"/two/",
					"/three/",
					"/four/",
					"/five/",
					"/six/",
					"/seven/",
					"/eight/",
					"/nine/"
				],
				[ // leave first + last letters in replacement to catch `eightwo` shared letter issue
					"o1e",
					"t2o",
					"t3e",
					"f4r",
					"f5e",
					"s6x",
					"s7n",
					"e8t",
					"n9e"
				],
				$lines
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$result->part1 = $this->calculate($this->lines);

			$replaced = $this->wordReplace($this->lines);
			$result->part2 = $this->calculate($replaced);

			return $result;
		}
	}
?>
