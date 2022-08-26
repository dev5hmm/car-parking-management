<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\BookingSetting;
use Illuminate\Support\Facades\DB;

class BookingSettingContoller extends Controller
{
    public function index()
    {
        $settings = BookingSetting::get(['from', 'to', 'fee'])->toArray();
        // return $settings;
        return Inertia::render('Admin/Setting', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $requestAll = $request->all();
        $requestAll[0]['from'] = config('day-range.min_day');

        foreach($request->all() as $index => $value) {
            if($value['to'] && $value['fee'] > 0) {
                if($index != 0) {
                    $requestAll[$index]['from'] = $requestAll[$index-1]['to'] + 1;
                    if($value['from'] > $value['to']) {
                        return back()->with('errorMessage', 'From must be smaller than To');
                    }
                    if($value['to'] > config('day-range.max_day')) {
                        return back()->with('errorMessage', "Maximum duration is ". config('day-range.max_day'));
                    }
                }
            } else {
                return back()->with('errorMessage', 'You need to enter all valid value');
            }
        }
        DB::beginTransaction();
        try{
            BookingSetting::getQuery()->delete();
            foreach($requestAll as $index => $value) {
                $setting = new BookingSetting();
                $setting->from = $value['from'];
                $setting->to = $value['to'];
                $setting->fee = $value['fee'];
                $setting->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Something went wrong. Please try again later. ');
        }
        // dd("hello");
        DB::commit();
        return redirect()->back()->with('successMessage', 'Successfully Saved');



    }
}
