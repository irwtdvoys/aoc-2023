<?php
	declare(strict_types=1);

	namespace App\TheFloorWillBeLava;

	enum Tiles: string
	{
		case EMPTY_SPACE = ".";
		case MIRROR_FORWARD = "/";
		case MIRROR_BACKWARD = "\\";
		case SPLITTER_VERTICAL = "|";
		case SPLITTER_HORIZONTAL = "-";
		case ENERGIZED = "#";
	}
?>
