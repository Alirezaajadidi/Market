<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class PanelController extends Controller
{
    public function index()
    {
//        $month = 12;

//      return  DB::table('payments')->whereMonth('created_at month ,count(*) published');

//        return Payment::select('MONTH(created_at) month , count(*) published')
//            ->where('created_at', '>', Carbon::now()->subMonth($month))
//            ->wherePayment(true)
//            ->groupBy('month')
//            ->latest()
//            ->get();
//
//        $labels = $this->getLastMonths($month);


        return view('Admin.panel');
    }

    public function uploadImageSubject()
    {
        $this->validate(request(), [
            'upload' => 'required|mimes:jpeg,png,bmp',
        ]);

        $year = Carbon::now()->year;
        $imagePath = "/upload/images/{$year}/";

        $file = request()->file('upload');
        $filename = $file->getClientOriginalName();

        if (file_exists(public_path($imagePath) . $filename)) {
            $filename = Carbon::now()->timestamp . $filename;
        }

        $file->move(public_path($imagePath), $filename);
        $url = $imagePath . $filename;

        return "<script>window.parent.CKEDITOR.tools.callFunction(1 , '{$url}' , '')</script>";
    }

//    private function getLastMonths($month)
//    {
//        for ($i = 0; $i < $month; $i++) {
//            $labels[] = Jalalian::forge(Carbon::now()->subMonths($i))->format('%B');
//        }
//        dd($labels);
//    }
}
