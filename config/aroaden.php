<?php

return [

  'files' => [
    'file_max_size' => 3
  ],

  'currency' => [
    'db_decimals' => 2,
    'db_dec_point' => '.',
    'db_thousands_sep' => '',
    'locale_code' => 'es_ES.utf8',
    'regexp' => "^\d+(\.\d{3})*(,\d{0,2})?$",
    'max' => '99999999999.99'
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

  'form_fields' => [
    'hidden_idpat' => false,
    'surname' => false,
    'name' => false,
    'position' => false,
    'address' => false,
    'city' => false,
    'birth' => false,
    'dni' => false,
    'sex' => false,
    'tel1' => false,
    'tel2' => false,
    'tel3' => false,
    'units' => false,
    'price' => false,
    'pricetax' => false,
    'paid' => false,            
    'tax' => false,
    'hour' => false,
    'day' => false,
    'issue_date' => false,
    'no_tax_msg' => false,
    'staff' => false,            
    'notes' => false,
    'username' => false,            
    'password' => false,
    'full_name' => false,
    'user_type' => false,
    'save' => false
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

  'validates' => [
    'name' => ['required|string|max:111', '111'],
    'surname' => ['required|string|max:111', '111'],
    'dni' => ['required|alpha_num|min:9|max:10', '10'],
    'sex' => ['nullable|string|max:9', '9'],    
    'tel1' => ['nullable|string|max:18', '18'],
    'tel2' => ['nullable|string|max:18', '18'],
    'tel3' => ['nullable|string|max:18', '18'],
    'address' => ['nullable|string|max:111', '111'],
    'city' => ['nullable|string|max:111', '111'],
    'birth' => ['nullable|date_format:d-m-Y'],
    'day' => ['required|date_format:d-m-Y'],
    'hour' => ['required|date_format:H:i'],
    'units' => ['required|integer||min:1|max:255', '255'],
    'tax' => ['required|integer||min:1|max:255', '255'],
    'uniqid' => ['required|string|max:16', '16'],
    'notes' => ['nullable|string|max:65000'],
    'paid' => ["required|min:0", '11, 2'],
    'price' => ["required|min:0", '11, 2'],
    
    'username' => ['required|alpha_num|max:40', '40'],
    'password' => ['required|alpha_num|max:40', '40'],
    'user_type' => ['required|string|max:30', '30'],
    'full_name' => ['required|string|max:111', '111'],

    'idpat' => ['required|integer'],
    'idser' => ['required|integer']
  ],

  'settings_fields' => [
    [ 
     'name'  => "company_name",
     'settting_type'  => "company_data",
     'type'  => "text",
     'maxlength'  => "111",
     'col'  => "col-sm-3",
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
     'col'  => "col-sm-3",
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
     'col'  => "col-sm-3",
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
     'col'  => "col-sm-2",
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
     'col'  => "col-sm-2",
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
     'col'  => "col-sm-2",
     'autofocus'  => "",
     'required'  => "",
     'pattern'  => "[0-9 -]{0,11}",
     'rows'  => ""
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
    ]
  ]
];
