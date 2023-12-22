<?php
	declare(strict_types=1);

	namespace App\PulsePropagation;

	class Pulse
	{
		public function __construct(
			public State $state,
			public string $origin,
			public string $target
		)
		{}

		public function __toString(): string
		{
			return $this->origin . " -" . $this->state->name . "-> " . $this->target;
		}
	}
?>
