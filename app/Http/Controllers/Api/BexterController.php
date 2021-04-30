<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\customers_tico;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Http;
use App\Laravue\Models\Credentials;

class BexterController extends BaseController
{
    private function bexterAPI()
    {
        $bexterAPI = Credentials::find(2);
        return $bexterAPI;
    }

    
    public function customers_level_API(Request $request)
    {
        $username = $request->header('php-auth-user');
        $pass = $request->header('php-auth-pw');
        
        if ($username == self::bexterAPI()->username && $pass == self::bexterAPI()->password)
        {
			$customer = customers_tico::where('customer_id', $request->id)->first();
			$level = MemberLevel::findLevel($customer->total_points);
			$pom = MemberLevel::findLevelAPI($level);

			return response()->json([
					'total_points' => $customer->total_points,
					'level' => $level,
					'level_strength' => $pom->level_strength,
					'discount_percent' => $pom->discount_percent/10,
				],
				200,
				[],
				JSON_NUMERIC_CHECK
			);
        }
        else
        {
          	return response()->json(['error' => 'Neispravni podaci']);
        }
    }
}
