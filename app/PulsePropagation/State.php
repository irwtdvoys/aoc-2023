<?php
	declare(strict_types=1);

	namespace App\PulsePropagation;

	enum State: int
	{
		case HIGH = 1;
		case LOW = 0;
	}
?>
