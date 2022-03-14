<?php

return [
    'fields' => [
        'image' => [
            'imageNote' => 'Image here <em><strong>must be</strong> 770px wide x 400px tall</em>',
            'width' => 770,
            'height' => 400,
        ]
    ],

    'details_template_id' => '__ID__',

    'timezone' => 'UTC',

    'featured' => false,

    'tags' => false,

    'categories' => false,

    'file' => false,

    'externalLink' => false,

    'images' => false,

    /*
     * or an array of fields to use instead
    'images' => [
      [
          [ 'label' => 'Images', 'name' => 'images', 'type' => 'repeatable', 'required' => false, 'hideLabel' => true, 'fields' =>
              [
                  [ 'name' => 'Image', 'page_content_type_id' => 4, 'field' => 'image' ],
              ]
          ],
      ],
    */

];
