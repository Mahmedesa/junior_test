<?php

use App\Models\OIAI;
use App\Models\OrderItem;
use GraphQL\Type\Definition\Type;

$orderitemMutations = [
    'addorderitem' => [
        'type' => $orderitemType, // Replace with your OrderItem type
        'args' => [
            'product_id' => Type::nonNull(Type::int()),
            'attributes' => Type::listOf(Type::int()), // List of attribute item IDs
            'quantity' => Type::nonNull(Type::int()),
        ],
        'resolve' => function ($root, $args) {
            // Example logic to save OrderItem and associated OIAIs
            // Replace with your actual database operations using PDO, Doctrine, etc.

            // 1. Save the OrderItem
            $orderItem = new OrderItem();
            $orderItem->product_id = $args['product_id'];
            $orderItem->quantity = $args['quantity'];
            $orderItem->save();

            // 2. Get the ID of the saved OrderItem
            $orderItemId = $orderItem->id;

            // 3. Save each attribute item as OIAI (OrderItemAttributeItem)
            foreach ($args['attributes'] as $attributeItemId) {
                // Replace with your actual model and save operation
                $oiai = new OIAI(); // Assuming OIAI is your model for OrderItemAttributeItem
                $oiai->orderitem_id = $orderItemId;
                $oiai->attributeitem_id = $attributeItemId;
                $oiai->save();
            }

            // 4. Return the created OrderItem (or any necessary data)
            return [
                'id' => $orderItemId,
                'product_id' => $args['product_id'],
                'quantity' => $args['quantity'],
                // Add other fields as needed
            ];
        },
    ],
    'editorderitem' => [
        'type' => $orderitemType,
        'args' => [
            'id' => Type::nonNull(Type::int()),
            'quantity' => Type::nonNull(Type::int()),
            'attributes' => Type::listOf(Type::int()),
        ],
        'resolve' => function ($root, $args) {
            $orderItem = OrderItem::find($args['id']);
            if (!$orderItem) {
                throw new \Exception('OrderItem not found');
            }

            $orderItem->quantity = $args['quantity'];
            $orderItem->save();

            OIAI::where('orderitem_id', $orderItem->id)->delete();

            foreach ($args['attributes'] as $attributeItemId) {
                $oiai = new OIAI();
                $oiai->orderitem_id = $orderItem->id;
                $oiai->attributeitem_id = $attributeItemId;
                $oiai->save();
            }

            return $orderItem->toArray();
        }
    ],
    // Add this debug statement to ensure that the $args['id'] is being passed correctly
    'deleteorderitem' => [
        'type' => $orderitemType,
        'args' => [
            'id' => Type::nonNull(Type::int()),
        ],
        'resolve' => function ($root, $args) {
            error_log('Deleting order item with id: ' . $args['id']);

            $orderItem = OrderItem::find($args['id']);
            if (!$orderItem) {
                throw new \Exception('OrderItem not found');
            }

            $orderItem->delete();
            return $orderItem->toArray();
        }
    ]

];
