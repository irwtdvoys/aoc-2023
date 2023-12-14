<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\PointOfIncidence\Direction;
	use App\PointOfIncidence\Pattern;
	use Bolt\Strings;
	use Exception;

	class PointOfIncidence extends Helper
	{
		/** @var Pattern[] */
		public array $patterns;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);


			$this->patterns = array_map(
				function ($element)
				{
					return new Pattern($element);
				},
				explode(PHP_EOL . PHP_EOL, $raw)
			);
		}

		private function calculateStrings($string, $index): array
		{
			$left = substr($string, 0, $index);
			$right = substr($string, $index);

			$lengthLeft = strlen($left);
			$lengthRight = strlen($right);

			if ($lengthLeft < $lengthRight)
			{
				$right = substr($right, 0, $lengthLeft);
			}
			elseif ($lengthLeft > $lengthRight)
			{
				$left = substr($left, -$lengthRight);
			}

			return [
				$left,
				$right
			];
		}

		private function getReflectionPoints(string $string): array
		{
			$result = [];

			for ($index = 1; $index < strlen($string); $index++)
			{
				[$left, $right] = $this->calculateStrings($string, $index);

				$isMirrored = $left === strrev($right);

				if ($isMirrored)
				{
					$result[] = $index;
				}

				if ($this->verbose)
				{
					$this->output("[" . $index . "] " . $left . " " . $right . " " . ($isMirrored ? "true" : "false"));
				}
			}

			return $result;
		}

		private function processPattern(array $lines, bool $includeSmudges = false): int|false
		{
			$points = [];

			foreach ($lines as $line)
			{
				$points[] = $this->getReflectionPoints($line);
			}

			$common = array_intersect(...$points);

			if (!$includeSmudges)
			{

				return match (count($common))
				{
					0 => false,
					1 => array_pop($common),
					default => throw new Exception("Multiple reflection points found"),
				};
			}

			$width = strlen($lines[0]);
			$height = count($lines);
			$counts = array_fill(0, $width, 0);

			for ($index = 0; $index < $width; $index++)
			{
				foreach ($points as $next)
				{
					if (in_array($index, $next))
					{
						$counts[$index]++;
					}
				}
			}

			$possible = array_keys(
				array_filter(
					$counts,
					function ($element) use ($height)
					{
						return $element === $height - 1;
					}
				)
			);

			if (count($possible) === 0)
			{
				return false;
			}


			foreach ($possible as $value)
			{
				$tmp = array_keys(
					array_filter(
						$points,
						function ($element) use ($value) {
							return !in_array($value, $element);
						}
					)
				);

				[$left, $right] = $this->calculateStrings($lines[$tmp[0]], $value);

				$diff = Strings::diff($left, strrev($right));

				if (count($diff) === 1)
				{
					return $value;
				}
			}

			throw new Exception("Multiple valid smudges found");
		}

		private function process(bool $useSmudges = false)
		{
			$result = 0;

			foreach ($this->patterns as $pattern)
			{
				$index = $this->processPattern($pattern->fetch(Direction::HORIZONTAL), $useSmudges);

				if ($index !== false)
				{
					$result += $index;
				}
				else
				{
					$index = $this->processPattern($pattern->fetch(Direction::VERTICAL), $useSmudges);

					if ($index !== false)
					{
						$result += 100 * $index;
					}
				}

				if ($index === false)
				{
					die("NONE FOUND" . PHP_EOL);
				}
			}

			return $result;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$result->part1 = $this->process();
			$result->part2 = $this->process(true);

			return $result;
		}
	}
?>
