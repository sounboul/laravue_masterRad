<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\Customers;
use App\Laravue\Models\MemberLevel;

class CustomerResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'dob' => $this->dob,
            'ID_number' => $this->ID_number,
            'street' => $this->street,
            'number' => $this->number,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'member_since' => $this->member_since,
            'order_id' => $this->order_id,
            'total_points' => $this->total_points,
            'level' => $this->level,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}