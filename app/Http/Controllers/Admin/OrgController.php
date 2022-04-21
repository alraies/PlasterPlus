<?php

Namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Org;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Models\Translation;
use app\Scopes;
class OrgController extends Controller
{
    function index()
    {
        $orgs=Org::latest()->paginate(config('default_pagination'));
        return view('admin-views.org.index',compact('orgs'));
    }





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
    public function get_org_data(Org $org)
    {
        return response()->json($org);
    }
    function store(Request $request)
    {
        // $request->validate([
        //     'OrgName' => 'required',
        // ]);
        $data  = '';
        DB::table('orgs')->insert([
            'OrgName' => $request->OrgName[0],
            'photo' => $request->photo,
            'created_at' => now(),
        ]);



        Toastr::success(trans('messages.org_added_successfully'));
        return back();
    }

    public function edit($orgId)
    {
        $org = Org::withoutGlobalScope('translate')->findOrFail($orgId);
        return view('admin-views.org.edit', compact('org'));
    }

    public function IsActive(Request $request)
    {
        $org = Org::find($request->orgId);
        $org->IsActive = $request->IsActive;
        $org->save();
        Toastr::success(trans('messages.org_status_updated'));
        return back();
    }

    public function update(Request $request, $orgId)
    {
        $request->valorgIdate([
            'OrgName' => 'required|max:100|unique:orgs,OrgName,'.$orgId,
        ]);
        $org = Org::find($orgId);

        $org->OrgName = $request->OrgName[array_search('en', $request->lang)];
        $org->image = $request->has('image') ? Helpers::update('org/', $org->image, 'png', $request->file('image')) : $org->image;
        $org->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->OrgName[$index] && $key != 'en')
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\Org',
                        'translationable_orgId'    => $org->orgId,
                        'locale'                => $key,
                        'key'                   => 'OrgName'],
                    ['value'                 => $request->OrgName[$index]]
                );
            }
        }
        Toastr::success(trans('messages.org_updated_successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $org = Org::findOrFail($request->orgId);

            $org->delete();
            Toastr::success('Org removed!');

        return back();
    }

    public function get_all(Request $request){
        $data = Org::where('OrgName', 'like', '%'.$request->q.'%')->limit(8)->get([DB::raw('orgId, OrgName ')]);
        if(isset($request->all))
        {
            $data[]=(object)['orgId'=>'all', 'text'=>'All'];
        }
        return response()->json($data);
    }


    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $orgs=Org::
        when($request->sub_org, function($query){
            return $query;
        })
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('OrgName', 'like', "%{$value}%");
            }
        })->limit(50)->get();


        return response()->json([
            'view'=>view('admin-views.org.partials._table',compact('orgs'))->render(),
            'count'=>$orgs->count()
        ]);
    }
}
