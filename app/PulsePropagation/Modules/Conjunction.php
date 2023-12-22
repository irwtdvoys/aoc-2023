<?php
	declare(strict_types=1);

	namespace App\PulsePropagation\Modules;

	use App\PulsePropagation\Module;
	use App\PulsePropagation\Pulse;
	use App\PulsePropagation\State;

	class Conjunction extends Module
	{
		/** @var State[] */
		public array $memory = [];

		public function __construct(string $label, array $origins, array $destinations)
		{
			parent::__construct($label, $origins, $destinations);

			foreach ($this->origins as $origin)
			{
				$this->memory[$origin] = State::LOW;
			}
		}

		public function execute(string $origin, State $pulse): array
		{
			$results = [];
			$this->memory[$origin] = $pulse;

			foreach ($this->destinations as $destination)
			{
				$results[] = new Pulse(
					$this->isTriggered() ? State::LOW : State::HIGH,
					$this->label,
					$destination
				);
			}

			return $results;
		}

		private function isTriggered(): bool
		{
			return !in_array(State::LOW, $this->memory, true);
		}

		public function reset(): void
		{
			foreach ($this->memory as $key => $value)
			{
				$this->memory[$key] = State::LOW;
			}
		}
	}
?>
