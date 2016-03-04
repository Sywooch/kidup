<?php
namespace codecept\muffins;

use Faker\Factory as Faker;

class InvoiceMuffin extends \booking\models\Invoice
{
    public function definitions()
    {
        $faker = Faker::create();
        return [];
    }
}
