<?php

	namespace App\Scratchcards;

	use Bolt\Maths;

	class Card
	{
		public int $id;
		public array $winning;
		public array $numbers;

		public function __construct($data)
		{
			[$list, $numbers] = explode(":", $data);

			$this->id = (int)substr($list, 4);
			[$winners, $chosen] = explode("|", $numbers);

			$this->winning = array_map(
				"intval",
				array_filter(explode(" ", $winners))
			);

			$this->numbers = array_map(
				"intval",
				array_filter(explode(" ", $chosen))
			);
		}

		public function matches(): array
		{
			return array_intersect($this->winning, $this->numbers);
		}

		public function score(): int
		{
			$count = count($this->matches());

			return $count === 0 ? 0 : Maths::double(1, $count - 1);
		}
	}
?>
