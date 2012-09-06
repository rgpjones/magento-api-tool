<?php
require_once 'RemoteService.php';

try {
    $questions = array(
        array(
            'param' => 'email',
            'question' => 'Email',
            'type' => 'text'
        ),
        array(
            'param' => 'firstname',
            'question' => 'First Name',
            'type' => 'text'
        ),
        array(
            'param' => 'lastname',
            'question' => 'Last Name',
            'type' => 'text'
        ),
        array(
            'param' => 'password',
            'question' => 'Password',
            'type' => 'password'
        ),
        array(
            'param' => 'website_id',
            'question' => 'Website ID',
            'type' => 'text'
        ),
    );

    $service = new RemoteService();

    $data = array();
    foreach ($questions as $question) {
        $data[$question['param']] = $service->ask($question['question'], $question['type']);
    }

    $result = $service->customerCustomerCreate(array('customerData' => $data));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
