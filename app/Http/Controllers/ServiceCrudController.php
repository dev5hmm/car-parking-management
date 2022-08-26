<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Filters\ServiceFilters;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceCrudController extends Controller
{
    public function index()
    {
        $services = ServiceResource::collection(Service::filter(app(ServiceFilters::class))->paginate(request()->perPage ?? 10));
        // return $services;
        return Inertia::render('Admin/Service/Index', [
            "services" => $services,
        ]);
    }
    public function create()
    {
        return Inertia::render('Admin/Service/Create', [

        ]);
    }
    public function store(StoreServiceRequest $request)
    {
        Service::create([
            'name' => $request['name'],
            'fee' => $request['fee'],
            'is_active' => $request['is_active'],
        ]);
        return redirect()->route('admin.services.index')->with('successMessage', 'Succssfully Created');
    }
    public function edit(Service $service)
    {
        $service = new ServiceResource($service);
        // return $service;
        return Inertia::render('Admin/Service/Edit', [
            "service" => $service,
        ]);
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        $user = Service::find($id);
        $user->name = $request['name'];
        $user->fee = $request['fee'];
        $user->is_active = $request['is_active'];
        $user->save();
        return redirect()->route('admin.services.index')->with('successMessage', "Successfully Updated");
    }
    public function destroy($id)
    {
        $service = Service::find($id);
        $service->bookings()->detach();
        $service->delete();
        return back()->with('successMessage', "Successfully Deleted");
    }
}
