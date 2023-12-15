<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class GearRatios extends Helper
	{
		/** @var string[][] */
		private array $data;
		/** @var string[] */
		private array $symbols;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->symbols = array_unique(str_split(preg_replace("/[0-9\.\n]/", "", $raw)));

			$lines = explode(PHP_EOL, $raw);

			for ($y = 0; $y < count($lines); $y++)
			{
				$cells = str_split($lines[$y]);

				for ($x = 0; $x < count($cells); $x++)
				{
					$this->data[$y][$x] = $cells[$x];
				}
			}
		}

		private function check(int $x, int $y): array
		{
			$result = [];

			for ($xAdjust = -1; $xAdjust <= 1; $xAdjust++)
			{
				for ($yAdjust = -1; $yAdjust <= 1; $yAdjust++)
				{
					if ($xAdjust === 0 && $yAdjust === 0)
					{
						continue;
					}

					if (is_numeric($this->data[$y + $yAdjust][$x + $xAdjust]))
					{
						list($key, $value) = $this->findNumber(($x + $xAdjust), ($y + $yAdjust));
						$result[$key] = $value;
					}
				}
			}

			if ($this->verbose)
			{
				$this->output("Found: " . implode(", ", array_values($result)));
			}

			return $result;
		}

		private function findNumber(int $x, int $y): array
		{
			$currentX = $x;

			while (isset($this->data[$y][$currentX]) && is_numeric($this->data[$y][$currentX]))
			{
				$currentX--;
			}

			$currentX++;

			$result = "";
			$startX = $currentX;

			while (isset($this->data[$y][$currentX]) && is_numeric($this->data[$y][$currentX]))
			{
				$result .= $this->data[$y][$currentX];
				$currentX++;
			}

			return [$startX . "," . $y, (int)$result];
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$partNumbers = [];

			for ($y = 0; $y < count($this->data); $y++)
			{
				for ($x = 0; $x < count($this->data[$y]); $x++)
				{
					if (in_array($this->data[$y][$x], $this->symbols))
					{
						if ($this->verbose)
						{
							$this->output("Found '" . $this->data[$y][$x] . "' [" . $x . "," . $y . "]");
						}

						$numbers = $this->check($x, $y);
						$partNumbers = array_merge($partNumbers, $numbers);

						if ($this->data[$y][$x] === "*" && count($numbers) === 2)
						{
							$gearRatio = array_product($numbers);
							$result->part2 += $gearRatio;

							if ($this->verbose)
							{
								$this->output("Gear Ratio: " . $gearRatio);
							}
						}
					}
				}
			}

			$result->part1 = array_sum($partNumbers);

			return $result;
		}
	}
?>
