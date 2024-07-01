<?php

use App\Models\OIAI;
use GraphQL\Type\Definition\Type;

$oiaiMutations = [
    'addoioi'=>[
        'type' => $orderitemType,
        'args'=>[
            'orderitem_id' =>Type::nonNull(Type::int()),
            'attributeitem_id'=>Type::int(),

        ],
        'resolve'=>function($root,$args){
            $orderitem = new OIAI([
                'orderitem_id'=>$args['orderitem_id'],
                'attributeitem_id'=>$args['attributeitem_id'] ?? null,
            ]);
             $orderitem->save();
             return $orderitem->toArray();
        }
    ]
];
