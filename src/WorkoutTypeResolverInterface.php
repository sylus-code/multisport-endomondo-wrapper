<?php

namespace SylusCode\MultiSport\EndomondoWrapper;

use SylusCode\MultiSport\Workout\Type;

interface WorkoutTypeResolverInterface
{
    public function resolve(string $endomondoWorkoutTypeName): Type;
}