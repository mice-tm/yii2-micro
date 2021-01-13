<?php


namespace app\services;


use app\forms\DiscountForm;
use app\models\queries\DiscountQuery;
use app\models\Discount;

interface DiscountServiceInterface
{
    public function generate(DiscountForm $form, DiscountQuery $discountQuery): Discount;
    
    public function getUniqueCode(): string;
    
    public function apply(array $data, DiscountQuery $query);
}
