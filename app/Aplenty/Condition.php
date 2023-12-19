<?php
	declare(strict_types=1);

	namespace App\Aplenty;

	class Condition
	{
		public ?string $property;
		public ?Operator $operator;
		public ?int $value;
		public string|Target $target;

		public function __construct(string $data)
		{
			preg_match("/(?'start'[xmas][<>])?(?'value'[0-9]+)?:?(?'target'[a-zAR]+)/m", $data, $matches);

			if (!empty($matches['start']))
			{
				$this->property = substr($matches['start'], 0, 1);
				$this->operator = Operator::from(substr($matches['start'], 1));
			}

			$this->value = !empty($matches['value']) ? (int)$matches['value'] : null;
			$this->target = strlen($matches['target']) === 1 ? Target::from($matches['target']) : $matches['target'];
		}

		public function __toString(): string
		{
			$output = "";

			if (isset($this->property))
			{
				$output = $this->property . $this->operator->value . $this->value . ":";
			}

			return $output . (is_string($this->target) ? $this->target : $this->target->value);
		}
	}
?>
