<?php

namespace SylusCode\MultiSport\EndomondoWrapper;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class WorkoutImporter implements WorkoutImporterInterface
{
    private $finder;
    private $workoutParser;

    public function __construct(Finder $finder, WorkoutParser $workoutParser)
    {
        $this->finder = $finder;
        $this->workoutParser = $workoutParser;
    }

    public function importFromZipFile(string $filePath, string $temporaryExtractPath = null): array
    {
        $path = dirname($filePath);

        $temporaryExtractPath = $temporaryExtractPath ?? $path;
        $this->extractFiles($filePath,$temporaryExtractPath);

        $this->finder->in($temporaryExtractPath . '/TempData/Workouts')->name('*.json');

        $workouts = [];
        $ileTo = 0;

        foreach ($this->finder as $file) {
            $string = $file->getContents();
            $workout = $this->workoutParser->parseFromJson($string);
            array_push($workouts, $workout);

            if ($ileTo == 5) {
                break;
            }
            $ileTo++;
        }

        $filesystem = new Filesystem();
        $filesystem->remove([$temporaryExtractPath . '/TempData']);

        return $workouts;
    }

    private function extractFiles(string $filePath, string $temporaryExtractPath): void
    {
        $zip = new \ZipArchive();
        $res = $zip->open($filePath);

        if ($res == !true) {
            throw new \InvalidPathException(sprintf('Could not open %s', $filePath));
        }
        $zip->extractTo($temporaryExtractPath . '/TempData');
        $zip->close();
    }
}
