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
                ],
            ],
        ],
        'projects' => [
            'allowedSorts' => [
                'project_name',
                'project_manager',
                'assignee',
                'date',
                'priority',
                'overview',
                'to_do',
            ],
            'allowedIncludes' => [

            ],
            'allowedFilters' => [
                'project_name',
                'project_manager',
                'assignee',
                'date',
                'priority',
                'overview',
                'to_do',
            ],
            'validationRules' => [
                'create' => [

                    'data.attributes.project_name' => 'required|string',
                    'data.attributes.project_manager' => 'required|string',
                    'data.attributes.assignee' => 'required|string',
                    'data.attributes.date' => 'required|string',
                    'data.attributes.priority' => 'required|string',
                    'data.attributes.overview' => 'required|string',
                    'data.attributes.to_do' => 'required|string',
                ],
                'update' => [
                    'data.attributes.project_name' => 'sometimes|required|string',
                    'data.attributes.project_manager' => 'sometimes|required|string',
                    'data.attributes.assignee' => 'sometimes|required|string',
                    'data.attributes.date' => 'sometimes|required|string',
                    'data.attributes.priority' => 'sometimes|required|string',
                    'data.attributes.overview' => 'sometimes|required|string',
                    'data.attributes.to_do' => 'sometimes|required|string',
                ],
            ],
            'relationships' => [

            ],
        ],
        'tasks' => [
            'allowedSorts' => [
                'project_id',
                'status',
                'deadline',
                'priority',
                'task_detail',
            ],
            'allowedIncludes' => [

            ],
            'allowedFilters' => [
                'project_id',
                'status',
                'deadline',
                'priority',
                'task_detail',
            ],
            'validationRules' => [
                'create' => [

                    'data.attributes.project_id' => 'required|string',
                    'data.attributes.status' => 'required|string',
                    'data.attributes.deadline' => 'required|string',
                    'data.attributes.priority' => 'required|string',
                    'data.attributes.task_detail' => 'required|string',
                ],
                'update' => [
                    'data.attributes.project_id' => 'sometimes|required|string',
                    'data.attributes.status' => 'sometimes|required|string',
                    'data.attributes.deadline' => 'sometimes|required|string',
                    'data.attributes.priority' => 'sometimes|required|string',
                    'data.attributes.task_detail' => 'sometimes|required|string',
                ],
            ],
            'relationships' => [

            ],
        ],
        'inspections' => [
            'allowedSorts' => [
                'report',
                'status',
            ],
            'allowedIncludes' => [

            ],
            'allowedFilters' => [
                'report',
                'status',
            ],
            'validationRules' => [
                'create' => [

                    'data.attributes.report' => 'required|string',
                    'data.attributes.status' => 'required|string',
                ],
                'update' => [
                    'data.attributes.report' => 'sometimes|required|string',
                    'data.attributes.status' => 'sometimes|required|string',
                ],
            ],
            'relationships' => [

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
                    'data.attributes.password' => 'required|string|min:6|max:16',
                    'data.attributes.role' => 'required|string',
                    'avatar' => 'sometimes|required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string|max:32|regex:/^[a-zA-Z0-9 ]+$/|unique:users,name',
                    'data.attributes.email' => 'sometimes|required|email||max:255|unique:users,email',
                   'data.attributes.role' => 'sometimes|required|string', 'data.attributes.password' => 'sometimes|required|string|min:6|max:16',
                    'avatar' => 'sometimes|required|string',
                ],
            ],
            'relationships' => [

            ],
        ],

    ],
];
