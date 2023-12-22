<?php
	declare(strict_types=1);

	namespace App\PulsePropagation\Modules;

	use App\PulsePropagation\Module;
	use App\PulsePropagation\State;

	class Output extends Module
	{
		public function execute(string $origin, State $pulse): array
		{
			return [];
		}

		public function reset(): void
		{
		}
	}
?>
