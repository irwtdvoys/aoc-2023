<?php
	declare(strict_types=1);

	namespace App\PulsePropagation;

	abstract class Module
	{
		public string $label;

		/** @var string[] */
		public array $origins;
		/** @var string[] */
		public array $destinations;

		/** @return string[] */
		abstract public function execute(string $origin, State $pulse): array;

		public function __construct(string $label, array $origins, array $destinations)
		{
			$this->label = $label;
			$this->origins = $origins;
			$this->destinations = $destinations;
		}

		abstract public function reset(): void;
	}
?>
