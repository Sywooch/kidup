<?php
namespace tests\muffins;

use Faker\Factory as Faker;

class Invoice extends \booking\models\Invoice
{
    public function definitions()
    {
        $faker = Faker::create();
        return [];
    }
}
