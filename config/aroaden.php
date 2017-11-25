<?php

return [

    'routes' => [
        'company' => 'company',
        'appointments' => 'Citas',
        'patients' => 'Pacientes',
        'staff' => 'Personal',
        'sevices' => 'Servicios',
        'accounting' => 'Contable',
        'invoices' => 'invoices',
        'budgets' => 'Presup',
        'settings' => 'settings',
        'pays' => 'pays',
        'treatments' => 'Trapac',

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
            'type' => 'medio'
        ],
        [
            'username' => 'normal',
            'password' => 'normal',
            'type' => 'normal'
        ],
        [
            'username' => 'medio',
            'password' => 'medio',
            'type' => 'medio'
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
