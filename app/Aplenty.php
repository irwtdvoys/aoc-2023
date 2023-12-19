<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Range;
	use App\Aplenty\Operator;
	use App\Aplenty\Part;
	use App\Aplenty\PartsRange;
	use App\Aplenty\Target;
	use App\Aplenty\Workflow;

	class Aplenty extends Helper
	{
		/** @var Part[] */
		public array $parts;
		/** @var Workflow[] */
		public array $workflows;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			[$workflows, $parts] = explode(PHP_EOL . PHP_EOL, $raw);

			$this->parts = array_map(
				function ($element)
				{
					return new Part($element);
				},
				explode(PHP_EOL, $parts)
			);

			$workflows = explode(PHP_EOL, $workflows);

			foreach ($workflows as $next)
			{
				$workflow = new Workflow($next);

				$this->workflows[$workflow->label] = $workflow;
			}
		}

		private function process(Part $part): Target
		{
			$label = "in";
			$flow = [];

			while (!$label instanceof Target)
			{
				$flow[] = $label;
				$workflow = $this->workflows[$label];
				$label = $workflow->check($part);
			}

			$flow[] = $label->value;

			if ($this->verbose)
			{
				$this->output($part . ": " . implode(" -> ", $flow));
			}

			return $label;
		}

		private function combinations(PartsRange $range, string $label = "in", int $depth = 0): int
		{
			if ($this->verbose)
			{
				$this->output($label . ": " . number_format($range->count()));
			}

			$workflow = $this->workflows[$label];
			$result = 0;

			foreach ($workflow->conditions as $condition)
			{
				$newRange = clone $range;

				if (isset($condition->operator))
				{
					switch ($condition->operator)
					{
						case Operator::LESS_THAN:

							$lower = new Range(1, $condition->value - 1);
							$upper = new Range($condition->value, 4000);

							$newRange->{$condition->property} = $newRange->{$condition->property}->intersect($lower);
							$range->{$condition->property} = $range->{$condition->property}->intersect($upper);
							break;
						case Operator::GREATER_THAN:

							$lower = new Range(1, $condition->value);
							$upper = new Range($condition->value + 1, 4000);

							$newRange->{$condition->property} = $newRange->{$condition->property}->intersect($upper);
							$range->{$condition->property} = $range->{$condition->property}->intersect($lower);
							break;
					}
				}

				$result += match ($condition->target)
				{
					Target::REJECTED => 0,
					Target::ACCEPTED => $newRange->count(),
					default => $this->combinations($newRange, $condition->target, $depth + 1),
				};
			}

			return $result;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->parts as $part)
			{
				if ($this->process($part) === Target::ACCEPTED)
				{
					$result->part1 += $part->rating();
				}
			}

			$result->part2 = $this->combinations(new PartsRange(1, 4000));

			return $result;
		}
	}
?>
