<?php

namespace SylusCode\MultiSport\EndomondoWrapper;

interface WorkoutImporterInterface
{
    public function importFromZipFile(string $filePath, string $temporaryExtractPath = null ): iterable;
}
