<?php


namespace App;


use App\Models\SettingsModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class program_array
{
    public static function program_array($userName=null)
    {
        if($userName===null){
            $userName = Auth::user()->user_name;
        }
        $ldap_info=Helper::get_ldap_employee($userName)['emp_info'][0];
        return $array = [
            "forms"=> [
                    "visa_request"=> [
                            'title'=>"Visa Request",
                            'print_title'=>"Entry Visa Request Form",
                            'route'=>'/visa_request',
                            'db_table'=>'visa_request',
                            'tivoli'=>['approvals'=>['NEW'=>['display_name'=>"Requester"],'APPCLEVEL'=>['display_name'=>"Director of requester"],'EGSAPPR'=>['display_name'=>"EGS team leader"],'CLOAPP'=>['display_name'=>"EGS manager"]],
                                'structure_id'=>2909
                            ],
                            'db_joins'=>[
                                    [
                                        'type'=>'leftjoin',
                                        'table'=>'lookups',
                                        'letter'=>'l',
                                        'id'=>'a.visa_type',
                                        'ref_id'=>'id'
                                    ],
                            ],
                            'datatable_cols'=> [
                                'requester'=>[
                                    "title"=>"Requester",
                                    "cols"=>[
                                        ['col'=>'a.name','title'=>"Name",'name'=>'name'],
                                        ['col'=>'a.r_department','title'=>"Department",'name'=>'r_department'],
                                    ],
                                ],
                                'detail'=>[
                                    "title"=>"Details",
                                    "cols"=>[
                                        ['col'=>'l.value as visa_type','title'=>"Visa type",'name'=>'visa_type'],
                                        ['col'=>'a.objective','title'=>"Objective",'name'=>'objective'],
                                        ['col'=>'a.expected_arrival_date','title'=>"Expected date",'name'=>'expected_arrival_date'],
                                    ],
                                ],
                                'status'=>[
                                    "title"=>"Status",
                                    'col'=>'a.status',
                                    'name'=>'status'
                                ],
                                'submitted_at'=>[
                                    "title"=>"Submitted at",
                                    'col'=>'a.created_at',
                                    'name'=>'created_at'
                                ],
                                'actions'=>['title'=>'Actions'],
                            ],
                            'form'=> [
                                    "groups"=> [
                                            [
                                                'name'=> 'requester', 'title'=> "Requester info", 'size'=> 12,
//                                                'right_title'=> 'Checkbox : <input type="checkbox"/> '
                                            ],
                                            [
                                                "name"=> 'visitors', 'title'=> "Visitors info", 'size'=> 12
                                            ],
                                        ],
                                    "inputs"=> [
                                            //name
                                            [
                                                "title" => 'Full name',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'text',
                                                    "id"=>"name",
                                                    "name" => 'name',
                                                    'placeholder' => "Name",
                                                    "class" => "form-control",
                                                    "required" => true,
                                                    "readonly"=>true,
                                                    "value"=>isset($ldap_info['displayname'][0])?$ldap_info['displayname'][0]:"",
                                                ]
                                            ],
                                            //job title
                                            [
                                                "title" => 'Job title',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'text',
                                                    "id"=>"r_job_title",
                                                    "name" => 'r_job_title',
                                                    'placeholder' => "Job title",
                                                    "class" => "form-control",
                                                    "required" => true,
                                                    "readonly"=>true,
                                                    "value"=>isset($ldap_info['title'][0])?$ldap_info['title'][0]:"",
                                                ]
                                            ],
                                            //Department
                                            [
                                                "title" => 'Department',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'text',
                                                    "id"=>"r_department",
                                                    "name" => 'r_department',
                                                    'placeholder' => "Department",
                                                    "class" => "form-control",
                                                    "required" => true,
                                                    "readonly"=>true,
                                                    "value"=>isset($ldap_info['department'][0])?$ldap_info['department'][0]:"",
                                                ]
                                            ],
                                            //workplace
                                            [
                                                "title" => 'Workplace',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'text',
                                                    "id"=>"r_workplace",
                                                    "name" => 'r_workplace',
                                                    'placeholder' => "Workplace",
                                                    "class" => "form-control",
                                                    "required" => true,
                                                    "readonly"=>true,
                                                    "value"=>isset($ldap_info['physicaldeliveryofficename'][0])?$ldap_info['physicaldeliveryofficename'][0]:"",
                                                ]
                                            ],
                                            //contact
                                            [
                                                "title" => 'Contact',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'text',
                                                    "id"=>"r_contact",
                                                    "name" => 'r_contact',
                                                    'placeholder' => "Contact",
                                                    "class" => "form-control",
                                                    "required" => true,
                                                    "readonly"=>true,
                                                    "value"=>isset($ldap_info['mobile'][0])? $ldap_info['mobile'][0]: "",
                                                ]
                                            ],

                                            //space
                                            [
                                                "type"=>'space',
                                                "group"=>'requester',
                                                "attrs" => [
                                                    "type" => 'custom',
                                                    "html"=>"<div class='modal-body col-4 transaction_type1'></div>"
                                                ]
                                            ],

                                            //expected date
                                            [
                                                "title" => 'Expected arrival date',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'date',
                                                    "id"=>"expected_arrival_date",
                                                    "name" => 'expected_arrival_date',
                                                    "class" => "form-control",
                                                    "required" => false,
                                                ]
                                            ],
                                            //visit duration
                                            [
                                                "title" => 'Visit duration(days)',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'number',
                                                    "id"=>"visit_duration",
                                                    "name" => 'visit_duration',
                                                    "class" => "form-control",
                                                    "required" => false,
                                                ]
                                            ],
                                            //objective
                                            [
                                                "title" => 'Objective',
                                                "col_size" => 4,
                                                'group'=>'requester',
                                                "attrs" => [
                                                    "type" => 'text',
                                                    "id"=>"objective",
                                                    "name" => 'objective',
                                                    "class" => "form-control",
                                                    "required" => false,
                                                ]
                                            ],
                                            //visa type
                                            [
                                                "title" => 'Visa type',
                                                "col_size" => 4,
                                                "group"=>"requester",
                                                "attrs" => [
                                                    "type" => 'select',
                                                    "name" => "visa_type",
                                                    "id" => "visa_type",
                                                    "class" => "selectpicker form-control col-2 col-sm-12",
                                                    "data-style" => "border",
                                                    "data-live-search" => "true",
                                                    'title' => "Choose a type",

                                                ],
                                                "options" => DB::table('lookups')->where('l_id',DB::table('lookup_type')->where('name','visa_type')->value('id'))->get(),

                                                'optionLabel' => 'value',
                                                'optionValue' => 'id'
                                            ],

                                            //visitors
                                            [
                                                "col_size" => 12,
                                                "group"=>"visitors",
                                                "attrs" => [
                                                    "type" => 'multi_row_table',
                                                    "id"=>"visitor_infos",
                                                ],
                                                "columns"=>[
                                                    //v_name
                                                    [
                                                        "title" => 'Name',
                                                        "width"=>'25',
                                                        "attrs" => [
                                                            "type" => 'text',
                                                            "id"=>"v_name",
//                                                            "name" => 'v_name',
                                                            "class" => "form-control",
                                                            "required" => true,
                                                        ]
                                                    ],

                                                    //description
                                                    [
                                                        "title" => 'Description',
                                                        "width"=>'25',
                                                        "attrs" => [
                                                            "type" => 'text',
                                                            "id"=>"v_description",
//                                                            "name" => 'v_description',
                                                            "class" => "form-control",
                                                        ]
                                                    ],

                                                    //JOb_title
                                                    [
                                                        "title" => 'Job title',
                                                        "width"=>'25',
                                                        "attrs" => [
                                                            "type" => 'text',
                                                            "id"=>"v_job_title",
//                                                            "name" => 'v_job_title',
                                                            "class" => "form-control",
                                                            "required" => true,
                                                        ]
                                                    ],

                                                    //Contact
                                                    [
                                                        "title" => 'Contact',
                                                        "width"=>'17',
                                                        "attrs" => [
                                                            "type" => 'text',
                                                            "id"=>"v_contact",
//                                                            "name" => 'v_contact',
                                                            "class" => "form-control",
                                                            "required" => true,
                                                        ]
                                                    ],

                                                    //attachments
                                                    [
                                                        "title" => 'Attachments',
                                                        "width"=>"5",
                                                        "attrs" => [
                                                            "type" => 'custom',
                                                            "html"=>'<td><a onclick="open_attachment_modal(this)" data-toggle="modal" data-target="#user_modal" class="btn btn-sm btn-info btn-icon-split text-right ml-2 mb-2"><span class="icon text-white-50"><i class="fa fa-paperclip"></i></span></a></td>'
                                                        ]
                                                    ],

                                                ]
                                            ],
                                        ],
                                    "modals"=>[
                                      "attachments"=>[
                                          'id'=>"attachments",
                                          'modal_title'=>"Attachments",
                                          'modal_size'=>"",
                                          "form"=>[
                                              'inputs'=>[
                                              //passport
                                              [
                                                  "title" => 'Passport',
                                                  "col_size" => 12,
                                                  "attrs" => [
                                                      "type" => 'file',
                                                      "id"=>"passport",
                                                      "name" => 'passport',
                                                      "class" => "form-control",
                                                      "required" => true,
                                                      'onChange'=>'fileValidate(this)',
                                                      "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                  ]
                                              ],
                                              //Photo
                                              [
                                                  "title" => 'Photo',
                                                  "col_size" => 12,
                                                  "attrs" => [
                                                      "type" => 'file',
                                                      "id"=>"photo",
                                                      "name" => 'photo',
                                                      "class" => "form-control",
                                                      "required" => true,
                                                      'onChange'=>'fileValidate(this)',
                                                      "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                  ]
                                              ],
                                              //Certificate
                                              [
                                                  "title" => 'Certificate',
                                                  "col_size" => 12,
                                                  "attrs" => [
                                                      "type" => 'file',
                                                      "id"=>"certificate",
                                                      "name" => 'certificate',
                                                      "class" => "form-control",
                                                      'onChange'=>'fileValidate(this)',
                                                      "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                  ]
                                              ],
                                              //Invitation letter
                                              [
                                                  "title" => 'Invitation Letter',
                                                  "col_size" => 12,
                                                  "attrs" => [
                                                      "type" => 'file',
                                                      "id"=>"invitation_letter",
                                                      "name" => 'invitation_letter',
                                                      "class" => "form-control",
                                                      'onChange'=>'fileValidate(this)',
                                                      "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                  ]
                                              ],
                                              //Form33
                                              [
                                                  "title" => 'Form 33',
                                                  "col_size" => 12,
                                                  "attrs" => [
                                                      "type" => 'file',
                                                      "id"=>"form33",
                                                      "name" => 'form33',
                                                      "class" => "form-control",
                                                      "required" => true,
                                                      'onChange'=>'fileValidate(this)',
                                                      "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                  ]
                                              ],
                                            ]
                                          ]
                                      ]
                                    ],
                                    "cols"=>[
                                        "name"=>["title" => 'Full name'],
                                        "r_job_title"=>["title" => 'Job title'],
                                        "r_department"=>["title" => 'Department',],
                                        "r_workplace"=>["title" => 'Workplace',],
                                        "r_contact"=>["title" => 'Contact',],
                                        "expected_arrival_date"=>["title" => 'Expected arrival date',],
                                        "visit_duration"=>["title" => 'Visit duration',],
                                        "objective"=>["title" => 'Objective',],
                                        "visa_type"=>["title" => 'Visa type','col_ref'=>'l.value'],

                                        //visitors
                                        "visitor_infos"=>[
                                            "v_name"=>["title" => 'Name','size'=>'30%'],
                                            "v_description"=>["title" => 'Description','size'=>'20%'],
                                            "v_job_title"=>["title" => 'Job title','size'=>'20%'],
                                            "v_contact"=>["title" => 'Contact','size'=>'20%'],
                                        ],
                                    ],
                                    'expenses'=> [
                                        'inputs' => [
                                            //Issue date
                                            [
                                                "title" => 'Issue date',
                                                "col_size" => 4,
                                                "attrs" => [
                                                    "type" => 'date',
                                                    "id"=>"issue_date",
                                                    "name" => 'issue_date',
                                                    "class" => "form-control",
                                                ]
                                            ],
                                            //expire date
                                            [
                                                "title" => 'Expire date',
                                                "col_size" => 4,
                                                "attrs" => [
                                                    "type" => 'date',
                                                    "id"=>"expire_date",
                                                    "name" => 'expire_date',
                                                    "class" => "form-control",
                                                ]
                                            ],
                                            //fee
                                            [
                                                "title" => 'Fees',
                                                "col_size" => 4,
                                                "attrs" => [
                                                    "type" => 'number',
                                                    "id"=>"fees",
                                                    "name" => 'fees',
                                                    'placeholder' => "Fees (IQD)",
                                                    "class" => "form-control",
                                                ]
                                            ],
                                            //cost
                                            [
                                                "title" => 'Cost',
                                                "col_size" => 4,
                                                "attrs" => [
                                                    "type" => 'number',
                                                    "id"=>"cost",
                                                    "name" => 'cost',
                                                    'placeholder' => "Cost(IQD)",
                                                    "class" => "form-control",
                                                ]
                                            ],
                                            //note
                                            [
                                                "title" => 'Note',
                                                "col_size" => 4,
                                                "attrs" => [
                                                    "type" => 'textarea',
                                                    "id"=>"note",
                                                    "name" => 'note',
                                                    'placeholder' => "Write a note...",
                                                    "class" => "form-control",
                                                ]
                                            ],
                                        ],
                                        'currency'=>'IQD',
                                    ],
                                ]
                            ],
                    "residency_request"=> [
                        'title'=>"Residency Request",
                        'print_title'=>"Residency card request form",
                        'route'=>'/residency_request',
                        'db_table'=>'residency_request',
                        'tivoli'=>['approvals'=>['NEW'=>['display_name'=>"Requester"],'APPCLEVEL'=>['display_name'=>"Director of requester"],'EGSAPPR'=>['display_name'=>"EGS team leader"],'CLOAPP'=>['display_name'=>"EGS manager"]],
                            'structure_id'=>2910
                        ],
                        'db_joins'=>[
                            [
                                'type'=>'leftjoin',
                                'table'=>'lookups',
                                'letter'=>'l',
                                'id'=>'a.residency_type',
                                'ref_id'=>'id'
                            ],
                            [
                                'type'=>'leftjoin',
                                'table'=>'lookups',
                                'letter'=>'l2',
                                'id'=>'a.residence_duration',
                                'ref_id'=>'id'
                            ],
                        ],
                        'datatable_cols'=> [
                            'requester'=>[
                                "title"=>"Requester",
                                "cols"=>[
                                    ['col'=>'a.name','title'=>"Name",'name'=>'name'],
                                    ['col'=>'a.r_department','title'=>"Department",'name'=>'r_department'],
                                ],
                            ],
                            'detail'=>[
                                "title"=>"Details",
                                "cols"=>[
                                    ['col'=>'l.value as residency_type','title'=>"Residency type",'name'=>'residency_type'],
                                    ['col'=>'l2.value as residence_duration','title'=>"Residency duration",'name'=>'residence_duration'],
                                    ['col'=>'address','title'=>"Full address",'name'=>'address'],
                                ],
                            ],
                            'status'=>[
                                "title"=>"Status",
                                'col'=>'a.status',
                                'name'=>'status'
                            ],
                            'submitted_at'=>[
                                "title"=>"Submitted at",
                                'col'=>'a.created_at',
                                'name'=>'created_at'
                            ],
                            'actions'=>['title'=>'Actions'],
                        ],
                        'form'=> [
                            "groups"=> [
                                [
                                    'name'=> 'requester', 'title'=> "Requester info", 'size'=> 12,
//                                                'right_title'=> 'Checkbox : <input type="checkbox"/> '
                                ],
                                [
                                    "name"=> 'visitors', 'title'=> "Visitors info", 'size'=> 12
                                ],
                            ],
                            "inputs"=> [
                                //name
                                [
                                    "title" => 'Full name',
                                    "col_size" => 4,
                                    'group'=>'requester',
                                    "attrs" => [
                                        "type" => 'text',
                                        "id"=>"name",
                                        "name" => 'name',
                                        'placeholder' => "Name",
                                        "class" => "form-control",
                                        "required" => true,
                                        "readonly"=>true,
                                        "value"=>Helper::get_ldap_employee($userName)['emp_info'][0]['displayname'][0]
                                    ]
                                ],
                                //job title
                                [
                                    "title" => 'Job title',
                                    "col_size" => 4,
                                    'group'=>'requester',
                                    "attrs" => [
                                        "type" => 'text',
                                        "id"=>"r_job_title",
                                        "name" => 'r_job_title',
                                        'placeholder' => "Job title",
                                        "class" => "form-control",
                                        "required" => true,
                                        "readonly"=>true,
                                        "value"=>Helper::get_ldap_employee($userName)['emp_info'][0]['title'][0]
                                    ]
                                ],
                                //Department
                                [
                                    "title" => 'Department',
                                    "col_size" => 4,
                                    'group'=>'requester',
                                    "attrs" => [
                                        "type" => 'text',
                                        "id"=>"r_department",
                                        "name" => 'r_department',
                                        'placeholder' => "Department",
                                        "class" => "form-control",
                                        "required" => true,
                                        "readonly"=>true,
                                        "value"=>Helper::get_ldap_employee($userName)['emp_info'][0]['department'][0]

                                    ]
                                ],
                                //workplace
                                [
                                    "title" => 'Workplace',
                                    "col_size" => 4,
                                    'group'=>'requester',
                                    "attrs" => [
                                        "type" => 'text',
                                        "id"=>"r_workplace",
                                        "name" => 'r_workplace',
                                        'placeholder' => "Workplace",
                                        "class" => "form-control",
                                        "required" => true,
                                        "readonly"=>true,
                                        "value"=>Helper::get_ldap_employee($userName)['emp_info'][0]['physicaldeliveryofficename'][0]
                                    ]
                                ],
                                //contact
                                [
                                    "title" => 'Contact',
                                    "col_size" => 4,
                                    'group'=>'requester',
                                    "attrs" => [
                                        "type" => 'text',
                                        "id"=>"r_contact",
                                        "name" => 'r_contact',
                                        'placeholder' => "Contact",
                                        "class" => "form-control",
                                        "required" => true,
                                        "readonly"=>true,
                                        "value"=>Helper::get_ldap_employee($userName)['emp_info'][0]['mobile'][0]
                                    ]
                                ],

                                //space
                                [
                                    "type"=>'space',
                                    "group"=>'requester',
                                    "attrs" => [
                                        "type" => 'custom',
                                        "html"=>"<div class='modal-body col-4 transaction_type1'></div>"
                                    ]
                                ],

                                //residence type
                                [
                                    "title" => 'Residence type',
                                    "col_size" => 4,
                                    "group"=>"requester",
                                    "attrs" => [
                                        "type" => 'select',
                                        "name" => "residency_type",
                                        "id" => "residency_type",
                                        "class" => "selectpicker form-control col-2 col-sm-12",
                                        "data-style" => "border",
                                        'title' => "Choose duration",
                                    ],
                                    "options" => DB::table('lookups')->where('l_id',DB::table('lookup_type')->where('name','residence_type')->value('id'))->get(),
                                    'optionLabel' => 'value',
                                    'optionValue' => 'id'
                                ],
                                //residence duration
                                [
                                    "title" => 'Residence duration',
                                    "col_size" => 4,
                                    "group"=>"requester",
                                    "attrs" => [
                                        "type" => 'select',
                                        "name" => "residence_duration",
                                        "id" => "residence_duration",
                                        "class" => "selectpicker form-control col-2 col-sm-12",
                                        "data-style" => "border",
                                        'title' => "Choose duration",
                                    ],
                                    "options" => DB::table('lookups')->where('l_id',DB::table('lookup_type')->where('name','residence_duration')->value('id'))->get(),
                                    'optionLabel' => 'value',
                                    'optionValue' => 'id'
                                ],
                                //Full address
                                [
                                    "title" => 'Full address',
                                    "col_size" => 4,
                                    'group'=>'requester',
                                    "attrs" => [
                                        "type" => 'text',
                                        "id"=>"address",
                                        "name" => 'address',
                                        "class" => "form-control",
                                        "required" => false,
                                    ]
                                ],

                                //visitors
                                [
                                    "col_size" => 12,
                                    "group"=>"visitors",
                                    "attrs" => [
                                        "type" => 'multi_row_table',
                                        "id"=>"visitor_infos",
                                    ],
                                    "columns"=>[
                                        //v_name
                                        [
                                            "title" => 'Name',
                                            "attrs" => [
                                                "type" => 'text',
                                                "id"=>"v_name",
//                                                            "name" => 'v_name',
                                                "class" => "form-control",
                                                "required" => true,
                                            ]
                                        ],

                                        //description
                                        [
                                            "title" => 'Description',
                                            "attrs" => [
                                                "type" => 'text',
                                                "id"=>"v_description",
//                                                            "name" => 'v_description',
                                                "class" => "form-control",
                                                "required" => true,
                                            ]
                                        ],

                                        //JOb_title
                                        [
                                            "title" => 'Job title',
                                            "attrs" => [
                                                "type" => 'text',
                                                "id"=>"v_job_title",
//                                                            "name" => 'v_job_title',
                                                "class" => "form-control",
                                                "required" => true,
                                            ]
                                        ],

                                        //Contact
                                        [
                                            "title" => 'Contact',
                                            "attrs" => [
                                                "type" => 'text',
                                                "id"=>"v_contact",
//                                                            "name" => 'v_contact',
                                                "class" => "form-control",
                                                "required" => true,
                                            ]
                                        ],

                                        //attachments
                                        [
                                            "title" => 'Attachments',
                                            "attrs" => [
                                                "type" => 'custom',
                                                "html"=>'<td><a onclick="open_attachment_modal(this)" data-toggle="modal" data-target="#user_modal" class="btn btn-sm btn-info btn-icon-split text-right ml-2 mb-2"><span class="icon text-white-50"><i class="fa fa-paperclip"></i></span></a></td>'
                                            ]
                                        ],

                                    ]
                                ],
                            ],
                            "modals"=>[
                                "attachments"=>[
                                    'id'=>"attachments",
                                    'modal_title'=>"Attachments",
                                    'modal_size'=>"",
                                    "form"=>[
                                        'inputs'=>[
                                            //passport
                                            [
                                                "title" => 'Passport',
                                                "col_size" => 12,
                                                "attrs" => [
                                                    "type" => 'file',
                                                    "id"=>"passport",
                                                    "name" => 'passport',
                                                    "class" => "form-control",
//                                                      "required" => true,
                                                    'onChange'=>'fileValidate(this)',
                                                    "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                ]
                                            ],
                                            //photo
                                            [
                                                "title" => 'Photo',
                                                "col_size" => 12,
                                                "attrs" => [
                                                    "type" => 'file',
                                                    "id"=>"photo",
                                                    "name" => 'photo',
                                                    "class" => "form-control",
//                                                      "required" => true,
                                                    'onChange'=>'fileValidate(this)',
                                                    "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                ]
                                            ],
                                            //old_residency
                                            [
                                                "title" => 'Old residency',
                                                "col_size" => 12,
                                                "attrs" => [
                                                    "type" => 'file',
                                                    "id"=>"old_residency",
                                                    "name" => 'old_residency',
                                                    "class" => "form-control",
                                                    'onChange'=>'fileValidate(this)',
                                                    "accept"=>".png, .jpeg, .jpg, .pdf,.PNG,.JPEG,.JPG,.PDF",
                                                ]
                                            ],
                                        ]
                                    ]
                                ]
                            ],
                            "cols"=>[
                                "name"=>["title" => 'Full name'],
                                "r_job_title"=>["title" => 'Job title'],
                                "r_department"=>["title" => 'Department',],
                                "r_workplace"=>["title" => 'Workplace',],
                                "r_contact"=>["title" => 'Contact',],

                                "residency_type"=>["title" => 'Residency type',],
                                "address"=>["title" => 'Full address',],
                                "residence_duration"=>["title" => 'Residence duration',],

                                //visitors
                                "visitor_infos"=>[
                                    "v_name"=>["title" => 'Name','size'=>'30%'],
                                    "v_description"=>["title" => 'Description','size'=>'20%'],
                                    "v_job_title"=>["title" => 'Job title','size'=>'20%'],
                                    "v_contact"=>["title" => 'Contact','size'=>'20%'],
                                ],
                            ],
                            'expenses'=> [
                                'inputs' => [
                                    //Issue date
                                    [
                                        "title" => 'Issue date',
                                        "col_size" => 4,
                                        "attrs" => [
                                            "type" => 'date',
                                            "id"=>"issue_date",
                                            "name" => 'issue_date',
                                            "class" => "form-control",
                                        ]
                                    ],
                                    //expire date
                                    [
                                        "title" => 'Expire date',
                                        "col_size" => 4,
                                        "attrs" => [
                                            "type" => 'date',
                                            "id"=>"expire_date",
                                            "name" => 'expire_date',
                                            "class" => "form-control",
                                        ]
                                    ],
                                    //fee
                                    [
                                        "title" => 'Fees',
                                        "col_size" => 4,
                                        "attrs" => [
                                            "type" => 'number',
                                            "id"=>"fees",
                                            "name" => 'fees',
                                            'placeholder' => "Fees (IQD)",
                                            "class" => "form-control",
                                        ]
                                    ],
                                    //cost
                                    [
                                        "title" => 'Cost',
                                        "col_size" => 4,
                                        "attrs" => [
                                            "type" => 'number',
                                            "id"=>"cost",
                                            "name" => 'cost',
                                            'placeholder' => "Cost(IQD)",
                                            "class" => "form-control",
                                        ]
                                    ],
                                    //note
                                    [
                                        "title" => 'Note',
                                        "col_size" => 4,
                                        "attrs" => [
                                            "type" => 'textarea',
                                            "id"=>"note",
                                            "name" => 'note',
                                            'placeholder' => "Write a note...",
                                            "class" => "form-control",
                                        ]
                                    ],
                                ],
                                'currency'=>'IQD',
                            ],
                        ]
                    ],
            ],
            "requests"=> [
                'title'=>"Requests",
                'filters'=> [
                    [
                        "title" => 'Username',
                        "col_size" => 3,
                        "attrs" => [
                            "type" => 'text',
                            "name" => 'user_name',
                            "class" => "form-control",
                        ]
                    ],
                ],
            ],
        ];
    }
    public static function status_lookups()
    {
        return $array = [
            0=>['title'=>"Drafted",'icon'=>'fas fa-hourglass-end','color'=>'badge badge-info'],
            1=>['title'=>"Pending at EGS",'icon'=>'fas fa-hourglass-end','color'=>'badge badge-warning'],
            2=>['title'=>"Rejected at EGS",'icon'=>'fas fa-thumbs-down','color'=>'badge badge-danger'],
            3=>['title'=>"Pending at Tivoli",'icon'=>'fas fa-hourglass-end','color'=>'badge badge-info'],
            4=>['title'=>"Finished",'icon'=>'fas fa-thumbs-up','color'=>'badge badge-success'],
            5=>['title'=>"Rejected at Tivoli",'icon'=>'fas fa-thumbs-down','color'=>'badge badge-dark'],
        ];
    }

}
