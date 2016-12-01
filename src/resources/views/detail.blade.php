@extends("administration::layouts.admin")

@section("header")
    <div class="header">
        <h1>{{ $title }} <span id="subtitle">{{ $subtitle }}</span></h1>
        <div class="buttons">
            <a id="backButton" href="{{ \Illuminate\Support\Facades\URL::previous() }}"><i class="fa fa-angle-left"></i> Back</a>
            <a id="deleteButton" href="{{ $delete_url }}" onClick="Javascript:return confirm('Are you sure you want to delete this record?');"><i class="fa fa-trash"></i> Delete</a>
            <a href="#" id="saveDetailForm"><i class="fa fa-floppy-o"></i> Save</a>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection

@section("content")


    <form method="POST" id="detailForm" action="{{ $save_url }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        @foreach($detailGroups as $detailGroup)

            <!-- Group: Standard -->
                @if($detailGroup->type == \Activelogiclabs\Administration\Admin\Core::GROUP_STANDARD)

                    <div class="data-header">
                        <h1>{{ $detailGroup->label }}</h1>
                        <div class="clearfix"></div>
                    </div>

                    <div class="data-group">

                        @foreach($detailGroup->data as $key => $row)
                            @foreach($detailGroup->fields as $id => $value)
                                <div class="data-group-field">
                                    <div class="title">{{ $value }}</div>
                                    <div class="value">{!! $row[$id]->fieldView() !!}</div>
                                </div>
                            @endforeach
                        @endforeach

                        <div class="clearfix"></div>

                    </div>

                @endif

            <!-- Group: Relationship -->
                @if($detailGroup->type == \Activelogiclabs\Administration\Admin\Core::GROUP_RELATIONSHIP)

                    <div class="data-header">
                        <h1>RELATIONSHIP GROUP NOT WORKING</h1>
                        <div class="clearfix"></div>
                    </div>

                    {{--<div class="data-header">--}}
                        {{--<h1>{{ $detailGroup->label }}</h1>--}}
                        {{--<div class="clearfix"></div>--}}
                    {{--</div>--}}

                    {{--<div class="data-group">--}}

                        {{--@foreach($detailGroup->data as $key => $row)--}}
                            {{--@foreach($detailGroup->fields as $id => $value)--}}
                                {{--<div class="data-group-field">--}}
                                    {{--<div class="title">{{ $value }}</div>--}}
                                    {{--<div class="value">{!! $row[$id]->fieldView() !!}</div>--}}
                                {{--</div>--}}
                            {{--@endforeach--}}
                        {{--@endforeach--}}

                        {{--<div class="clearfix"></div>--}}

                    {{--</div>--}}

                @endif

            <!-- Group: WYSIWYG -->
            @if($detailGroup->type == \Activelogiclabs\Administration\Admin\Core::GROUP_WYSIWYG)

                <div class="data-header">
                    <h1>{{ $detailGroup->label }}</h1>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group group_wysiwyg">
                    {!! $detailGroup->data->fieldView() !!}
                </div>

            @endif

            <!-- Group: Full -->
            @if($detailGroup->type == \Activelogiclabs\Administration\Admin\Core::GROUP_FULL)

                <div class="data-header">
                    <h1>{{ $detailGroup->label }}</h1>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group full_page">
                    {!! $detailGroup->data->fieldView() !!}
                </div>

            @endif

            <!-- Group: MANY (For existing records only) -->
            @if($detailGroup->type == \Activelogiclabs\Administration\Admin\Core::GROUP_MANY && $isNewRecord == false)

                <div class="data-header">
                    <h1>{{ $detailGroup->label }}</h1>
                    <ul>
                        <li><a href="#"><i class="fa fa-plus" aria-hidden="true"></i> New</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group full_page">

                    @include('administration::partials.overview_component', [
                        'dataset' => $detailGroup->data,
                        'enableDetailView' => true,
                        'detail_url' => \Activelogiclabs\Administration\Admin\Core::url($detailGroup->controller->slug . "/detail")
                    ])

                </div>

            @endif

        @endforeach

    </form>

    @include("administration::partials.modal")

@endsection

@section("scripts")


@endsection