<?php
	namespace App\IfYouGiveASeedAFertilizer;

	class Map
	{
		public string $from;
		public string $to;
		/** @var Range[] */
		public array $ranges;

		public function __construct(string $section)
		{
			[$label, $data] = explode(":" . PHP_EOL, $section);

			[$this->from, $this->to] = explode("-to-", substr($label, 0, -4));

			$this->ranges = array_map(
				function ($element)
				{
					return new Range(...array_map("intval", explode(" ", $element)));
				},
				explode(PHP_EOL, $data)
			);
		}

		public function adjust(int $value): int
		{
			foreach ($this->ranges as $range)
			{
				$checked = $range->adjust($value);

				if ($checked !== $value)
				{
					return $checked;
				}
			}

			return $value;
		}

		public function reverse(int $value): int
		{
			foreach ($this->ranges as $range)
			{
				$checked = $range->reverse($value);

				if ($checked !== $value)
				{
					return $checked;
				}
			}

			return $value;
		}
	}
?>
