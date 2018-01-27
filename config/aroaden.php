<?php

return [

    'files' => [
        'file_max_size' => 3,
    ],

    'routes' => [
        'company' => 'company',
        'appointments' => 'appointments',
        'patients' => 'patients',
        'staff' => 'staff',
        'services' => 'services',
        'accounting' => 'accounting',
        'users' => 'users',
        'invoices' => 'invoices',
        'budgets' => 'budgets',
        'settings' => 'settings',
        'pays' => 'pays',
        'treatments' => 'treatments',
    ],

    'tax_types' => [
        "0%" => 0,
        "4%" => 4,
        "10%" => 10,
        "21%" => 21
    ],

    'default_users' => [
        [
            'username' => 'admin',
            'password' => 'admin',
            'type' => 'normal'
        ],
        [
            'username' => 'normal',
            'password' => 'normal',
            'type' => 'normal'
        ],
        [
            'username' => 'basic',
            'password' => 'basic',
            'type' => 'basic'
        ]
    ],

    'settings_fields' => [
        [ 
           'name'  => "company_name",
           'type'  => "text",
           'maxlength'  => "111",
           'col'  => "col-sm-4",
           'autofocus'  => "autofocus",
           'required'  => "required",
           'pattern'  => "",
           'rows'  => "",
        ],
        [ 
           'name'  => "company_address",
           'type'  => "text",
           'maxlength'  => "111",
           'col'  => "col-sm-4",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "",
           'rows'  => "",
        ],
        [ 
           'name'  => "company_city",
           'type'  => "text",
           'maxlength'  => "111",
           'col'  => "col-sm-4",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "",
           'rows'  => "",
        ],
        [ 
           'name'  => "company_nif",
           'type'  => "text",
           'maxlength'  => "22",
           'col'  => "col-sm-3",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "",
           'rows'  => "",
        ],
        [ 
           'name'  => "company_tel1",
           'type'  => "text",
           'maxlength'  => "11",
           'col'  => "col-sm-3",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "[0-9 -]{0,11}",
           'rows'  => "",
        ],
        [ 
           'name'  => "company_tel2",
           'type'  => "text",
           'maxlength'  => "11",
           'col'  => "col-sm-3",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "[0-9 -]{0,11}",
           'rows'  => "",
        ],
        [ 
           'name'  => "company_tel3",
           'type'  => "text",
           'maxlength'  => "11",
           'col'  => "col-sm-3",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "[0-9 -]{0,11}",
           'rows'  => "",
        ],
        [ 
           'name'  => "company_notes",
           'type'  => "textarea",
           'maxlength'  => "",
           'col'  => "col-sm-12",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "",
           'rows'  => "4",
        ],
        [ 
           'name'  => "invoice_text",
           'type'  => "textarea",
           'maxlength'  => "",
           'col'  => "col-sm-12",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "",
           'rows'  => "4",
        ],
        [ 
           'name'  => "budget_text",
           'type'  => "textarea",
           'maxlength'  => "",
           'col'  => "col-sm-12",
           'autofocus'  => "",
           'required'  => "",
           'pattern'  => "",
           'rows'  => "4",
        ]
    ]
];
