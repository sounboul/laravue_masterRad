<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use App\Laravue\Models\Stores;
use App\Laravue\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class UserController extends BaseController
{
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $store = Arr::get($searchParams, 'store', '');
        $active = Arr::get($searchParams, 'active', '');
        $department = Arr::get($searchParams, 'department', '');
        $keyword = Arr::get($searchParams, 'keyword', '');


        if (empty($role) && empty($active) && empty($store) && empty($department)) {
            $userQuery->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $keyword . '%');
        }

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($active)) {
            if (!empty($keyword)) {
                $userQuery->where('active', $active)
                            ->where('name', 'LIKE', '%' . $keyword . '%')
                            ->Where('email', 'LIKE', '%' . $keyword . '%');
            }
            else
            {
                $userQuery->where('active', $active);
            }
        }

        if (!empty($store)) {

            $location = Stores::where('name', $store)->first();
            if (!empty($keyword)) {
                $userQuery->where('stores_id', $location->id)
                        ->where('name', 'LIKE', '%' . $keyword . '%')
                        ->Where('email', 'LIKE', '%' . $keyword . '%');
            }
            else
            {
                $userQuery->where('stores_id', $location->id);
                
            }
        }

        if(!empty($department)) {

            $department1 = Department::where('name', $department)->first();
            if (!empty($keyword)) {
                    $userQuery->where('department_id', $department1->id)
                            ->where('name', 'LIKE', '%' . $keyword . '%')
                            ->Where('email', 'LIKE', '%' . $keyword . '%');
                }
            else
            {
                $userQuery->where('department_id', $department1->id);
                
            }

        }

        return UserResource::collection($userQuery->paginate($limit));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'email' => 'unique:users',
                    'password' => ['required', 'min:6'],
                    'confirmPassword' => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            if ($params['store'] === null) {
                $params['store'] = 1;
            }
            if ($params['department'] === null) {
                $params['department'] = 1;
            }
            if($params['files']) {
                $file = $request['files'];
                //dd(json_encode($file['url']));
                $pom = $request['files']['picture'];
                
                $image_64 = $pom['url']; //your base64 encoded data

                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

                $replace = substr($image_64, 0, strpos($image_64, ',')+1); 

                // find substring fro replace here eg: data:image/png;base64,

                $image = str_replace($replace, '', $image_64); 

                $image = str_replace(' ', '+', $image); 

                $imageName = time().'.'.$extension;

                Storage::disk('my_images')->put($imageName, base64_decode($image));

                $file_path = "users/".$imageName;
            }
            else {
                $file_path = null;
            }
            $user = User::create([
                'name'          => $params['name'],
                'email'         => $params['email'],
                'password'      => Hash::make($params['password']),
                'phone'         => $params['phone'],
                'stores_id'     => $params['store'],
                'department_id' => $params['department'],
                'avatar'        => $file_path,
            ]);
            $role = Role::findByName($params['role']);
            $user->syncRoles($role);

            return new UserResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        //dd($request->all());
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && $currentUser->id !== $user->id
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_USER_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $email = $request->get('email');
            $found = User::where('email', $email)->first();
            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'Email has been taken'], 403);
            }

            $user->name = $request->get('name');
            $user->email = $email;
            if($request->new_password !== null) {
                $user->password = Hash::make($request->new_password);
            }
            $user->phone = $request->phone;
            if (is_numeric($request->store)) {
                $user->stores_id = $request->store;
            }
            if (is_numeric($request->department)) {
                $user->department_id = $request->department;
            }
            if ($request->role !== null) {
                $role = Role::findByName($request->role);
                $user->syncRoles($role);
            }
            $user->save();
            return new UserResource($user);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function($permission) {
                return $permission['id'];
            },

            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Ehhh! Can not delete admin user'], 403);
        }

        try {
            $user->active = 'deleted';
            $user->update();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }
}
