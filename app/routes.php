<?php

foreach (File::allFiles(__DIR__.'/routes') as $partial) {
	require_once $partial->getPathname();
}