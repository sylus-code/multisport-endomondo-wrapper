<?php

namespace SylusCode\MultiSport\EndomondoWrapper;

use SylusCode\MultiSport\Workout\Point;
use SylusCode\MultiSport\Workout\Workout;

class WorkoutParser
{
    private $endoTypeResolver;

    public function __construct(WorkoutTypeResolver $endoTypeResolver)
    {
        $this->endoTypeResolver = $endoTypeResolver;
    }

    public function parseFromJson($json): Workout
    {
        $jsonFlatten = $this->array_flatten($json);

        $workout = new Workout();
        $workout->setType($this->endoTypeResolver->resolve($jsonFlatten['sport']));
        $workout->setStart($this->formatStartTime($jsonFlatten['start_time']));
        $workout->setDistance($jsonFlatten['distance_km']);
        $workout->setDurationTotal($jsonFlatten['duration_s']);
        $workout->setCalories($jsonFlatten['calories_kcal']);
        $workout->setAvgSpeed($jsonFlatten['speed_avg_kmh']);

        $points = [];
        foreach ($jsonFlatten['points'] as $trackPoint) {

            $point = $this->createPoint($trackPoint);
            $points[] = $point;
        }
        $workout->setPoints($points);

        return $workout;
    }

    private function array_flatten($json): array
    {
        $return = array();
        foreach ($json as $key => $value) {
            if (is_array($value)) {
                if (isset($value['points'])) {
                    foreach ($value['points'] as $point) {
                        $return['points'][] = $this->array_flatten($point);
                    }
                } else {
                    $return = array_merge($return, $this->array_flatten($value));
                }
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    private function createPoint($trackPoint): Point
    {
        // possible to add HeartRate condition
        $point = new Point();
        if (isset($trackPoint['latitude'])) {
            $point->setLatitude($trackPoint['latitude']);
        }
        if (isset($trackPoint['longitude'])) {
            $point->setLongtitude($trackPoint['longitude']);
        }
        if (isset($trackPoint['timestamp'])) {
            $point->setTime(\DateTime::createFromFormat('D M d H:m:s T Y', $trackPoint['timestamp']));
        }
        if (isset($trackPoint['distance_km'])) {
            $point->setDistance($trackPoint['distance_km']);
        }
        if (isset($trackPoint['altitude'])) {
            $point->setAltitude($trackPoint['altitude']);
        }

        return $point;
    }
    private function formatStartTime(string $time): \DateTime
    {
        $startTime = substr($time, 0, -2);
        return \DateTime::createFromFormat('Y-m-d H:m:s', $startTime);
    }
}
