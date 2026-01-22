<?php

abstract class PaymentGateway
{
    protected $gatewayName;

    public function __construct($name)
    {
        $this->gatewayName = $name;
    }


    abstract public function pay($amount);


    public function paymentInfo()
    {
        return "Processing payment using {$this->gatewayName}";
    }
}

class Razorpay extends PaymentGateway
{
    public function pay($amount)
    {
        return "₹{$amount} paid successfully via Razorpay";
    }
}


class Paytm extends PaymentGateway
{
    public function pay($amount)
    {
        return "₹{$amount} paid successfully via Paytm";
    }
}


class Stripe extends PaymentGateway
{
    public function pay($amount)
    {
        return "$ {$amount} paid successfully via Stripe";
    }
}


function processPayment(PaymentGateway $gateway, $amount)
{
    echo $gateway->paymentInfo() . "<br>";
    echo $gateway->pay($amount) . "<br><br>";
}


$razorpay = new Razorpay("Razorpay");
$paytm    = new Paytm("Paytm");
$stripe  = new Stripe("Stripe");


processPayment($razorpay, 500);
processPayment($paytm, 1200);
processPayment($stripe, 20);
