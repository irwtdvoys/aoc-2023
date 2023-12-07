<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\CamelCards\Hand;

	class CamelCards extends Helper
	{
		/** @var Hand[] */
		public array $hands;
		public bool $wildcards = false;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->hands = array_map(
				function ($element)
				{
					return new Hand($element);
				},
				explode(PHP_EOL, $raw)
			);
		}

		public function sort(Hand $a, Hand $b): int
		{
			return $a->value($this->wildcards) <=> $b->value($this->wildcards);
		}

		private function score(): int
		{
			$result = 0;

			foreach ($this->hands as $key => $value)
			{
				$result += $value->bid * ($key + 1);

				if ($this->verbose)
				{
					$this->output("[$key] " . $value . " (" . $value->bid . ") " . $value->value($this->wildcards) . " " . $value->type($this->wildcards)->name . "|" . $value->strength($this->wildcards));
				}
			}

			return $result;
		}

		public function execute(bool $wildcards = false): int
		{
			$this->wildcards = $wildcards;
			usort($this->hands, [$this, "sort"]);
			return $this->score();
		}

		public function run(): Result
		{
			return new Result(
				$this->execute(),
				$this->execute(true)
			);
		}
	}
?>
