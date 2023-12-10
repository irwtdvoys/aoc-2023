<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use AoC\Utils\Styling;

	class PipeMaze extends Helper
	{
		/** @var string[][] */
		public array $maze;
		/** @var bool[] */
		public array $path;
		/** @var bool[] */
		public array $inside;
		public Position2d $start;

		public array $options = [
			"S" => [self::UP, self::RIGHT, self::DOWN, self::LEFT],
			"F" => [self::RIGHT, self::DOWN],
			"-" => [self::RIGHT, self::LEFT],
			"7" => [self::DOWN, self::LEFT],
			"|" => [self::UP, self::DOWN],
			"J" => [self::UP, self::LEFT],
			"L" => [self::UP, self::RIGHT]
		];

		const UP = ["F", "7", "|"];
		const RIGHT = ["-", "7", "J"];
		const DOWN = ["|", "J", "L"];
		const LEFT = ["F", "-", "L"];

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
					$this->maze[$y][$x] = $value;

					if ($value === "S")
					{
						$this->start = new Position2d($x, $y);
						$this->path[$x . "," . $y] = true;
					}
				}
			}
		}

		private function calculatePipe(): string
		{
			$matches = [];

			foreach ($this->options["S"] as $option)
			{
				$tmp = new Position2d($this->start->x, $this->start->y);

				switch ($option)
				{
					case self::UP:
						$tmp->y--;
						break;
					case self::RIGHT:
						$tmp->x++;
						break;
					case self::DOWN:
						$tmp->y++;
						break;
					case self::LEFT:
						$tmp->x--;
						break;
				}

				if (isset($this->maze[$tmp->y][$tmp->x]) && in_array($this->maze[$tmp->y][$tmp->x], $option) && isset($this->path[$tmp->x . "," . $tmp->y]))
				{
					$matches[] = $option;
				}
			}

			foreach ($this->options as $key => $options)
			{
				if ($options === $matches)
				{
					return (string)$key;
				}
			}

			return "S";
		}

		private function boxReplacement(string $string): string
		{
			return str_replace(
				[
					"-",
					"|",
					"7",
					"J",
					"L",
					"F",
					"."
				],
				[
					"─",
					"│",
					"┐",
					"┘",
					"└",
					"┌",
					" "
				],
				$string
			);
		}

		private function draw(): void
		{
			for ($y = 0; $y < count($this->maze); $y++)
			{
				$output = "";

				for ($x = 0; $x < count($this->maze[$y]); $x++)
				{
					$value = $this->maze[$y][$x];

					if ($this->start->x === $x && $this->start->y === $y)
					{
						$value = Styling::format([Styling::BG_BLUE], $value);
					}
					elseif (isset($this->path[$x . "," . $y]))
					{
						$value = Styling::format([Styling::BG_RED], $value);
					}
					elseif (isset($this->inside[$x . "," . $y]))
					{
						$value = Styling::format([Styling::BG_GREEN], $value);
					}

					$output .= $value;
				}

				$this->output($this->boxReplacement($output));
			}

			$this->output("");
		}

		private function check(int $x, int $y, array $options): bool
		{
			return isset($this->maze[$y][$x])
				&& in_array($this->maze[$y][$x], $options)
				&& !isset($this->path[$x . "," . $y]);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$steps = 0;
			$current = new Position2d($this->start->x, $this->start->y);

			while (true)
			{
				$found = false;

				foreach ($this->options[$this->maze[$current->y][$current->x]] as $option)
				{
					$tmp = new Position2d($current->x, $current->y);

					switch ($option)
					{
						case self::UP:
							$tmp->y--;
							break;
						case self::RIGHT:
							$tmp->x++;
							break;
						case self::DOWN:
							$tmp->y++;
							break;
						case self::LEFT:
							$tmp->x--;
							break;
					}

					$check = $this->check($tmp->x, $tmp->y, $option);

					if ($check)
					{
						$this->path[$tmp->x . "," . $tmp->y] = true;
						$current = $tmp;
						$steps++;
						$found = true;
						break;
					}
				}

				if ($found === false)
				{
					$steps++;
					break;
				}
			}

			$result->part1 = $steps / 2;

			$this->maze[$this->start->y][$this->start->x] = $this->calculatePipe();

			for ($y = 0; $y < count($this->maze); $y++)
			{
				for ($x = 0; $x < count($this->maze[$y]); $x++)
				{
					if (!isset($this->path[$x . "," . $y]))
					{
						$count = 0;

						for ($dx = 0; $dx < $x; $dx++)
						{
							if (isset($this->path[$dx . "," . $y]) && in_array($this->maze[$y][$dx], ["|", "J", "L"]))
							{
								$count++;
							}
						}

						if ($count % 2 === 1)
						{
							$this->inside[$dx . "," . $y] = true;
							$result->part2++;
						}
					}
				}
			}

			if ($this->verbose)
			{
				$this->draw();
			}

			return $result;
		}
	}
?>
