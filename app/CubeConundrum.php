<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\CubeConundrum\Game;

	class CubeConundrum extends Helper
	{
		/** @var Game[] */
		public array $games;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->games = array_map(
				function($element)
				{
					return new Game($element);
				},
				explode(PHP_EOL, $raw)
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$rules = [
				"red" => 12,
				"green" => 13,
				"blue" => 14
			];

			foreach ($this->games as $game)
			{
				if ($this->verbose)
				{
					$this->output((string)$game);
				}

				$valid = true;

				foreach ($rules as $colour => $total)
				{
					if ($this->verbose)
					{
						$this->output("Max '" . $colour . "' = " . $game->max($colour));
					}

					if ($game->max($colour) > $total)
					{
						$valid = false;
					}
				}

				if ($valid)
				{
					$result->part1 += $game->number;
				}

				$result->part2 += $game->power();

				if ($this->verbose)
				{
					$this->output("Power = " . $game->power() . PHP_EOL);
				}
			}

			return $result;
		}
	}
?>
