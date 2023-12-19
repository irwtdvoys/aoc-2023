<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use App\TheFloorWillBeLava\Beam;
	use App\TheFloorWillBeLava\Direction;
	use App\TheFloorWillBeLava\Floor;
	use App\TheFloorWillBeLava\Tiles;

	class TheFloorWillBeLava extends Helper
	{
		public Floor $floor;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$this->floor = new Floor(parent::load($override));
		}

		private function trace(Beam $original): int
		{
			$beams = [$original];

			while (count($beams) > 0)
			{
				$beam = array_pop($beams);

				$x = $beam->origin->x;
				$y = $beam->origin->y;

				while (true)
				{
					// Target is outside region
					if (!isset($this->floor->data[$y][$x]))
					{
						break;
					}

					// Target has been visited before in this direction
					$stop = $this->floor->data[$y][$x]->visit($beam->direction);

					if ($stop === false)
					{
						break;
					}

					switch ($this->floor->data[$y][$x]->tile)
					{
						case Tiles::SPLITTER_VERTICAL:
							if ($beam->direction === Direction::RIGHT || $beam->direction === Direction::LEFT)
							{
								$beams[] = new Beam(
									new Position2d($x, $y - 1),
									Direction::UP
								);
								$beams[] = new Beam(
									new Position2d($x, $y + 1),
									Direction::DOWN
								);

								break 2;
							}

							break;
						case Tiles::SPLITTER_HORIZONTAL:
							if ($beam->direction === Direction::UP || $beam->direction === Direction::DOWN)
							{
								$beams[] = new Beam(
									new Position2d($x - 1, $y),
									Direction::LEFT
								);
								$beams[] = new Beam(
									new Position2d($x + 1, $y),
									Direction::RIGHT
								);

								break 2;
							}

							break;
						case Tiles::MIRROR_FORWARD:
							switch ($beam->direction)
							{
								case Direction::UP:
									$beams[] = new Beam(
										new Position2d($x + 1, $y),
										Direction::RIGHT
									);
									break;
								case Direction::RIGHT:
									$beams[] = new Beam(
										new Position2d($x, $y - 1),
										Direction::UP
									);
									break;
								case Direction::DOWN:
									$beams[] = new Beam(
										new Position2d($x - 1, $y),
										Direction::LEFT
									);
									break;
								case Direction::LEFT:
									$beams[] = new Beam(
										new Position2d($x, $y + 1),
										Direction::DOWN
									);
									break;
							}
							break 2;
						case Tiles::MIRROR_BACKWARD:
							switch ($beam->direction)
							{
								case Direction::UP:
									$beams[] = new Beam(
										new Position2d($x - 1, $y),
										Direction::LEFT
									);
									break;
								case Direction::RIGHT:
									$beams[] = new Beam(
										new Position2d($x, $y + 1),
										Direction::DOWN
									);
									break;
								case Direction::DOWN:
									$beams[] = new Beam(
										new Position2d($x + 1, $y),
										Direction::RIGHT
									);
									break;
								case Direction::LEFT:
									$beams[] = new Beam(
										new Position2d($x, $y - 1),
										Direction::UP
									);
									break;
							}

							break 2;
					}

					switch ($beam->direction)
					{
						case Direction::UP:
							$y--;
							break;
						case Direction::RIGHT:
							$x++;
							break;
						case Direction::DOWN:
							$y++;
							break;
						case Direction::LEFT:
							$x--;
							break;
					}
				}
			}

			$energy = $this->floor->energized();

			if ($this->verbose)
			{
				$this->output($original . " " . $energy);
				$this->floor->draw(true);
			}

			$this->floor->reset();

			return $energy;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			if ($this->verbose)
			{
				$this->floor->draw();
			}

			$result->part1 = $this->trace(new Beam(
				new Position2d(0, 0),
				Direction::RIGHT
			));

			$this->floor->reset();

			for ($y = 0; $y < count($this->floor->data); $y++)
			{
				$result->part2 = max(
					$result->part2,
					$this->trace(new Beam(
						new Position2d(0, $y),
						Direction::RIGHT
					))
				);

				$result->part2 = max(
					$result->part2,
					$this->trace(new Beam(
						new Position2d(count($this->floor->data[0]) - 1, $y),
						Direction::LEFT
					))
				);
			}

			for ($x = 0; $x < count($this->floor->data[0]); $x++)
			{
				$result->part2 = max(
					$result->part2,
					$this->trace(new Beam(
						new Position2d($x, 0),
						Direction::DOWN
					))
				);

				$result->part2 = max(
					$result->part2,
					$this->trace(new Beam(
						new Position2d($x, count($this->floor->data) - 1),
						Direction::UP
					))
				);
			}

			return $result;
		}
	}
?>
