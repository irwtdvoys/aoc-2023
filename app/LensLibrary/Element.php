<?php
	declare(strict_types=1);

	namespace App\LensLibrary;

	class Element
	{
		public string $data;

		public string $label;
		public string $operation;
		public ?int $focalLength;

		public function __construct($data)
		{
			$this->data = $data;
			preg_match("/(?'label'[a-z]+)(?'operation'[=-])(?'focus'[0-9]?)/", $data, $matches);

			$this->label = $matches['label'];
			$this->operation = $matches['operation'];
			$this->focalLength = $matches['focus'] === "" ? null : (int)$matches['focus'];
		}

		public function hash(string $string = null): int
		{
			$currentValue = 0;
			$characters = str_split($string ?? $this->data);

			foreach ($characters as $character)
			{
				$currentValue += ord($character);
				$currentValue *= 17;
				$currentValue %= 256;
			}

			return $currentValue;
		}

		public function __toString(): string
		{
			return "[" . $this->label . " " . $this->focalLength . "]";
		}
	}
?>
