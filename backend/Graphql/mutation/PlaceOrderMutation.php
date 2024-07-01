<?php

use App\Models\PlaceOrder;
use GraphQL\Type\Definition\Type;

$placeorderMutations = [
    'addplaceorder' =>[
        'type'=> $placeorderType,
        'args'=>[
            'address'=>Type::nonNull(Type::string()),
            'payment_method'=> Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $placeorder = new PlaceOrder([
                'address' => $args['address'],
                'payment_method'=> $args['payment_method']
            ]);
            $placeorder -> save();
            return $placeorder -> toArray();
        }
    ],
    'editplaceorder'=>[
        'type' => $placeorderType,
        'args' => [
            'id'=> Type::nonNull(Type::int()),
            'address'=>Type::nonNull(Type::string()),
            'payment_method'=> Type::nonNull(Type::string()),
            'order_id'=>Type::int()
        ],
        'resolve'=>function($root, $args){
            $placeorder = PlaceOrder::find($args['id']);
            $placeorder->address = isset($args['address'])?$args['address']:$placeorder->address;
            $placeorder->payment_method = isset($args['payment_method'])?$args['payment_method']:$placeorder->payment_method;
            $placeorder->order_id = isset($args['order_id'])?$args['order_id']:$placeorder->order_id;
            $placeorder->save();
            return $placeorder->toArray();
        }
    ]
];