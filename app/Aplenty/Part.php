<?php
	declare(strict_types=1);

	namespace App\Aplenty;

	class Part
	{
		public int $x;
		public int $m;
		public int $a;
		public int $s;

		public function __construct(string $data)
		{
			$properties = explode(",", substr($data, 1, -1));

			foreach ($properties as $property)
			{
				$key = substr($property, 0, 1);
				$value = (int)substr($property, strpos($property, "=") + 1);

				$this->$key = $value;
			}
		}

		public function __toString(): string
		{
			return "{x=" . $this->x . ",m=" . $this->m . ",a=" . $this->a . ",s=" . $this->s . "}";
		}

		public function rating(): int
		{
			return $this->x + $this->m + $this->a + $this->s;
		}
	}
?>
