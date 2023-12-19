<?php
	declare(strict_types=1);

	namespace App\Aplenty;

	use AoC\Utils\Range;

	class PartsRange
	{
		public Range $x;
		public Range $m;
		public Range $a;
		public Range $s;

		public function __construct(int $min, int $max)
		{
			$this->x = new Range($min, $max);
			$this->m = new Range($min, $max);
			$this->a = new Range($min, $max);
			$this->s = new Range($min, $max);
		}

		public function count(): int
		{
			return $this->x->count() * $this->m->count() * $this->a->count() * $this->s->count();
		}

		public function __toString(): string
		{
			return json_encode($this);
		}
	}
?>
