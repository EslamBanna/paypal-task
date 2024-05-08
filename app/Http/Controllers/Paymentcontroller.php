<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class Paymentcontroller extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_SANDBOX_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_SANDBOX_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }
    public function payView()
    {
        try{
        $item['item_description'] = ' Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi, labore officia libero, magni dolores laboriosam nisi atque omnis qui impedit saepe accusamus deleniti, voluptas doloribus provident. Debitis, non! Suscipit, ipsa?        ';
        $item['item_name'] = 'Nike Shoes';
        $item['item_price'] = 10;
        $item['item_currency'] = 'USD';
        $item['item_title'] = 'Nike Shoes ';
        $item['item_qty'] = 1;
        $item['item_image'] = asset('assets/shoes.png');
        return view('payment.item', compact('item'));
        }catch(\Exception $e){
            notify()->warning('Something Went Wrong, Please Try Later :(');
        }
    }

    public function pay(Request $request)
    {
        try {
            $required_money = $request->item_qty * $request->item_price;
            if ($required_money <= 0) {
                notify()->warning('Invalid Amount');
                return redirect()->route('payment.view');
            }
            $payment = new Payment();
            $payment->payment_id = '';
            $payment->payer_id = '';
            $payment->amount = $required_money;
            $payment->currency = env('PAYPAL_CURRENCY');
            $payment->payment_status = 'created';
            $payment->payer_email = '';
            $payment->user_id = auth()->user()->id;
            $payment->item_id = 1;
            $payment->save();
            $response = $this->gateway->purchase(array(
                'amount' => $required_money,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success-pay/' . $payment->id),
                'cancelUrl' => url('error-pay/' . $payment->id)
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                notify()->warning($response->getMessage());
                return redirect()->route('payment.view');
            }
        } catch (\Exception $e) {
            notify()->warning('Something Went Wrong, Please Try Later :(');
            return redirect()->route('payment.view');
        }
    }

    public function successPay($payment_id, Request $request)
    {
        $payment = Payment::find($payment_id);
        try {
            if ($request->input('paymentId') && $request->input('PayerID')) {

                $transaction = $this->gateway->completePurchase(array(
                    'payer_id' => $request->input('PayerID'),
                    'transactionReference' => $request->input('paymentId')
                ));

                $response = $transaction->send();
                if ($response->isSuccessful()) {
                    $arr = $response->getData();
                    $payment->payment_id = $arr['id'];
                    $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                    $payment->amount = $arr['transactions'][0]['amount']['total'];
                    $payment->currency = env('PAYPAL_CURRENCY');
                    $payment->payment_status = $arr['state'];
                    $payment->payer_email = $arr['payer']['payer_info']['email'];
                    $payment->save();
                    notify()->emotify('success','Payment is Successfull. Your Transaction Id is : ' . $arr['id']);
                    return redirect()->route('payment.view');
                } else {
                    $payment->payment_status = 'Error on Transaction';
                    $payment->save();
                    notify()->warning('Payment is Error : ' . $response->getMessage());
                    return redirect()->route('payment.view');
                }
            } else {
                $payment->payment_status = 'Error on Transaction';
                $payment->save();
                notify()->warning('Payment declined!!');
                return redirect()->route('payment.view');
            }
        } catch (\Exception $e) {
            $payment->payment_status = 'Error on Transaction';
            $payment->save();
            notify()->warning('Payment declined!!');
            return redirect()->route('payment.view');
        }
    }

    public function errorPay($payment_id)
    {
        notify()->warning('User declined the payment!');
        return redirect()->route('payment.view');
    }
}
