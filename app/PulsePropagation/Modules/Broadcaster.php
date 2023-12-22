<?php
	declare(strict_types=1);

	namespace App\PulsePropagation\Modules;

	use App\PulsePropagation\Module;
	use App\PulsePropagation\Pulse;
	use App\PulsePropagation\State;

	class Broadcaster extends Module
	{
		public function execute(string $origin, State $pulse): array
		{
			$results = [];

			foreach ($this->destinations as $destination)
			{
				$results[] = new Pulse(
					$pulse,
					$this->label,
					$destination
				);
			}

			return $results;
		}

		public function reset(): void
		{
		}
	}
?>
