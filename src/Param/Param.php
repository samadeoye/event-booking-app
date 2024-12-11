<?php
namespace KpakpandoEventsBooking\Param;

class Param
{
    public static function getRequestParams($action)
    {
        $data = [];
        switch($action)
        {
            case 'register':
                $data = [
                    'fname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'First Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'lname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Last Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,200],
                        'label' => 'Email',
                        'required' => true,
                        'type' => 'string',
                        'is_email' => true
                    ],
                    'password1' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password',
                        'required' => true
                    ],
                    'password2' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Confirm Password',
                        'required' => true
                    ],
                ];
            break;

            case 'login':
                $data = [
                    'email' => [
                        'method' => 'post',
                        'length' => [13,200],
                        'label' => 'Email',
                        'required' => true,
                        'type' => 'string',
                        'is_email' => true
                    ],
                    'password' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password',
                        'required' => true
                    ]
                ];
            break;

            case 'forgotPassVerifyEmail':
                $data = [
                    'email' => [
                        'method' => 'post',
                        'length' => [13,100],
                        'label' => 'Email',
                        'required' => true
                    ]
                ];
            break;

            case 'resetpassword':
                $data = [
                    'token' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Token',
                        'required' => true
                    ],
                    'password' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password',
                        'required' => true
                    ],
                    'passwordConfirm' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password Confirm',
                        'required' => true
                    ]
                ];
            break;

            case 'changepassword':
                $data = [
                    'currentPassword' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Current Password',
                        'required' => true
                    ],
                    'newPassword' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'New Password',
                        'required' => true
                    ],
                    'confirmPassword' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Confirm Password',
                        'required' => true
                    ]
                ];
            break;

            case 'updateprofile':
                $data = [
                    'fname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'First Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'lname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Last Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,200],
                        'label' => 'Email',
                        'required' => true,
                        'type' => 'string',
                        'is_email' => true
                    ]
                ];
            break;
            
            case 'addevent':
                $data = [
                    'title' => [
                        'method' => 'post',
                        'length' => [4,200],
                        'label' => 'Title',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'description' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Description',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'dateType' => [
                        'method' => 'post',
                        'length' => [4,15],
                        'label' => 'Date Type',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'date' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Date',
                        'required' => false,
                        'type' => 'string'
                    ],
                    'dateFrom' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Date From',
                        'required' => false,
                        'type' => 'string'
                    ],
                    'dateTo' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Date To',
                        'required' => false,
                        'type' => 'string'
                    ],
                    'time' => [
                        'method' => 'post',
                        'length' => [4,10],
                        'label' => 'Time',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'price' => [
                        'method' => 'post',
                        'length' => [2,40],
                        'label' => 'Price',
                        'required' => true
                    ],
                    'venue' => [
                        'method' => 'post',
                        'length' => [5,250],
                        'label' => 'Venue',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'slots' => [
                        'method' => 'post',
                        'length' => [1,7],
                        'label' => 'Slots Available'
                    ]
                ];
            break;

            case 'updateevent':
                $data = [
                    'id' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Event', //event id
                        'required' => true,
                        'type' => 'string'
                    ],
                    'title' => [
                        'method' => 'post',
                        'length' => [4,200],
                        'label' => 'Title',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'description' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Description',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'dateType' => [
                        'method' => 'post',
                        'length' => [4,15],
                        'label' => 'Date Type',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'date' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Date',
                        'required' => false,
                        'type' => 'string'
                    ],
                    'dateFrom' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Date From',
                        'required' => false,
                        'type' => 'string'
                    ],
                    'dateTo' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Date To',
                        'required' => false,
                        'type' => 'string'
                    ],
                    'time' => [
                        'method' => 'post',
                        'length' => [4,10],
                        'label' => 'Time',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'price' => [
                        'method' => 'post',
                        'length' => [2,40],
                        'label' => 'Price',
                        'required' => true
                    ],
                    'venue' => [
                        'method' => 'post',
                        'length' => [5,250],
                        'label' => 'Venue',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'slots' => [
                        'method' => 'post',
                        'length' => [1,7],
                        'label' => 'Slots Available'
                    ]
                ];
            break;
            
            case 'bookticket':
                $data = [
                    'eventId' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Event',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'firstName' => [
                        'method' => 'post',
                        'length' => [3,50],
                        'label' => 'First Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'lastName' => [
                        'method' => 'post',
                        'length' => [3,50],
                        'label' => 'Last Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,200],
                        'label' => 'Email',
                        'required' => true,
                        'type' => 'string',
                        'is_email' => true
                    ],
                    'phone' => [
                        'method' => 'post',
                        'length' => [6,14],
                        'label' => 'Phone',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'age' => [
                        'method' => 'post',
                        'length' => [1,2],
                        'label' => 'Age',
                        'required' => true
                    ],
                    'gender' => [
                        'method' => 'post',
                        'length' => [4,6],
                        'label' => 'Gender',
                        'required' => true
                    ],
                    'ticketQty' => [
                        'method' => 'post',
                        'length' => [1,3],
                        'label' => 'Qty',
                        'required' => true
                    ],
                    'ticketAmt' => [
                        'method' => 'post',
                        'length' => [1,10],
                        'label' => 'Amount',
                        'required' => true
                    ]
                ];
            break;
        }
        return $data;
    }
}