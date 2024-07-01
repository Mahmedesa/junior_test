<?php

use GraphQL\GraphQL;
use GraphQL\Type\Schema;

// Include the necessary files
require('type.php'); 
require('query.php');
require('mutation.php');

// Create the schema
$schema = new Schema([
    'query' => $rootquery,
    'mutation' => $rootmutation
]);

try {
    // Read raw input
    $rawInput = file_get_contents('php://input');
    // Decode JSON input
    $input = json_decode($rawInput, true);

    // Check if the query is set
    if (isset($input['query'])) {
        $query = $input['query'];
    } else {
        throw new Exception('Query not provided');
    }

    // Check if variables are set
    if (isset($input['variables'])) {
        $variableValues = $input['variables'];
    } else {
        $variableValues = null;
    }

    // Debug log the input for verification
    error_log('GraphQL Query: ' . $query);
    error_log('GraphQL Variables: ' . json_encode($variableValues));
    
    // Execute the query
    $result = GraphQL::executeQuery($schema, $query, null, null, $variableValues);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'errors' => [
            ['message' => $e->getMessage()]
        ]
    ];
}

// Set response header
header('Content-Type: application/json');
// Return the output as JSON
echo json_encode($output);
