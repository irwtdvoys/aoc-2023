<?php
	declare(strict_types=1);

	namespace App\PointOfIncidence;

	use AoC\Utils\Grid;

	class Pattern extends Grid
	{
		/** return string[] */
		public function fetch(Direction $direction): array
		{
			$result = [];

			if ($direction === Direction::HORIZONTAL)
			{
				foreach ($this->data as $datum)
				{
					$result[] = implode("", $datum);
				}
			}
			elseif ($direction === Direction::VERTICAL)
			{
				for ($x = 0; $x < count($this->data[0]); $x++)
				{
					$line = "";

					for ($y = 0; $y < count($this->data); $y++)
					{
						$line .= $this->data[$y][$x];
					}

					$result[] = $line;
				}
			}

			return $result;
		}
	}
?>
