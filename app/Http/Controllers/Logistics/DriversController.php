<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Helpers\DriverHelper;
use App\Http\Requests\Logistics\DriverRequest;
use App\Models\Logistics\Driver;
use App\Models\Logistics\DriverVehicle;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Traits\Helpers\MyFileUpload;

class DriversController extends Controller
{
    use ImageTrait, MyFileUpload;

    public function __construct()
    {
        $this->authorizeResource(Driver::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $driver = Driver::with(['vehicle'])->where('user_id', Auth::user()->id)->first();
        return view('pages.logistics.driver.create')->with([
            'driver' => $driver
        ]);
    }

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
        $imagePath = '';
        if(isset($validatedData['image_path']))
        {
            $image_path = $this->uploadMyFile($validatedData['image_path'], 'logistics/drivers/images', 'driver-image');
        }

        if(isset($validatedData['license_path']))
        {
            $license_path = $this->uploadMyFile($validatedData['license_path'], 'logistics/drivers/license', 'license');
        }

        if(isset($validatedData['cnic_front_path']))
        {
            $cnic_front_path = $this->uploadMyFile($validatedData['cnic_front_path'], 'logistics/drivers/cnic-front-image', 'cnic-front-image');
        }

        if(isset($validatedData['cnic_back_path']))
        {
            $cnic_back_path = $this->uploadMyFile($validatedData['cnic_back_path'], 'logistics/drivers/cnic-back-image', 'cnic-back-image');
        }

        if(isset($validatedData['vehicle_image']))
        {
            $vehicle_image = $this->uploadMyFile($validatedData['vehicle_image'], 'logistics/drivers/vehicle-image', 'vehicle-image');
        }

        if(isset($validatedData['vehicle_reg_certificate']))
        {
            $vehicle_reg_certificate = $this->uploadMyFile($validatedData['vehicle_reg_certificate'], 'logistics/drivers/vehicle-reg-ceritificate', 'vehicle-reg-ceritificate');
        }

        if(isset($validatedData['vehicle_health_certificate']))
        {
            $vehicle_health_certificate = $this->uploadMyFile($validatedData['vehicle_health_certificate'], 'logistics/drivers/health-certificate', 'health-certificate');
        }


        $saved = tap(
            Driver::create([
                'user_id' => Auth::user()->id,
                'image' => $image_path,
                'dob' => $validatedData['dob'],
                'license_number' => $validatedData['license_no'],
                'license_photo' => $license_path,
                'license_expiry_date' => $validatedData['date_of_expiry'],
                'cnic_front' => $cnic_front_path,
                'cnic_back' => $cnic_back_path,
                'referral_code' => $validatedData['referrel_code'] ?? NULL
            ]), function (Driver $driver) use ($validatedData, $vehicle_image, $vehicle_reg_certificate, $vehicle_health_certificate) {
                $driverVehicle = new DriverVehicle();

                $driverVehicle->driver_id = $driver->id;
                $driverVehicle->vehicle_id = $validatedData['vehicle_id'];
                $driverVehicle->company_name = $validatedData['company'];
                $driverVehicle->number_plate_no = $validatedData['number_plate_no'];
                $driverVehicle->photo = $vehicle_image;
                $driverVehicle->registration_certificate = $vehicle_reg_certificate;
                $driverVehicle->fitness_certificate = $vehicle_health_certificate;

                $driver->vehicle()->save($driverVehicle);
            }
        );

        if($saved) {
            DB::commit();
            Session::flash('message', __('Driver information has been saved successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            DB::rollBack();
            Session::flash('message', __('Unable to save driver information'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
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
                $driver->{$key} = $this->uploadImage($image, 'public/logistics/drivers/'.$folder);
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
                $driver->vehicle->{$key} = $this->uploadImage($image, 'public/logistics/drivers/'.$folder);
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
            Session::flash('message', __('Driver information has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            DB::rollBack();
            Session::flash('message', __('Unable to update driver information'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

}
