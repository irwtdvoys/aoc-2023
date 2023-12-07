<?php
	declare(strict_types=1);

	namespace App\CamelCards;

	use Exception;

	class Hand
	{
		public array $cards;
		public int $bid;

		const OPTIONS = [2, 3, 4, 5, 6, 7, 8, 9, "T", "J", "Q", "K", "A"];

		public function __construct($data)
		{
			[$cards, $bid] = explode(" ", $data);

			$this->bid = (int)$bid;
			$this->cards = str_split($cards);
		}

		public function __toString(): string
		{
			return implode("", $this->cards);
		}

		public function type(bool $wildcards = false): Type
		{
			$counts = array_count_values($this->cards);
			arsort($counts);

			if ($wildcards && isset($counts["J"]) && $counts["J"] < 5) // 5 jokers already counts as 5oaK so no changes required
			{
				$jokers = $counts["J"];
				unset($counts["J"]);

				// Reassign jokers to most common type
				$counts[array_key_first($counts)] += $jokers;
			}

			return match (count($counts))
			{
				1 => Type::FIVE_OF_A_KIND,
				2 => reset($counts) === 4 ? Type::FOUR_OF_A_KIND : Type::FULL_HOUSE,
				3 => reset($counts) === 3 ? Type::THREE_OF_A_KIND : Type::TWO_PAIR,
				4 => Type::ONE_PAIR,
				5 => Type::HIGH_CARD,
				default => throw new Exception("Unknown hand type '" . implode("", $this->cards) . "'"),
			};
		}

		public function strength(bool $wildcards = false): string
		{
			$options = self::OPTIONS;

			if ($wildcards)
			{
				unset($options[array_search("J", $options)]);
				array_unshift($options, "J");
			}

			$values = array_map(
				function ($element) use ($options)
				{
					$found = array_search($element, $options);
					return base_convert((string)$found, 10, 13);
				},
				$this->cards
			);

			return implode("", $values);
		}

		public function value(bool $wildcards = false): string
		{
			return $this->type($wildcards)->value . $this->strength($wildcards);
		}
	}
?>
