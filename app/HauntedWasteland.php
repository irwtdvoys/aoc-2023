<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\HauntedWasteland\Node;
	use Bolt\Maths;

	class HauntedWasteland extends Helper
	{
		/** @var string[] */
		public array $instructions;
		/** @var Node[] */
		public array $nodes;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			[$instructions, $nodes] = explode(PHP_EOL . PHP_EOL, $raw);

			$this->instructions = str_split($instructions);

			$regex = "/(?'label'[A-Z0-9]{3,}) = \((?'left'[A-Z0-9]{3,}), (?'right'[A-Z0-9]{3,})\)/";
			preg_match_all($regex, $nodes, $matches);

			foreach ($matches['label'] as $label)
			{
				$this->nodes[$label] = new Node($label);
			}

			for ($index = 0; $index < count($matches['label']); $index++)
			{
				$this->nodes[$matches['label'][$index]]->left = $this->nodes[$matches['left'][$index]];
				$this->nodes[$matches['label'][$index]]->right = $this->nodes[$matches['right'][$index]];
			}
		}

		private function process(Node $current, string $end): int
		{
			$index = 0;
			$count = 0;

			while (!str_ends_with($current->label, $end))
			{
				if ($index >= count($this->instructions))
				{
					$index = $index % count($this->instructions);
				}

				$instruction = $this->instructions[$index];

				switch ($instruction)
				{
					case "L":
						$current = $current->left;
						break;
					case "R":
						$current = $current->right;
						break;
				}

				if ($this->verbose)
				{
					$this->output($index . " " . $instruction . " " . $current->label);
				}

				$index++;
				$count++;
			}

			return $count;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$current = $this->nodes['AAA'];

			$result->part1 = $this->process($current, "ZZZ");

			$nodes = $this->nodes;

			$starters = array_map(
				function ($element) use ($nodes)
				{
					return $nodes[$element];
				},
				array_filter(
					array_keys($this->nodes),
					function ($element)
					{
						return str_ends_with($element, "A");
					}
				)
			);

			$counts = array_map(
				function ($element)
				{
					return $this->process($element, "Z");
				},
				$starters
			);

			$result->part2 = Maths::lcm(...$counts);

			return $result;
		}
	}
?>
