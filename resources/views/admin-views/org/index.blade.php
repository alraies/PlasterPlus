@extends('layouts.admin.app')

@section('title',__('messages.Add new org'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{__('messages.org')}}</h1>
                </div>
                @if(isset($org))
                <a href="{{route('admin.org.add')}}" class="btn btn-primary pull-right"><i class="tio-add-circle"></i> {{__('messages.add')}} {{__('messages.new')}} {{__('messages.org')}}</a>
                @endif
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-header"><h5>{{isset($org)?__('messages.update'):__('messages.add').' '.__('messages.new')}} {{__('messages.org')}}</h5></div>
            <div class="card-body">
                <form action="{{route('admin.org.store')}}" method="post" >
                    @csrf
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'en')
                    @if($language)
                        @php($default_lang = json_decode($language)[0])
                        <ul class="nav nav-tabs mb-4">
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($language)
                        @foreach(json_decode($language) as $lang)
                            <div class="form-group {{$lang != $default_lang ? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.name')}} ({{strtoupper($lang)}})</label>
                                <input type="text" name="OrgName[]" class="form-control" placeholder="{{__('messages.new_org')}}" maxlength="191" {{$lang == $default_lang? 'required':''}} oninvalid="document.getElementById('en-link').click()">
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">
                        @endforeach
                    @else
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{__('messages.name')}}</label>
                            <input type="text" name="OrgName" class="form-control" placeholder="{{__('messages.new_org')}}" value="{{old('name')}}" required maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                    @endif
                    <!-- <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.name')}}</label>
                        <input type="text" name="OrgName" value="{{isset($org)?$org['name']:''}}" class="form-control" placeholder="New Category" required>
                    </div> -->
                    <input name="position" value="0" style="display: none">
                    <div class="form-group">
                        <label>{{__('messages.image')}}</label><small style="color: red">* ( {{__('messages.ratio')}} 1:1)</small>
                        <div class="custom-file">
                            <input type="file" name="photo" id="customFileEg1" class="custom-file-input"
                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                            <label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0%;">
                        <center>
                            <img style="width: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                @if(isset($org))
                                src="{{asset('storage/app/public/org')}}/{{$org['image']}}"
                                @else
                                src="{{asset('assets/admin/img/900x400/img1.jpg')}}"
                                @endif
                                alt="photo"/>
                        </center>
                    </div>
                    <div class="form-group pt-2">
                        <button type="submit" class="btn btn-primary">{{isset($org)?__('messages.update'):__('messages.add')}}</button>
                    </div>

                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header pb-0">
                <h5>{{__('messages.org')}} {{__('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$orgs->total()}}</span></h5>
                <form id="dataSearch">
                    @csrf
                    <!-- Search -->
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tio-search"></i>
                            </div>
                        </div>
                        <input type="search" name="search" class="form-control" placeholder="{{__('messages.search_orgs')}}" aria-label="{{__('messages.search_orgs')}}">
                        <button type="submit" class="btn btn-light">{{__('messages.search')}}</button>
                    </div>
                    <!-- End Search -->
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-align-middle" style="width:100%;"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">{{__('messages.#')}}</th>
                                <th style="width: 10%">{{__('messages.id')}}</th>
                                <th style="width: 30%">{{__('messages.OrgName')}}</th>
                                <th style="width: 25%">{{__('messages.action')}}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                        @foreach($orgs as $key=>$org)
                            <tr>
                                <td>{{$key+$orgs->firstItem()}}</td>
                                <td>{{$org->id}}</td>
                                <td>
                                <span class="d-block font-size-sm text-body">
                                    {{Str::limit($org['OrgName'], 20,'...')}}
                                </span>
                                </td>



                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer page-area">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $orgs->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
        </div>

    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================


            $('#dataSearch').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('admin.org.search')}}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        $('#table-div').html(data.view);
                        $('#itemCount').html(data.count);
                        $('.page-area').hide();
                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>
@endpush
