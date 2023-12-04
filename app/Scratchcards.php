<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\Scratchcards\Card;

	class Scratchcards extends Helper
	{
		/** @var Card[] */
		public array $cards;
		/** @var int[] */
		public array $counts;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);
			$lines = explode(PHP_EOL, $raw);

			foreach ($lines as $next)
			{
				$card = new Card($next);
				$this->cards[$card->id] = $card;
				$this->counts[$card->id] = 1;
			}
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->counts as $key => $count)
			{
				$score = $this->cards[$key]->score();
				$result->part1 += $score;

				if ($score > 0)
				{
					$matches = count($this->cards[$key]->matches());

					for ($index = 1; $index <= $matches; $index++)
					{
						$this->counts[$key + $index] += $this->counts[$key];
					}
				}
			}

			$result->part2 = array_sum($this->counts);

			return $result;
		}
	}
?>
