<?php 

class ProductionCalendarService
{
    public function getMaxProductionTime(array $data): int
    {
        $result = 0;

        if (!empty($data)) {
            $result = max(
                array_map(function($data) {
                    return $data['production_time'];
                }, $data)
            );
        }

        return $result;
    }
}