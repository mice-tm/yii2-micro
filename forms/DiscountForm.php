<?php

namespace app\forms;

class DiscountForm extends \app\models\Discount
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discount'], 'numeric'],
//            [['code', 'state'], 'string', 'max' => 45],
        
        ];
    }
}
