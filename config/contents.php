<?php
return [
    'feature' => [
        'field_name' => [
            'title' => 'text',
            'information' => 'text',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'information.*' => 'required|max:200',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '50x50'
        ]
    ],

    'statistics' => [
        'field_name' => [
            'title' => 'text',
            'number_of_data' => 'text',
            'icon' => 'icon'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'number_of_data.*' => 'required|max:100',
            'icon' => 'nullable'
        ],
    ],

    'team' => [
        'field_name' => [
            'name' => 'text',
            'designation' => 'text',
            'image' => 'file',
            'fb_link' => 'url',
            'twitter_link' => 'url',
            'instagram_link' => 'url',
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'designation.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
            'fb_link.*' => 'nullable',
            'twitter_link.*' => 'nullable',
            'instagram_link.*' => 'nullable'
        ],
        'size' => [
            'image' => '256x341'
        ]
    ],


    'testimonial' => [
        'field_name' => [
            'name' => 'text',
            'designation' => 'text',
            'description' => 'textarea',
            'image' => 'file'
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'designation.*' => 'required|max:2000',
            'description.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '150x150'
        ]
    ],

    'faq' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:190',
            'description.*' => 'required|max:5000'
        ]
    ],
    'blog' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'description.*' => 'required|max:5000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '856x570',
            'thumb' => '416x333'
        ]
    ],




    'why-chose-us' => [
        'field_name' => [
            'title' => 'text',
            'information' => 'textarea',
            'icon' => 'icon',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'information.*' => 'required|max:300',
            'icon.*' => 'required|max:300',
        ]
    ],

    'social' => [
        'field_name' => [
            'name' => 'text',
            'icon' => 'icon',
            'link' => 'url',
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'icon.*' => 'required|max:100',
            'link.*' => 'required|max:100'
        ],
    ],
    'support' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'description.*' => 'required|max:15000'
        ]
    ],


    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
    ],

    'content_media' => [
        'image' => 'file',
        'thumbnail' => 'file',
        'youtube_link' => 'url',
        'button_link' => 'url',
        'link' => 'url',
        'icon' => 'icon',
        'fb_link' => 'url',
        'twitter_link' => 'url',
        'instagram_link' => 'url',
    ]
];
