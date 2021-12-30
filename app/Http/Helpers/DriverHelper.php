<?php

namespace App\Http\Helpers;

use App\Models\Logistics\Driver;
use App\Traits\ImageTrait;

class DriverHelper
{
    use ImageTrait;

    public static function getExistingImages($validatedData, Driver $driver)
    {

        $existingImages = [];

        if(!empty($validatedData['image_path'])) {
            $existingImages['image'] = $driver->image;
        }

        if(!empty($validatedData['license_path'])) {
            $existingImages['license_photo'] = $driver->license_photo;
        }

        if(!empty($validatedData['cnic_front_path'])) {
            $existingImages['cnic_front'] = $driver->cnic_front;
        }

        if(!empty($validatedData['cnic_back_path'])) {
            $existingImages['cnic_back'] = $driver->cnic_back;
        }

        return $existingImages;
    }

    public static function getDriverVehicleImages($validatedData, Driver $driver)
    {
        $existingImages = [];

        if(!empty($validatedData['vehicle_image'])) {
            $existingImages['photo'] = $driver->vehicle->photo;
        }
        if(!empty($validatedData['vehicle_reg_certificate'])) {
            $existingImages['registration_certificate'] = $driver->vehicle->registration_certificate;
        }
        if(!empty($validatedData['vehicle_health_certificate'])) {
            $existingImages['fitness_certificate'] = $driver->vehicle->fitness_certificate;
        }

        return $existingImages;
    }

    public static function deleteExistingImages($existingImages)
    {
        if(!empty($existingImages)) {
            foreach($existingImages as $existingImage) {
                if(!(new DriverHelper)->deleteImage($existingImage)) {
                    break;
                    return false;
                }
            }
        }

        return true;
    }
}
