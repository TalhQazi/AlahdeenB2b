<?php

namespace App\Http\Controllers\Api\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Helpers\DriverHelper;
use App\Http\Requests\Logistics\DriverRequest;
use App\Http\Resources\Logistics\Driver as LogisticsDriver;
use App\Models\Logistics\Driver;
use App\Models\Logistics\DriverVehicle;
use App\Traits\ApiResponser;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriversController extends Controller
{
    use ImageTrait, ApiResponser;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DriverRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        if(!empty(Driver::where('user_id', Auth::user()->id)->first())) {
            return $this->error(__('Driver alreay exists against the given user id'), 500);
        }

        $saved = tap(
            Driver::create([
                'user_id' => Auth::user()->id,
                'image' => $this->getNewImagePath($validatedData['image_path'], 'public/logistics/drivers/photos'),
                'dob' => $validatedData['dob'],
                'license_number' => $validatedData['license_no'],
                'license_photo' => $this->getNewImagePath($validatedData['license_path'], 'public/logistics/drivers/license'),
                'license_expiry_date' => $validatedData['date_of_expiry'],
                'cnic_front' => $this->getNewImagePath($validatedData['cnic_front_path'], 'public/logistics/drivers/cnic'),
                'cnic_back' => $this->getNewImagePath($validatedData['cnic_back_path'], 'public/logistics/drivers/cnic'),
                'referral_code' => $validatedData['referrel_code'] ?? NULL
            ]), function (Driver $driver) use ($validatedData) {
                $driverVehicle = new DriverVehicle();

                $driverVehicle->driver_id = $driver->id;
                $driverVehicle->vehicle_id = $validatedData['vehicle_id'];
                $driverVehicle->company_name = $validatedData['company'];
                $driverVehicle->number_plate_no = $validatedData['number_plate_no'];
                $driverVehicle->photo = $this->getNewImagePath($validatedData['vehicle_image'], 'public/logistics/drivers/vehicles');
                $driverVehicle->registration_certificate = $this->getNewImagePath($validatedData['vehicle_reg_certificate'], 'public/logistics/drivers/vehicles/reg-certificates');
                $driverVehicle->fitness_certificate = $this->getNewImagePath($validatedData['vehicle_health_certificate'], 'public/logistics/drivers/vehicles/fitness-certificates');

                $driver->vehicle()->save($driverVehicle);
            }
        );

        if($saved) {
            DB::commit();
            return $this->success([], __('Driver information has been saved successfully'));
        } else {
            DB::rollBack();
            return $this->error(__('Unable to save driver information'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logistics\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        $driver = $driver::with(['user', 'vehicle'])->where('user_id', Auth::user()->id)->where('id', $driver->id)->first();
        $driver = (new LogisticsDriver($driver))->response()->getData();
        return $this->success($driver);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logistics\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(DriverRequest $request, Driver $driver)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        $driverInfoImages = DriverHelper::getExistingImages($validatedData, $driver);
        if(!empty($driverInfoImages)) {
            foreach($driverInfoImages as $key => $value) {
                if($key == 'license_photo') {
                    $image = $validatedData['license_path'];
                    $folder = 'license';
                } else if($key == 'cnic_front') {
                    $image = $validatedData['cnic_front_path'];
                    $folder = 'cnic';
                }  else if($key == 'cnic_back') {
                    $image = $validatedData['cnic_back_path'];
                    $folder = 'cnic';
                } else {
                    $image = $validatedData['image_path'];
                    $folder = 'photos';
                }
                $driver->{$key} = $this->getNewImagePath($image, 'public/logistics/drivers/'.$folder);
            }
        }

        $driverVehicleImages = DriverHelper::getDriverVehicleImages($validatedData, $driver);
        if(!empty($driverVehicleImages)) {
            foreach($driverVehicleImages as $key => $value) {
                if($key == 'registration_certificate') {
                    $image = $validatedData['vehicle_reg_certificate'];
                    $folder = 'vehicles/reg-certificates';
                } else if($key == 'fitness_certificate') {
                    $image = $validatedData['vehicle_health_certificate'];
                    $folder = 'vehicles/fitness-certificates';
                } else {
                    $image = $validatedData['vehicle_image'];
                    $folder = 'vehicles';
                }
                $driver->vehicle->{$key} = $this->getNewImagePath($image, 'public/logistics/drivers/'.$folder);
            }
        }

        $driver->dob = $validatedData['dob'];
        $driver->license_number = $validatedData['license_no'];
        $driver->license_expiry_date = $validatedData['date_of_expiry'];
        $driver->referral_code = $validatedData['referrel_code'] ?? NULL;

        $driver->vehicle->vehicle_id = $validatedData['vehicle_id'];
        $driver->vehicle->company_name = $validatedData['company'];
        $driver->vehicle->number_plate_no = $validatedData['number_plate_no'];

        $existingImages = array_merge($driverInfoImages, $driverVehicleImages);


        if($driver->save() && $driver->vehicle->save() && DriverHelper::deleteExistingImages($existingImages)) {
            DB::commit();
            return $this->success([], __('Driver information has been updated successfully'));
        } else {
            DB::rollBack();
            return $this->success([], __('Unable to update driver information'));
        }
    }

    public function getNewImagePath($oldLocationPath, $newLocationPath)
    {
        $newPath = null;

        if(!empty($oldLocationPath)) {
            $tempPath = str_replace('/storage', 'public', $oldLocationPath);
            $newPath = str_replace('/storage/tmp/image', $newLocationPath, $oldLocationPath);
            $this->moveImage($tempPath, $newPath);
            $this->deleteImage($tempPath);
        } else {
            return false;
        }

        return $newPath;
    }
}
