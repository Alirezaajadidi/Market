<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SoapClient;

class UserPanelController extends Controller
{
    protected $MerchantID = '935ddc35-e66e-46c2-aa45-399fbdd455e1'; //Required

    public function index()
    {
        return view('Home.panel.index');
    }

    public function history()
    {
        $payments = auth()->user()->payments()->latest()->paginate(20);
        return view('Home.panel.history', compact('payments'));
    }

    public function vip()
    {
        return view('Home.panel.vip');
    }

    public function vipPayment()
    {
        $this->validate(request(), [
            'plan' => 'required'
        ]);

        switch (request('plan')) {
            case 1:
                $price = 100;
                break;
            case 3:
                $price = 300;
                break;
            default :
                $price = 120;
        }


        $Description = 'توضیحات تراکنش تستی'; // Required
        $Email = auth()->user()->email; // Optional
        $CallbackURL = url(route('user.panel.vip.checker')); // Required

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
            ]);

            return redirect('https://www.zarinpal.com/pg/StartPay/' . $result->Authority);
        } else {
            echo 'ERR: ' . $result->Status;
        }
    }

    public function vipChecker()
    {
        $Authority = request('Authority');

        $payment = Payment::whereResnumber($Authority)->firstOrFail();

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
                if ($this->checkPayment($payment)) {
                    alert()->success('عملیات مورد نظر با موفقیت انجام شد', 'با تشکر');
                    return redirect(route('user.panel'));
                }
            } else {
                echo 'Transaction failed. Status:' . $result->Status;
            }
        } else {
            echo 'Transaction canceled by user';
        }
    }

    public function checkPayment($payment)
    {
        $payment->update([
            'payment' => 1
        ]);

        switch ($payment->price) {
            case 10000 :
                $time = 1;
//                $type = 'month';
                break;
            case 30000 :
                $time = 3;
//                $type = '3month';
                break;
            case 120000 :
                $time = 12;
//                $type = '12mo';
                break;
        }

        $user = $payment->user;
        $viptime = $user->isActive() ? Carbon::parse($user->viptime) : Carbon::now();
        $user->update([
            'viptime' => $viptime->addMonths($time),
        ]);

        return true;
    }

}
