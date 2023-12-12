<?php
	declare(strict_types=1);

	namespace App\HotSprings;

	class Record
	{
		public string $springs;
		/** @var int[] */
		public array $groups;
		public array $cache;

		public function __construct($data)
		{
			[$strings, $groups] = explode(" ", $data);

			$this->springs = $strings;
			$this->groups = array_map("intval", explode(",", $groups));
		}

		public function __toString(): string
		{
			return $this->springs . " " . implode(",", $this->groups);
		}

		public function unfold(): void
		{
			$this->springs = implode("?", array_fill(0, 5, $this->springs));
			$this->groups = array_merge(...array_fill(0, 5, $this->groups));
			$this->cache = [];
		}

		public function countArrangements(?array $groups = null, $state = [0, 0, 0]): int
		{
			if (!isset($groups))
			{
				$groups = $this->groups;
			}

			$state_key = implode(":", $state);

			if (isset($this->cache[$state_key]))
			{
				return $this->cache[$state_key];
			}

			[$stringIndex, $groupIndex, $length] = $state;

			if ($stringIndex == strlen($this->springs))
			{
				if ($groupIndex == count($groups)-1 && $length == $groups[$groupIndex])
				{
					$groupIndex++;
					$length = 0;
				}

				return (int)($groupIndex == count($groups) && $length == 0);
			}

			$result = 0;

			if (str_contains(".?", $this->springs[$stringIndex]))
			{
				if ($length == 0)
				{
					$result += $this->countArrangements($groups, [$stringIndex + 1, $groupIndex, 0]);
				}
				elseif ($groupIndex < count($groups) && $groups[$groupIndex] == $length)
				{
					$result += $this->countArrangements($groups, [$stringIndex + 1, $groupIndex + 1, 0]);
				}
			}

			if (str_contains("#?", $this->springs[$stringIndex]))
			{
				$result += $this->countArrangements($groups, [$stringIndex + 1, $groupIndex, $length + 1]);
			}

			return $this->cache[$state_key] = $result;
		}
	}
?>
