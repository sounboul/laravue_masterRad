<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\User;
use App\Laravue\Models\Department;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'status' => $this->email_verified_at,
            'active' => $this->active,
            'store' => $this->stores->name,
            'department' => Department::find($this->department_id)->name,
            'f_account' => $this->f_account,
            'i_account' => $this->i_account,
            'l_account' => $this->l_account,
            'roles' => array_map(
                function ($role) {
                    return $role['name'];
                },
                $this->roles->toArray()
            ),
            'permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->getAllPermissions()->toArray()
            ),
            'avatar' => 'images/'.$this->avatar,
            /*'avatar' => 'https://i.pravatar.cc',*/
        ];
    }
}
