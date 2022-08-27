<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BookingSetting;
use App\Filters\BookingFilters;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\BookingResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\CustomerResource;
use App\Mail\MakeBooking;
use App\Mail\PaidBooking;

class BookingCrudController extends Controller
{
    public function index()
    {
        $bookings = BookingResource::collection(Booking::filter(app(BookingFilters::class))->with(['customer', 'author', 'services'])->paginate(request()->perPage ?? 10));
        // return $bookings;
        return Inertia::render('Backend/Booking/Index', [
            "bookings" => $bookings
        ]);
    }
    public function create()
    {
        // return BookingSetting::min('from');
        // return  BookingSetting::where([['from', '<=', 6], ['to', '>=', 6]])->first();
        return Inertia::render('Backend/Booking/Create', [
            "customers" => CustomerResource::collection(Customer::all()),
            'services' => ServiceResource::collection(Service::active()->get()),
            "min_duration" => BookingSetting::min('from'),
            "max_duration" => BookingSetting::max('to')
        ]);
    }
    public function getTotalAmount(Request $request)
    {
        if($request->total_duration) {

            $setting_record = BookingSetting::where([['from', '<=', $request->total_duration], ['to', '>=', $request->total_duration]])->first();
            if(is_null($setting_record)) {
                return response()->json([
                    "success" => false,
                    "total" => 0,
                ]);
            } else{
                $total = $setting_record->fee;
                if($request->services) {
                    $total += Service::whereIn('id', $request->services)->sum('fee');
                }
                return response()->json([
                    "success" => true,
                    "total" => $total,
                ]);
            }

        } else {
            return response()->json([
                "success" => false,
                "total" => 0,
            ]);
        }
    }
    public function store(Request $request)
    {
        // dd($request->all());
        if($request->existing_customer) {
            $request->validate([
                "customer_id" => "required",
                "car_no"=> "required",
                "duration" => "required",
                "duration_type" => "required",
            ]);
            $total_duration = $this->getTotalDuration($request->duration, $request->duration_type);
            $setting_record = BookingSetting::where([['from', '<=', $total_duration], ['to', '>=', $total_duration]])->first();
            if(is_null($setting_record)){
                return back()->with('errorMessage', 'Invalid Duration');
            }
            $booking = new Booking();
            $booking->customer_id = $request->customer_id;
            $booking->duration = $request->duration;
            $booking->duration_type = $request->duration_type;
            $booking->note = $request->note;
            $booking->car_no = $request->car_no;
            $booking->user_by = auth()->user()->id;
            $booking->total = $this->getTotalFee($setting_record->fee, $request->services);
            $booking->save();
            if($request->services) {
                $booking->services()->sync($request->services);
            }
            Mail::to(Customer::find($request->customer_id)->email)->send(new MakeBooking($booking));

            return redirect()->route('bookings.index')->with('successMessage', "Created Successfully");
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:60'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
                'car_no' => 'required',
                "duration" => "required",
                "duration_type" => "required",
            ]);
            $total_duration = $this->getTotalDuration($request->duration, $request->duration_type);
            $setting_record = BookingSetting::where([['from', '<=', $total_duration], ['to', '>=', $total_duration]])->first();
            if(is_null($setting_record)){
                return back()->with('errorMessage', 'Invalid Duration');
            }
            $user = Customer::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'user_by' => auth()->user()->id
            ]);
            $booking = new Booking();
            $booking->customer_id = $user->id;
            $booking->duration = $request->duration;
            $booking->duration_type = $request->duration_type;
            $booking->car_no = $request->car_no;
            $booking->note = $request->note;
            $booking->user_by = auth()->user()->id;
            $booking->total = $this->getTotalFee($setting_record->fee, $request->services);
            $booking->save();
            if($request->services) {
                $booking->services()->sync($request->services);
            }
            Mail::to($user->email)->send(new MakeBooking($booking));

            return redirect()->route('bookings.index')->with('successMessage', "Created Successfully");
        }

    }

    public function edit($id)
    {
        // return $booking;
        $booking = Booking::where('id', $id)->with('services')->first();
        if($booking->has_paid) {
            return back()->with('errorMessage', "Cannot edit this record");
        }
        // return new BookingResource($booking);
        return Inertia::render('Backend/Booking/Edit', [
            "booking" => new BookingResource($booking),
            "customers" => CustomerResource::collection(Customer::all()),
            'services' => ServiceResource::collection(Service::active()->get()),
            "min_duration" => BookingSetting::min('from'),
            "max_duration" => BookingSetting::max('to')
        ]);
    }
    public function update($id, Request $request)
    {
        $booking  = Booking::find($id);
        $request->validate([

            'car_no' => 'required',
            "duration" => "required",
            "duration_type" => "required",
        ]);
        $total_duration = $this->getTotalDuration($request->duration, $request->duration_type);
        $setting_record = BookingSetting::where([['from', '<=', $total_duration], ['to', '>=', $total_duration]])->first();
        if(is_null($setting_record)){
            return back()->with('errorMessage', 'Invalid Duration');
        }
        $booking->car_no = $request->car_no;
        $booking->duration = $request->duration;
        $booking->duration_type = $request->duration_type;
        $booking->save();
        if($request->services) {
            $booking->services()->sync($request->services);
        }

        return redirect()->route('bookings.index')->with('successMessage', "Updated Successfully");
    }
    public function getTotalDuration($duration, $type)
    {
        return $type== 'week' ? $duration*7 : $duration*1;
    }
    public function getTotalFee($setting_fee, $services)
    {
        if($services) {
            $setting_fee += Service::whereIn('id', $services)->sum('fee');
        }
        return $setting_fee;
    }
    public function destroy($id)
    {

        $booking = Booking::find($id);
        $booking->services()->detach();
        $booking->delete();
        return back()->with('succesMessage', 'Successfully Deleted');
    }
    public function paid($id)
    {
        $booking = Booking::find($id);
        $booking->has_paid = true;
        $booking->save();
        if($booking->customer) {
            // dd("hello");
            Mail::to($booking->customer->email)->send(new PaidBooking($booking));
        }
        return back()->with('successMessage', 'Successfully Saved');
    }
}
