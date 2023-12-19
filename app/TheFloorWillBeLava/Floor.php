<?php
	declare(strict_types=1);

	namespace App\TheFloorWillBeLava;

	class Floor
	{
		/** @var Cell[][] */
		public array $data;

		public function __construct(string $data)
		{
			$lines = explode(PHP_EOL, $data);

			for ($y = 0; $y < count($lines); $y++)
			{
				$line = str_split($lines[$y]);

				for ($x = 0; $x < count($line); $x++)
				{
					$this->data[$y][$x] = new Cell(Tiles::from($line[$x]));
				}
			}
		}

		public function draw(bool $beams = false, bool $energized = false): void
		{
			for ($y = 0; $y < count($this->data); $y++)
			{
				$tmp = array_map(
					function ($element) use ($beams, $energized)
					{
						if ($element->tile === Tiles::EMPTY_SPACE && $beams)
						{
							switch (count($element->visited))
							{
								case 0:
									return Tiles::EMPTY_SPACE->value;
								case 1:
									return $element->visited[0]->value;
								default:
									return count($element->visited);
							}
						}

						if ($energized)
						{
							return $element->isEnergized() ? Tiles::ENERGIZED->value : Tiles::EMPTY_SPACE->value;
						}

						return $element->tile->value;
					},
					$this->data[$y]
				);
				echo(implode("", $tmp) . PHP_EOL);
			}

			echo(PHP_EOL);
		}

		public function energized(): int
		{
			$count = 0;

			for ($y = 0; $y < count($this->data); $y++)
			{
				for ($x = 0; $x < count($this->data[$y]); $x++)
				{
					if ($this->data[$y][$x]->isEnergized())
					{
						$count++;
					}
				}
			}

			return $count;
		}

		public function reset(): void
		{
			for ($y = 0; $y < count($this->data); $y++)
			{
				for ($x = 0; $x < count($this->data[$y]); $x++)
				{
					$this->data[$y][$x]->reset();
				}
			}
		}
	}
?>
