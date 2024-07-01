<?php

use GraphQL\Type\Definition\ObjectType;

require('mutation/CategoryMutation.php');
require('mutation/ProductMutation.php');
require('mutation/AttributeMutation.php');
require('mutation/GalleryMutation.php');
require('mutation/OrderItemMutation.php');
require('mutation/OrderMutation.php');
require('mutation/OIAIMutation.php');

$mutations = array();
$mutations += $categoryMutations;
$mutations += $productMutations;
$mutations += $attributeMutations;
$mutations += $galleryMutations;
$mutations += $orderitemMutations;
$mutations += $orderMutations;
$mutations += $oiaiMutations;


$rootmutation = new ObjectType([
    'name'=>'Mutation',
    'fields'=> $mutations
]);