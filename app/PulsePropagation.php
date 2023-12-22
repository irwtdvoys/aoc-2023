<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\PulsePropagation\Module;
	use App\PulsePropagation\Pulse;
	use App\PulsePropagation\State;
	use Bolt\Maths;

	class PulsePropagation extends Helper
	{
		/** @var Module[] */
		public array $modules;
		const TARGETS = ["xj", "qs", "kz", "km"];

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$lines = explode(PHP_EOL, parent::load($override));
			$data = [];

			foreach ($lines as $line)
			{
				[$tmp, $destinations] = explode(" -> ", $line);

				switch (substr($tmp, 0, 1))
				{
					case "%":
						$label = substr($tmp, 1);
						$module = "Flipflop";
						break;
					case "&":
						$label = substr($tmp, 1);
						$module = "Conjunction";
						break;
					default:
						$label = $tmp;
						$module = "Broadcaster";
						break;
				}

				$destinations = explode(", ", $destinations);

				$data[$label] = [
					"module" => $module,
					"destinations" => $destinations,
					"origins" => []
				];
			}

			foreach ($data as $label => $datum)
			{
				foreach ($datum['destinations'] as $destination)
				{
					if (!isset($data[$destination]))
					{
						$data[$destination] = [
							"module" => "Output",
							"destinations" => [],
							"origins" => []
						];
					}

					$data[$destination]['origins'][] = $label;
				}
			}

			foreach ($data as $label => $datum)
			{
				$class = "\\App\\PulsePropagation\\Modules\\" . $datum['module'];
				$this->modules[$label] = new $class(
					$label,
					$datum['origins'],
					$datum['destinations']
				);
			}
		}

		private function reset(): void
		{
			foreach ($this->modules as $module)
			{
				$module->reset();
			}
		}

		private function pushButton(int $count = 1): array|int
		{
			$counts = [0, 0];
			$push = 1;
			$cache = [];

			while ($push < $count)
			{
				$pulses = [
					new Pulse(
						State::LOW,
						"button",
						"broadcaster"
					)
				];

				while (count($pulses) > 0)
				{
					$pulse = array_shift($pulses);

					if ($pulse->state === State::HIGH && in_array($pulse->origin, self::TARGETS))
					{
						if (!isset($cache[$pulse->origin]))
						{
							$cache[$pulse->origin] = $push;
						}

						if (count($cache) === count(self::TARGETS))
						{
							return Maths::lcm(...array_values($cache));
						}
					}

					$counts[$pulse->state->value]++;

					$pulses = array_merge(
						$pulses,
						$this->modules[$pulse->target]->execute($pulse->origin, $pulse->state)
					);
				}

				$push++;
			}

			return $counts;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$counts = $this->pushButton(1000);
			$result->part1 = array_product($counts);

			$this->reset();
			$counts = $this->pushButton(5000);
			$result->part2 = $counts;

			return $result;
		}
	}
?>
