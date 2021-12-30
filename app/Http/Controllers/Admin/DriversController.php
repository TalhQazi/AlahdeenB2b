<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logistics\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DriversController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Driver::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = Driver::with(['vehicle', 'user'])->paginate(10);
        return view('pages.logistics.driver.admin.index')->with([
            'drivers' => $drivers
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logistics\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        $driver = $driver::with(['user', 'vehicle'])->where('id', $driver->id)->first();
        return view('pages.logistics.driver.admin.show')->with([
            'driver' => $driver
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logistics\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $driver->is_verified = 1;
        if($driver->save()) {
            Session::flash('message', __('Driver has been marked as verified'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to mark driver as verified'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->route('admin.logistics.drivers.index');
    }

}
