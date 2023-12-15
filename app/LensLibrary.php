<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\LensLibrary\Box;
	use App\LensLibrary\Element;

	class LensLibrary extends Helper
	{
		/** @var Element[] */
		public array $initialisation;

		/** @var Box[] */
		public array $boxes;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->initialisation = array_map(
				function ($element)
				{
					return new Element($element);
				},
				explode(",", $raw)
			);

			for ($index = 0; $index < 256; $index++)
			{
				$this->boxes[$index] = new Box();
			}
		}

		public function draw(): void
		{
			$index = 0;

			foreach ($this->boxes as $box)
			{
				if (count($box->slots) > 0)
				{
					$this->output("Box " . $index . ": " . $box);
				}

				$index++;
			}
		}

		private function power(): int
		{
			$power = 0;
			$index = 1;

			foreach ($this->boxes as $box)
			{
				if (count($box->slots) > 0)
				{
					$power += $index * $box->power();
				}

				$index++;
			}

			return $power;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$sequence = [];

			foreach ($this->initialisation as $next)
			{
				$value = $next->hash();
				$sequence[] = $value;

				if ($this->verbose)
				{
					$this->output($next->data . " " . $value);
				}
			}

			$result->part1 = array_sum($sequence);

			foreach ($this->initialisation as $element)
			{
				$this->boxes[$element->hash($element->label)]->apply($element);

				if ($this->verbose)
				{
					$this->output("After \"" . $element->data . "\"");
					$this->draw();
					$this->output("");
				}
			}

			$result->part2 = $this->power();

			return $result;
		}
	}
?>
