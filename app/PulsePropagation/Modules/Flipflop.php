<?php
	declare(strict_types=1);

	namespace App\PulsePropagation\Modules;

	use App\PulsePropagation\Module;
	use App\PulsePropagation\Pulse;
	use App\PulsePropagation\State;

	class Flipflop extends Module
	{
		public bool $state = false;

		public function execute(string $origin, State $pulse): array
		{
			$results = [];

			if ($pulse === State::LOW)
			{
				$this->state = !$this->state;

				foreach ($this->destinations as $destination)
				{
					$results[] = new Pulse(
						$this->state === true ? State::HIGH : State::LOW,
						$this->label,
						$destination
					);
				}
			}

			return $results;
		}

		public function reset(): void
		{
			$this->state = false;
		}
	}
?>
