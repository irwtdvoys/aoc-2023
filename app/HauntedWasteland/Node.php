<?php
	declare(strict_types=1);

	namespace App\HauntedWasteland;

	class Node
	{
		public function __construct(
			public string $label,
			public ?Node $left = null,
			public ?Node $right = null,
		) {}
	}
?>
