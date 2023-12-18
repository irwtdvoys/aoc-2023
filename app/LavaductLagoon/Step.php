<?php
	declare(strict_types=1);

	namespace App\LavaductLagoon;

	class Step
	{
		public Direction $direction;
		public int $distance;
		public string $colour;

		public function __construct(string $data)
		{
			$parts = explode(" ", $data);

			$this->direction = Direction::from($parts[0]);
			$this->distance = (int)$parts[1];
			$this->colour = substr($parts[2], 1, -1);
		}

		public function convert(): void
		{
			switch (substr($this->colour, -1))
			{
				case "0":
					$this->direction = Direction::RIGHT;
					break;
				case "1":
					$this->direction = Direction::DOWN;
					break;
				case "2":
					$this->direction = Direction::LEFT;
					break;
				case "3":
					$this->direction = Direction::UP;
					break;
			}

			$this->distance = hexdec(substr($this->colour, 1, 5));
		}
	}
?>
