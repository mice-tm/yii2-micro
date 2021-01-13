<?php

namespace app\services;

use app\forms\DiscountForm;
use app\models\queries\DiscountQuery;
use app\models\Discount;

class DiscountService implements DiscountServiceInterface
{
    
    public function generate(DiscountForm $form, DiscountQuery $discountQuery): Discount
    {
        $model = $discountQuery->createModel();
        $model->load($form->toArray(), '');
        $model->code = $this->getUniqueCode();
        $model->state = 'active';
        $model->save();
        return $model;
    }
    
    public function getUniqueCode(): string
    {
        return substr(md5(microtime()), 0, 10);
    }
    
    public function apply(array $data, DiscountQuery $query)
    {
        if (empty($data['items']) || empty($data['code'])) {
            return null;
        }
        
        $discount = $query->where(['code' => $data['code']])->one();
        $total = $this->calculateTotal($data['items']);
        $data['items'] = array_map(function ($item) use ($discount, $total) {
            $percent = $item['price'] / $total;
            $item['price_with_discount'] = $item['price'] - $this->calculateDiscount($discount->discount, $percent);
            return $item;
        }, $data['items']);
        return $data;
    }
    
    protected function calculateDiscount($amount, $discount)
    {
        return  (int) $amount * $discount;
    }
    
    protected function calculateTotal($items)
    {
        return array_reduce($items, function ($carry, $item) {
            return $carry + $item['price'];
        }, 0);
    }
    
}
