<?php
	declare(strict_types=1);

	namespace App\TheFloorWillBeLava;

	enum Direction: string
	{
		case UP = "^";
		case RIGHT = ">";
		case DOWN = "v";
		case LEFT = "<";
	}
?>
