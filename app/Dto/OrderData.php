<?php
namespace App\Dto;

use Spatie\LaravelData\Dto;

class OrderData extends Dto 
{
    public string $customer_name;
    public string $customer_email;
    
}