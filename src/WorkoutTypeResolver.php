<?php

namespace SylusCode\MultiSport\EndomondoWrapper;

use SylusCode\MultiSport\Workout\Type;

class WorkoutTypeResolver implements WorkoutTypeResolverInterface
{
    public function resolve(string $endomondoWorkoutTypeName): Type
    {
        // add other activitiy types when archive arrived
        switch ($endomondoWorkoutTypeName) {
            case 'RUNNING':
                $typeId = Type::TYPE_RUNNING;
                break;
            default:
                $typeId = Type::TYPE_OTHER;
        }
        return Type::createFromTypeId($typeId);
    }
}