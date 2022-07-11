@component('Home.panel.master')

<h4>history</h4>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">مقدار پرداخت</th>
            <th scope="col">وضعیت پرداخت</th>

        </tr>
        </thead>
        <tbody>
        @foreach($payments as $payment)
            <tr>
                <td>{{$payment->price}} تومان</td>
                <td>{{$payment->payment == 1 ? 'موفق' : 'ناموفق'}}</td>

            </tr>
        @endforeach
        </tbody>
    </table>

@endcomponent
