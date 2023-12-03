<?php
	declare(strict_types=1);

	namespace App\CubeConundrum;

	class Game
	{
		public int $number;
		/** @var Hand[] */
		public array $hands;

		public function __construct(string $data)
		{
			list($game, $hands) = explode(": ", $data);
			$this->number = (int)substr($game, 5);

			$this->hands = array_map(
				function($element)
				{
					return new Hand($element);
				},
				explode("; ", $hands)
			);
		}

		public function max(string $colour): int
		{
			$result = 0;

			foreach ($this->hands as $hand)
			{
				if (isset($hand->cubes[$colour]))
				{
					$result = max($result, $hand->cubes[$colour]);
				}
			}

			return $result;
		}

		public function count(string $colour): int
		{
			$result = 0;

			foreach ($this->hands as $hand)
			{
				if (isset($hand->cubes[$colour]))
				{
					$result += $hand->cubes[$colour];
				}
			}

			return $result;
		}

		public function power(): int
		{
			return $this->max("red") * $this->max("green") * $this->max("blue");
		}

		public function __toString(): string
		{
			$hands = [];

			foreach ($this->hands as $hand)
			{
				$hands[] = (string)$hand;
			}

			return "Game " . $this->number . ": " . implode("; ", $hands);
		}
	}
?>
