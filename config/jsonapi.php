<?php


return [
    'resources' => [
        'auth' => [                        
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required_without:data.attributes.email|string',
                    'data.attributes.email' => 'required_without:data.attributes.name|string|email',
                    'data.attributes.password' => 'required|string',
                ],
            ],
        ],
        'passwordresetcode' => [                        
            'validationRules' => [
                'create' => [
                    'data.attributes.email' => 'required|string|email|exists:users,email',
                ],
                'update' => [
                ]
            ],
        ],
        'posts' => [
            'allowedSorts' => [
                'title',
                'created_at',
                'updated_at',
                'id',
            ],
            'allowedIncludes' => [
                'users',
                'comments',
                'postlikes',
                'hasCurrentLiked'
            ],
            'allowedFilters' => [
                'user_id',
                'description',
                'title'
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.title' => 'required|string',
                    'data.attributes.description' => 'required|string',
                ],
                'update' => [
                    'data.attributes.title' => 'sometimes|required|string',
                    'data.attributes.description' => 'sometimes|required|string',                    
                ],
            ],
            'relationships' => [
                [
                    'type' => 'users',
                    'method' => 'users',
                ],
                [
                    'type' => 'comments',
                    'method' => 'comments',
                ],
            ],
        ],
        'users' => [
            'allowedSorts' => [
                'name',
                'email',
            ],
            'allowedIncludes' => [
                'comments',
                'posts',
            ],
            'allowedFilters' => [
                Spatie\QueryBuilder\AllowedFilter::exact('role'),
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string|max:32|regex:/^[a-zA-Z0-9 ]+$/|unique:users,name',
                    'data.attributes.email' => 'required|string|email|max:255|unique:users,email',
                    'data.attributes.password' => 'required|string|confirmed|min:6|max:16',
                    'avatar'  => 'sometimes|required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string|max:32|regex:/^[a-zA-Z0-9 ]+$/|unique:users,name',
                    'data.attributes.email' => 'sometimes|required|email||max:255|unique:users,email',
                    'data.attributes.password' => 'sometimes|required|string|min:6|max:16',
                    'avatar'  => 'sometimes|required|string',
                ],
            ],
            'relationships' => [
                [
                    'type' => 'comments',
                    'method' => 'comments',
                ],
                [
                    'type' => 'posts',
                    'method' => 'posts',
                ],
            ],
        ],
        'comments' => [
            'allowedSorts' => [
                'created_at',
                'id'

            ],
            'allowedIncludes' => [
                'posts',
                'users',
                'posts.comments',
                'posts.postlikes'
            ],
            'allowedFilters' => [
                Spatie\QueryBuilder\AllowedFilter::exact('post_id'),
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.message' => 'required|string',
                ],
                'update' => [
                    'data.attributes.message' => 'sometimes|required|string',
                ],
            ],
            'relationships' => [
                [
                    'type' => 'posts',
                    'method' => 'posts',
                ],
                [
                    'type' => 'users',
                    'method' => 'users',
                ],
            ],
        ],
        'postlikes' => [
            'allowedSorts' => [
                'created_at',
            ],
            'allowedIncludes' => [
                'posts',
                'users',
            ],
            'allowedFilters' => [
                Spatie\QueryBuilder\AllowedFilter::exact('post_id'),
                Spatie\QueryBuilder\AllowedFilter::exact('user_id'),
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.post_id' => 'required|string',
                ],
                'update' => [
                    'data.attributes.user_id' => 'required|string',
                ],
            ],
            'relationships' => [],
        ],
        'files' => [
            'validationRules' => [
                'create' => [
                    'file'  => 'required|mimes:png,jpg,jpeg,gif|max:2048',
                ],
            ],
            'relationships' => [],
        ]
    ],
];
