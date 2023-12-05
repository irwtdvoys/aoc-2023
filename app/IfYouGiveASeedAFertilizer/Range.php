<?php
	namespace App\IfYouGiveASeedAFertilizer;

	class Range
	{
		public function __construct(
			public int $source,
			public int $destination,
			public int $length
		) {}

		private function change(): int
		{
			return $this->source - $this->destination;
		}

		public function adjust(int $value): int
		{
			if ($value >= $this->destination && $value < $this->destination + $this->length)
			{
				return $value + $this->change();
			}

			return $value;
		}

		public function reverse(int $value): int
		{
			if ($value >= $this->source && $value < $this->source + $this->length)
			{
				return $value - $this->change();
			}

			return $value;
		}
	}
?>
