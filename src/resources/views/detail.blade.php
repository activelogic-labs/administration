@extends("administration::layouts.admin")

@section("header")
    <div class="header">
        <h1>{{ $title }} <span id="subtitle">{{ $subtitle }}</span></h1>
        <div class="buttons">
            <a id="backButton" href="{{ $back_url }}"><i class="fa fa-angle-left"></i> Back</a>
            <a id="deleteButton" href="{{ $delete_url }}" style="border: solid 1px #c50000; color: #c50000" onClick="Javascript:return confirm('Are you sure you want to delete this record?');"><i class="fa fa-trash"></i> Delete</a>
        </div>
    </div>
@endsection

@section("content")

    <form method="POST" id="detailForm" action="{{ $save_url }}" enctype="multipart/form-data">

        {{ csrf_field() }}

        @foreach($detailGroups as $detailGroup)

            <!-- Group: Standard -->
            @if($detailGroup['group_type'] == \Activelogiclabs\Administration\Admin\Core::GROUP_STANDARD)

                <div class="data-header">
                    <h1>{{ $detailGroup['group_title'] }}</h1>
                    <ul>
                        <li><span><i class="fa fa-pencil"></i> Click field to edit</span></li>
                        <li><input type="submit" name="submit" value="Save" /></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group">

                    @foreach($detailGroup['data'] as $key => $row)
                        @foreach($detailGroup['group_fields'] as $id => $value)
                            <div class="data-group-field">
                                <div class="title">{{ $value }}</div>
                                <div class="value">{!! $row[$id]->fieldView() !!}</div>
                            </div>
                        @endforeach
                    @endforeach

                    <div class="clearfix"></div>

                </div>

            @endif

            <!-- Group: WYSIWYG -->
            @if($detailGroup['group_type'] == \Activelogiclabs\Administration\Admin\Core::GROUP_WYSIWYG)

                <div class="data-header">
                    <h1>{{ $detailGroup['group_title'] }}</h1>
                    <ul>
                        <li><span><i class="fa fa-pencil"></i> Click field to edit, then click save</span></li>
                        <li><input type="submit" name="submit" value="Save" /></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group group_wysiwyg">
                    {!! $detailGroup['data']->fieldView() !!}
                </div>

            @endif

            <!-- Group: Full -->
            @if($detailGroup['group_type'] == \Activelogiclabs\Administration\Admin\Core::GROUP_FULL)

                <div class="data-header">
                    <h1>{{ $detailGroup['group_title'] }}</h1>
                    <ul>
                        <li><span><i class="fa fa-pencil"></i> Click field to edit</span></li>
                        <li><input type="submit" name="submit" value="Save" /></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group full_page">
                    {!! $detailGroup['data']->fieldView() !!}
                </div>

            @endif

        @endforeach

    </form>

@endsection

@section("scripts")


@endsection