<?php
	declare(strict_types=1);

	namespace App\ParabolicReflectorDish;

	use AoC\Utils\Grid;

	class Platform extends Grid
	{
		public array $index;

		public function __construct(string $data)
		{
			parent::__construct($data);

			$this->reIndex();
		}

		public function reIndex(): void
		{
			$this->index = [];

			for ($y = 0; $y < count($this->data); $y++)
			{
				for ($x = 0; $x < count($this->data[0]); $x++)
				{
					if ($this->data[$y][$x] === "O")
					{
						$this->index[$x . "," . $y] = true;
					}
				}
			}
		}

		public function canMove(int $x, int $y, Direction $direction = Direction::NORTH): bool
		{
			if ($this->data[$y][$x] !== "O")
			{
				return false;
			}

			switch ($direction)
			{
				case Direction::NORTH:
					$target = $this->data[$y - 1][$x];
					break;
				case Direction::EAST:
					$target = $this->data[$y][$x + 1];
					break;
				case Direction::SOUTH:
					$target = $this->data[$y + 1][$x];
					break;
				case Direction::WEST:
					$target = $this->data[$y][$x - 1];
					break;
			}


			return $target === ".";
		}

		public function score(): int
		{
			$max = count($this->data);
			$score = 0;

			foreach ($this->index as $key => $next)
			{
				[, $y] = explode(",", $key);
				$score += ($max - $y);
			}

			return $score;
		}

		public function maximiseMove(int $x, int $y, Direction $direction): int|false
		{
			switch ($direction)
			{
				case Direction::NORTH:
					$dy = $y - 1;

					while ($dy >= 0)
					{
						if (!isset($this->data[$dy][$x]) || $this->data[$dy][$x] !== ".")
						{
							break;
						}

						$dy--;
					}
					return $dy + 1;

				case Direction::EAST:
					$dx = $x + 1;

					while ($dx <= count($this->data[0]))
					{
						if (!isset($this->data[$y][$dx]) || $this->data[$y][$dx] !== ".")
						{
							break;
						}

						$dx++;
					}
					return $dx - 1;

				case Direction::SOUTH:
					$dy = $y + 1;

					while ($dy <= count($this->data))
					{
						if (!isset($this->data[$dy][$x]) || $this->data[$dy][$x] !== ".")
						{
							break;
						}

						$dy++;
					}
					return $dy - 1;

				case Direction::WEST:
					$dx = $x - 1;

					while ($dx >= 0)
					{
						if (!isset($this->data[$y][$dx]) || $this->data[$y][$dx] !== ".")
						{
							break;
						}

						$dx--;
					}
					return $dx + 1;
			}
		}

		public function tiltNorth(): void
		{
			for ($y = 1; $y < count($this->data); $y++)
			{
				for ($x = 0; $x < count($this->data[0]); $x++)
				{
					if ($this->canMove($x, $y))
					{
						$newY = $this->maximiseMove($x, $y, Direction::NORTH);

						$this->data[$newY][$x] = $this->data[$y][$x];
						$this->data[$y][$x] = ".";

						unset($this->index[$x . "," . $y]);
						$this->index[$x . "," . $newY] = true;
					}
				}
			}
		}

		public function tiltEast(): void
		{
			for ($y = 0; $y < count($this->data); $y++)
			{
				for ($x = count($this->data[0]) - 2; $x >= 0; $x--)
				{
					if ($this->canMove($x, $y, Direction::EAST))
					{
						$newX = $this->maximiseMove($x, $y, Direction::EAST);

						$this->data[$y][$newX] = $this->data[$y][$x];
						$this->data[$y][$x] = ".";

						unset($this->index[$x . "," . $y]);
						$this->index[$newX . "," . $y] = true;
					}
				}
			}
		}

		public function tiltSouth(): void
		{
			for ($y = count($this->data) - 2; $y >= 0; $y--)
			{
				for ($x = 0; $x < count($this->data[0]); $x++)
				{
					if ($this->canMove($x, $y, Direction::SOUTH))
					{
						$newY = $this->maximiseMove($x, $y, Direction::SOUTH);

						$this->data[$newY][$x] = $this->data[$y][$x];
						$this->data[$y][$x] = ".";

						unset($this->index[$x . "," . $y]);
						$this->index[$x . "," . $newY] = true;
					}
				}
			}
		}

		public function tiltWest(): void
		{
			for ($y = 0; $y < count($this->data); $y++)
			{
				for ($x = 1; $x < count($this->data[0]); $x++)
				{
					if ($this->canMove($x, $y, Direction::WEST))
					{
						$newX = $this->maximiseMove($x, $y, Direction::WEST);

						$this->data[$y][$newX] = $this->data[$y][$x];
						$this->data[$y][$x] = ".";

						unset($this->index[$x . "," . $y]);
						$this->index[$newX . "," . $y] = true;
					}
				}
			}
		}

		public function cycle(): void
		{
			$this->tiltNorth();
			$this->tiltWest();
			$this->tiltSouth();
			$this->tiltEast();
		}

		public function hash(): string
		{
			return serialize($this->data);
		}

		public function restore(string $hash): void
		{
			$this->data = unserialize($hash);
			$this->reIndex();
		}
	}
?>
