<?php

namespace  App\Http\Controllers\Admin;

use App\Models\CvProfileDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\UserUpdateCrudRequest as UpdateRequest;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Xml\Tests;
use App\Models\Employee;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Employee');
        $this->crud->setEntityNameStrings('employee', 'employees');
        $this->crud->setRoute(backpack_url('user'));
    }

    public function setupListOperation()
    {
        // dump($this);
        $this->crud->addColumns([
            [
                'label' => 'Company',
                'type'  => 'model_function',
                'name' => 'position_id',
                'function_name' => 'getCompanyName',
            ],
            [
                'label' => 'Name',
                'type'  => 'model_function',
                'name'  => 'user_id',
                'function_name' => 'getUserName',
            ],
            [
                'label' => 'Position',
                'type'  => 'select',
                'entity' => 'Position',
                'attribute' => 'name',
                'model' => 'App\Models\Position',
            ],
            [ // n-n relationship (with pivot table)
                'label'     => trans('backpack::permissionmanager.roles'), // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'roles', // the method that defines the relationship in your Model
                'entity'    => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => config('permission.models.role'), // foreign key model
            ],
            [ // n-n relationship (with pivot table)
                'label'     => trans('backpack::permissionmanager.extra_permissions'), // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'permissions', // the method that defines the relationship in your Model
                'entity'    => 'permissions', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => config('permission.models.permission'), // foreign key model
            ],
        ]);

        // if (backpack_pro()) {
        //     // Role Filter
        //     $this->crud->addFilter(
        //         [
        //             'name'  => 'role',
        //             'type'  => 'dropdown',
        //             'label' => trans('backpack::permissionmanager.role'),
        //         ],
        //         config('permission.models.role')::all()->pluck('name', 'id')->toArray(),
        //         function ($value) { // if the filter is active
        //             $this->crud->addClause('whereHas', 'roles', function ($query) use ($value) {
        //                 $query->where('role_id', '=', $value);
        //             });
        //         }
        //     );

        //     // Extra Permission Filter
        //     $this->crud->addFilter(
        //         [
        //             'name'  => 'permissions',
        //             'type'  => 'select2',
        //             'label' => trans('backpack::permissionmanager.extra_permissions'),
        //         ],
        //         config('permission.models.permission')::all()->pluck('name', 'id')->toArray(),
        //         function ($value) { // if the filter is active
        //             $this->crud->addClause('whereHas', 'permissions', function ($query) use ($value) {
        //                 $query->where('permission_id', '=', $value);
        //             });
        //         }
        //     );
        // }
    }

    public function setupCreateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidation(StoreRequest::class);
    }

    public function setupUpdateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidation(UpdateRequest::class);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitStore();
    }

    /**
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitUpdate();
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

    protected function addUserFields()
    {
        $this->crud->addFields([
            [
                'label' => 'Name',
                'type'  => 'select',
                'name' => 'name',
                'entity' => 'candidate',
                'attribute' => 'name',
                'model' => 'App\Models\Candidate',
                'subfields' => [],
                'attributes' => [
                    'readonly'    => 'readonly',
                    'disabled'    => 'disabled',
                ]
            ],
            [
                'label' => 'Position',
                'type'  => 'select',
                'name' => 'position_id',
                'entity' => 'position',
                'attribute' => 'name',
                'model' => 'App\Models\Position',
                'subfields' => [],
                'attributes' => [
                    'readonly'    => 'readonly',
                    'disabled'    => 'disabled',
                ]
            ],

            [
                'label' => 'Company',
                'type'  => 'select',
                'name' => 'name',
                'entity' => 'Company',
                'attribute' => 'name',
                'model' => 'App\Models\Company',
                'subfields' => [],
                'attributes' => [
                    'readonly'    => 'readonly',
                    'disabled'    => 'disabled',
                ]
            ],


            // [
            //     'label' => 'Company',
            //     'type'  => 'select_multiple',
            //     'name' => 'position_id',
            //     'entitiy' => 'position_id',
            //     'attributes' => [
            //         'readonly'    => 'readonly',
            //         'disabled'    => 'disabled',
            //     ],
            //     'subfields' => [],
            // ],
            // [
            //     'label' => 'Position',
            //     'type'  => 'hidden',
            //     'name'  => ['position'],
            //     'entity' => 'Position',
            //     'attribute' => 'name',
            //     'model' => 'App\Models\Position',
            // ],
            [
                // two interconnected entities
                'label'             => 'Role And Permission',
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency',
                'name'              => ['roles', 'permissions'],
                'subfields'         => [
                    'primary' => [
                        'label'            => trans('backpack::permissionmanager.roles'),
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'name', // foreign key attribute that is shown to user
                        'model'            => config('permission.models.role'), // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label'          => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => config('permission.models.permission'), // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                ],
            ],
        ]);
    }
}
