<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\RestaurantLogic;
use App\Http\Controllers\Controller;
use App\Models\Org;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class OrgController extends Controller
{

    public function get_Orgs(Request $request)
    {
        $cat = Org::withoutGlobalScope(OrgScope::class)->withoutGlobalScope('translate')->where(['orgId' => $request->orgId])->active()->get();
        $res = '';
        foreach ($cat as $row) {
            $res .= '<option value="' . $row->orgId.'"';
            if(count($request->data))
            {
                $res .= in_array($row->orgId, $request->data)?'selected':'';
            }
            $res .=  '>' . $row->OrgName . '</option>';
        }
        return response()->json([
            'options' => $res,
        ]);
    }
    function list()
    {
        $orgs=DB::table('orgs')->get();
        return $orgs;
    }



}
