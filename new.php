<?php

class Amenity
{
    public $name;
    public $movable;
    public $description;

    public function __construct($name, $movable, $description)
    {
        $this->name = $name;
        $this->movable = $movable;
        $this->description = $description;
    }
}

class Room
{
    public $roomNumber;
    public $rate;
    public $amenities;
    public function __construct($roomNumber, $rate, $amenities = [])
    {
        $this->roomNumber = $roomNumber;
        $this->rate = $rate;
        $this->amenities = $amenities;
    }

    public function getRoomNumber()
    {
        return $this->roomNumber;
    }
    public function calculateCost($numberOfDays)
    {
        if ($numberOfDays > 0) {
            return round($numberOfDays * $this->rate, 2);
        }

        return null;
    }

    public function createDescription()
    {
        $description = '';

        foreach ($this->amenities as $amenity) {
            $description = $amenity->description . '';
        }

        return $description;
    }
}