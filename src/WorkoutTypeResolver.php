<?php

namespace SylusCode\MultiSport\EndomondoWrapper;

use SylusCode\MultiSport\Workout\Type;

class WorkoutTypeResolver implements WorkoutTypeResolverInterface
{
    public function resolve(string $endomondoWorkoutTypeName): Type
    {
        return  new Type($endomondoWorkoutTypeName);
    }
}