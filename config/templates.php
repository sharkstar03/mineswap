<?php
return [
    'hero' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
            'sub_heading' => 'text',
            'short_description' => 'textarea',
            'button_name' => 'text',
            'button_link' => 'url',
            'image' => 'file',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
            'sub_heading.*' => 'required|max:100',
            'short_description.*' => 'required|max:2000',
            'button_name.*' => 'required|max:2000',
            'button_link.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
        ]
    ],

    'about-us' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
            'short_description' => 'textarea',
            'button_name' => 'text',
            'button_link' => 'url',
            'image' => 'file',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
            'short_description.*' => 'required|max:2000',
            'button_name.*' => 'required|max:2000',
            'button_link.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
        ]
    ],

    'feature' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
            'short_details' => 'textarea',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
            'short_details.*' => 'required|max:2000'
        ]
    ],

    'calculation' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
            'calculation_title' => 'text',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
            'calculation_title.*' => 'required|max:100'
        ]
    ],

    'team' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100'
        ]
    ],

    'plan' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100'
        ]
    ],

    'testimonial' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100'
        ]
    ],

    'faq' => [
        'field_name' => [
            'sub_title' => 'text',
            'highlight_heading' => 'text',
            'heading' => 'text',
            'image' => 'file',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
        ]
    ],
    'blog' => [
        'field_name' => [
            'sub_title' => 'text',
            'highlight_heading' => 'text',
            'heading' => 'text',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
            'heading.*' => 'required|max:100'
        ]
    ],

    'why-chose-us' => [
        'field_name' => [
            'sub_title' => 'text',
            'heading' => 'text',
            'highlight_heading' => 'text',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'heading.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
        ]
    ],
    'news-letter' => [
        'field_name' => [
            'sub_title' => 'text',
            'highlight_heading' => 'text',
            'heading' => 'text',
        ],
        'validation' => [
            'sub_title.*' => 'required|max:100',
            'highlight_heading.*' => 'required|max:100',
            'heading.*' => 'required|max:100'
        ]
    ],

    'contact-us' => [
        'field_name' => [
            'heading' => 'text',
            'sub_heading' => 'text',
            'address' => 'text',
            'email' => 'text',
            'phone' => 'text',
            'footer_short_details' => 'textarea'
        ],
        'validation' => [
            'heading.*' => 'required|max:100',
            'sub_heading.*' => 'required|max:200',
            'address.*' => 'required|max:2000',
            'email.*' => 'required|max:2000',
            'phone.*' => 'required|max:2000'
        ]
    ],


    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'icon_image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
    ],
    'template_media' => [
        'image' => 'file',
        'thumbnail' => 'file',
        'youtube_link' => 'url',
        'button_link' => 'url',
    ]
];
