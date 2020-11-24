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

    public function parseFromJson(string $json): Workout
    {
        $json = json_decode($json, true);
        $jsonFlatten = $this->array_flatten($json);
        $workout = new Workout();

        if (isset($jsonFlatten['sport'])) {
            $workout->setType($this->endoTypeResolver->resolve($jsonFlatten['sport']));
        }
        if (isset($jsonFlatten['start_time'])) {
            $workout->setStart($this->resolveTimestamp($jsonFlatten['start_time']));
        }
        if (isset($jsonFlatten['distance_km'])) {
            $workout->setDistance($jsonFlatten['distance_km']);
        }
        if (isset($jsonFlatten['duration_s'])) {
            $workout->setDurationTotal($jsonFlatten['duration_s']);
        }
        if (isset($jsonFlatten['calories_kcal'])) {
            $workout->setCalories($jsonFlatten['calories_kcal']);
        }
        if (isset($jsonFlatten['speed_avg_kmh'])) {
            $workout->setAvgSpeed($jsonFlatten['speed_avg_kmh']);
        }
        if (isset($jsonFlatten['heart_rate_avg_bpm'])) {
            $workout->setAvgHeartRate($jsonFlatten['heart_rate_avg_bpm']);
        }
        if (isset($jsonFlatten['heart_rate_max_bpm'])) {
            $workout->setMaxHeartRate($jsonFlatten['heart_rate_max_bpm']);
        }
        if (isset($jsonFlatten['speed_max_kmh'])) {
            $workout->setMaxSpeed($jsonFlatten['speed_max_kmh']);
        }
        if (isset($jsonFlatten['steps'])) {
            $workout->setSteps($jsonFlatten['steps']);
        }
        if (isset($jsonFlatten['notes'])) {
            $workout->setMessage($jsonFlatten['notes']);
        }
        if (isset($jsonFlatten['message'])) {
            $workout->setMessage($jsonFlatten['message']);
        }
        if (isset($jsonFlatten['points'])) {
            $points = [];
            foreach ($jsonFlatten['points'] as $trackPoint) {

                $point = $this->createPoint($trackPoint);
                $points[] = $point;
            }
            $workout->setPoints($points);
        }

        return $workout;
    }

    private function array_flatten(array $json): array
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

    private function resolveTimestamp(string $timestamp)
    {
        $datetime = \DateTime::createFromFormat('D M d H:i:s T Y', $timestamp);

        if ($datetime == false) {
            $datetime = substr($timestamp, 0, -2);
            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        }

        return $datetime;
    }

    private function createPoint($trackPoint): Point
    {
        $point = new Point();
        if (isset($trackPoint['latitude'])) {
            $point->setLatitude($trackPoint['latitude']);
        }
        if (isset($trackPoint['longitude'])) {
            $point->setLongtitude($trackPoint['longitude']);
        }
        if (isset($trackPoint['timestamp'])) {
            $point->setTime($this->resolveTimestamp($trackPoint['timestamp']));
        }
        if (isset($trackPoint['distance_km'])) {
            $point->setDistance($trackPoint['distance_km']);
        }
        if (isset($trackPoint['altitude'])) {
            $point->setAltitude($trackPoint['altitude']);
        }
        if (isset($trackPoint['heart_rate_bpm'])) {
            $point->setHeartRate($trackPoint['heart_rate_bpm']);
        }

        return $point;
    }
}
