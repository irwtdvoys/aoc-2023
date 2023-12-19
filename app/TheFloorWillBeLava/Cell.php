<?php
	declare(strict_types=1);

	namespace App\TheFloorWillBeLava;

	class Cell
	{
		public Tiles $tile;
		/** @var Tiles[] */
		public array $visited = [];

		public function __construct(Tiles $tile)
		{
			$this->tile = $tile;
		}

		public function isEnergized(): bool
		{
			return count($this->visited) > 0;
		}

		public function visit(Direction $direction): bool
		{
			if (in_array($direction, $this->visited))
			{
				return false;
			}

			$this->visited[] = $direction;

			return true;
		}

		public function reset(): void
		{
			$this->visited = [];
		}
	}
?>
