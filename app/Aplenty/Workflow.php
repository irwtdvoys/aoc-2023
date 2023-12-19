<?php
	declare(strict_types=1);

	namespace App\Aplenty;

	use Exception;

	class Workflow
	{
		public string $label;
		/** @var Condition[] */
		public array $conditions;

		public function __construct(string $data)
		{
			$start = strpos($data, "{");
			$this->label = substr($data, 0, $start);

			$logic = substr($data, $start + 1, -1);
			$this->conditions = array_map(
				function ($element)
				{
					return new Condition($element);
				},
				explode(",", $logic)
			);
		}

		public function check(Part $part): string|Target
		{
			foreach ($this->conditions as $condition)
			{
				if (!isset($condition->property))
				{
					return $condition->target;
				}

				switch ($condition->operator)
				{
					case Operator::GREATER_THAN:
						if ($part->{$condition->property} > $condition->value)
						{
							return $condition->target;
						}
						break;
					case Operator::LESS_THAN:
						if ($part->{$condition->property} < $condition->value)
						{
							return $condition->target;
						}
						break;
				}
			}

			throw new Exception("Unable to process conditions");
		}
	}
?>
