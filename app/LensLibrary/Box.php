<?php
	declare(strict_types=1);

	namespace App\LensLibrary;

	class Box
	{
		/** @var Element[] */
		public array $slots = [];

		public function apply(Element $element): void
		{
			switch ($element->operation)
			{
				case "=":
					$position = array_key_first(
						array_filter(
							$this->slots,
							function ($item) use ($element)
							{
								return $item->label === $element->label;
							}
						)
					);

					if ($position !== null)
					{
						$this->slots[$position] = $element;
					}
					else
					{
						$this->slots[] = $element;
					}

					break;
				case "-":
					$this->slots = array_values(
						array_filter(
							$this->slots,
							function ($item) use ($element)
							{
								return $item->label !== $element->label;
							}
						)
					);
					break;

			}
		}

		public function __toString(): string
		{
			return implode(" ", $this->slots);
		}

		public function power(): int
		{
			$power = 0;
			$index = 1;

			foreach ($this->slots as $slot)
			{
				$power += ($index * $slot->focalLength);
				$index++;
			}

			return $power;
		}
	}
?>
