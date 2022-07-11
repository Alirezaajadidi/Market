<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Episode;
use App\Models\Learning;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use SoapClient;

class CourseController extends Controller
{
    protected $MerchantID = '935ddc35-e66e-46c2-aa45-399fbdd455e1'; //Required

    public function index()
    {
        $categories = null;
        return view('Home.all-courses', compact('categories' ));
    }

    public function filtering(Request $request)
    {

        if ($request->category == 'all') {
            return redirect('course' );
        }

        $categories = Category::find($request->category);
        return view('Home.all-courses', compact( 'categories'));
    }

    public function single(Course $course)
    {
        $courses = Course::find($course->id);
        $course->increment('viewCount');
        $comments = $course->comments()->where('approved', 1)->latest()->where('parent_id', 0)->with('comments')->latest()->get();
        return view('Home.courses', compact('courses', 'comments'));
    }


    public function payment()
    {
        $this->validate(request(), [
            'course_id' => 'required'
        ]);

        $course = Course::findOrFail(request('course_id'));

        if (auth()->user()->checkLearning($course)) {
            alert()->error('شما قبلا در این دوره ثبت نام کرده اید', 'دقت کنید')->persistent('خیلی خوب');
            return back();
        }

        if ($course->price == 0 && $course->type == 'vip') {
            alert()->error('این دوره قابل خریداری توسط شما نیست', 'دقت کنید')->persistent('خیلی خوب');
            return back();
        }

        $price = $course->price;

        $Description = 'توضیحات تراکنش تستی'; // Required
        $Email = auth()->user()->email; // Optional
        $CallbackURL = 'http://localhost:8000/course/payment/checker'; // Required

        $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $client->PaymentRequest(
            [
                'MerchantID' => $this->MerchantID,
                'Amount' => $price,
                'Description' => $Description,
                'Email' => $Email,
                'CallbackURL' => $CallbackURL,
            ]
        );

        //Redirect to URL You can do it also by creating a form
        if ($result->Status == 100) {

            auth()->user()->payments()->create([
                'resnumber' => $result->Authority,
                'price' => $price,
                'course_id' => $course->id
            ]);
            return redirect('https://www.zarinpal.com/pg/StartPay/' . $result->Authority);
        } else {
            echo 'ERR: ' . $result->Status;
        }
    }


    public function checker()
    {
        $Authority = request('Authority');

        $payment = Payment::whereResnumber($Authority)->firstOrFail();
        $course = Course::findOrFail($payment->course_id);


        if (request('Status') == 'OK') {
            $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => $this->MerchantID,
                    'Authority' => $Authority,
                    'Amount' => $payment->price,
                ]
            );

            if ($result->Status == 100) {
                if ($this->AddUserForLearning($payment, $course)) {
                    alert()->success('عملیات مورد نظر با موفقیت انجام شد', 'با تشکر');
                    return redirect($course->path());
                }
            } else {
                echo 'Transaction failed. Status:' . $result->Status;
            }
        } else {
            echo 'Transaction canceled by user';
        }

    }

    protected function AddUserForLearning($payment, $course)
    {
        $payment->update([
            'payment' => 1
        ]);

        Learning::create([
            'user_id' => auth()->user()->id,
            'course_id' => $course->id
        ]);

        return true;
    }

    public function download(Episode $episode)
    {
        $hash = 'fds@#T@#56@sdgs131fasfq' . $episode->id . \request()->ip() . \request('t');

        if (Hash::check($hash, \request('mac'))) {
        } else {
//            return response()->download(storage_path($episode->videoUrl));
            $exists = storage_path($episode->videoUrl);
            return response()->download($exists);
        }


    }


}
