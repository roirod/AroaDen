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
      'staff_positions' => 'staff_positions',        
      'services' => 'services',
      'accounting' => 'accounting',
      'users' => 'users',
      'invoices' => 'invoices',
      'budgets' => 'budgets',
      'settings' => 'settings',
      'pays' => 'pays',
      'treatments' => 'treatments'
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
          'type' => 'normal',
          'full_name' => 'admin'
      ],
      [
          'username' => 'normal',
          'password' => 'normal',
          'type' => 'normal',
          'full_name' => 'normal'
      ],
      [
          'username' => 'basic',
          'password' => 'basic',
          'type' => 'basic',
          'full_name' => 'basic'
      ]
    ],

    'permissions' => [
      "normal" => [
        'patients'  => [
          'create'  => true,
          'edit'  => true,
          'delete'  => false,
          'deleteFile'  => true,
          'editRecord'  => true,
          'uploadOdontogram'  => true,
          'resetOdontogram'  => true,
          'uploadProfilePhoto'  => true,
        ],
        'staff'  => [
          'create'  => true,
          'edit'  => true,
          'delete'  => false,
          'deleteFile'  => true,
          'uploadProfilePhoto'  => true,
        ],
        'staff_positions'  => [
          'create'  => true,
          'edit'  => true,
          'delete'  => false
        ],
        'services'  => [
          'create'  => true,
          'edit'  => true,
          'delete'  => false
        ],
        'appointments'  => [
          'edit'  => true,
          'delete'  => true
        ],
        'treatments'  => [
          'edit'  => true,
          'delete'  => true
        ],
        'budgets'  => [
          'edit'  => true,
          'delete'  => true
        ],
        'company'  => [
          'edit'  => false
        ],
        'settings'  => [
          'edit'  => false
        ],
      ],

      "basic" => [
        'patients'  => [
          'create'  => true,
          'edit'  => false,
          'delete'  => false,
          'deleteFile'  => false,
          'editRecord'  => false,
          'uploadOdontogram'  => false,
          'resetOdontogram'  => false,
        ],
        'staff'  => [
          'create'  => true,
          'edit'  => false,
          'delete'  => false,
          'deleteFile'  => false
        ],
        'staff_positions'  => [
          'create'  => true,
          'edit'  => false,
          'delete'  => false
        ],
        'services'  => [
          'create'  => true,
          'edit'  => false,
          'delete'  => false
        ],
        'appointments'  => [
          'create'  => true,
          'edit'  => true,
          'delete'  => false
        ],
        'treatments'  => [
          'edit'  => true,
          'delete'  => false
        ],
        'budgets'  => [
          'edit'  => false,
          'delete'  => false
        ],
        'company'  => [
          'edit'  => false
        ],
        'settings'  => [
          'edit'  => false
        ],
      ]
    ],

    'settings_fields' => [
      [ 
       'name'  => "company_name",
       'settting_type'  => "company_data",
       'type'  => "text",
       'maxlength'  => "111",
       'col'  => "col-sm-4",
       'autofocus'  => "autofocus",
       'required'  => "required",
       'pattern'  => "",
       'rows'  => ""
      ],
      [ 
       'name'  => "company_nif",
       'settting_type'  => "company_data",           
       'type'  => "text",
       'maxlength'  => "22",
       'col'  => "col-sm-3",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "",
       'rows'  => ""
      ],
      [ 
       'name'  => "company_email",
       'settting_type'  => "company_data",           
       'type'  => "email",
       'maxlength'  => "",
       'col'  => "col-sm-3",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "",
       'rows'  => ""
      ],
      [ 
       'name'  => "company_address",
       'settting_type'  => "company_data",           
       'type'  => "text",
       'maxlength'  => "111",
       'col'  => "col-sm-4",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "",
       'rows'  => ""
      ],
      [ 
       'name'  => "company_city",
       'settting_type'  => "company_data",           
       'type'  => "text",
       'maxlength'  => "111",
       'col'  => "col-sm-4",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "",
       'rows'  => ""
      ],

      [ 
       'name'  => "company_tel1",
       'settting_type'  => "company_data",           
       'type'  => "text",
       'maxlength'  => "11",
       'col'  => "col-sm-3",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "[0-9 -]{0,11}",
       'rows'  => ""
      ],
      [ 
       'name'  => "company_tel2",
       'settting_type'  => "company_data",           
       'type'  => "text",
       'maxlength'  => "11",
       'col'  => "col-sm-3",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "[0-9 -]{0,11}",
       'rows'  => ""
      ],
      [ 
       'name'  => "company_tel3",
       'settting_type'  => "company_data",           
       'type'  => "text",
       'maxlength'  => "11",
       'col'  => "col-sm-3",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "[0-9 -]{0,11}",
       'rows'  => ""
      ],
      [ 
       'name'  => "company_notes",
       'settting_type'  => "company_data",           
       'type'  => "textarea",
       'maxlength'  => "",
       'col'  => "col-sm-12",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "",
       'rows'  => "4"
      ],
      [ 
       'name'  => "invoice_text",
       'settting_type'  => "company_data",
       'type'  => "textarea",
       'maxlength'  => "",
       'col'  => "col-sm-12",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "",
       'rows'  => "4"
      ],
      [ 
       'name'  => "budget_text",
       'settting_type'  => "company_data",           
       'type'  => "textarea",
       'maxlength'  => "",
       'col'  => "col-sm-12",
       'autofocus'  => "",
       'required'  => "",
       'pattern'  => "",
       'rows'  => "4"
      ]
    ]
];
