@foreach($orgs as $key=>$org)
<tr>
    <td>{{$key+1}}</td>
    <td>{{$org->orgId}}</td>
    <td>
    <span class="d-block font-size-sm text-body">
        {{Str::limit($org['name'], 20, '...')}}
    </span>
    </td>
    <!-- <td>
        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$org->orgId}}">
        <input type="checkbox" onclick="location.href='{{route('admin.org.IsActive',[$org['orgId'],$org->IsActive?0:1])}}'"class="toggle-switch-input" orgId="stocksCheckbox{{$org->orgId}}" {{$org->IsActive?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
    </td> -->

    <td>
        <a class="btn btn-sm btn-white"
            href="{{route('admin.org.edit',[$org['orgId']])}}" title="{{__('messages.edit')}} {{__('messages.org')}}"><i class="tio-edit"></i>
        </a>
        <a class="btn btn-sm btn-white" href="javascript:"
        onclick="form_alert('org-{{$org['orgId']}}','Want to delete this org')" title="{{__('messages.delete')}} {{__('messages.org')}}"><i class="tio-delete-outlined"></i>
        </a>
        <form action="{{route('admin.org.delete',[$org['orgId']])}}" method="post" orgId="org-{{$org['orgId']}}">
            @csrf @method('delete')
        </form>
    </td>
</tr>
@endforeach
