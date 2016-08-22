@extends("administration::layouts.admin")

@section("header")
    <div class="header">
        <h1>{{ $title }} <span>{{ $subtitle }}</span></h1>
        <div class="buttons">
            <a href="/"><i class="fa fa-angle-left"></i> Back</a>
        </div>
    </div>
@endsection

@section("content")

    <form action="{{ $save_url }}" method="POST">

        @foreach($detailData as $detailGroup)

            <!-- Group: Standard Field -->
            @if($detailGroup['group_type'] == \Activelogiclabs\Administration\Admin\Core::GROUP_STANDARD)

                <div class="data-header">
                    <h1>{{ $detailGroup['group_title'] }}</h1>
                    <ul>
                        <li><span><i class="fa fa-pencil"></i> Click field to edit</span></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group">

                    @foreach($detailGroup['data'] as $key => $row)
                        @foreach($detailGroup['group_fields'] as $id => $value)
                            <div class="data-group-field">
                                <div class="title">{{ $value }}</div>
                                <div class="value">{!! $row[$id]->fieldView() !!}</div>
                                <div class="submit"><input type="button" name="save" value="Save"></div>
                            </div>
                        @endforeach
                    @endforeach

                    <div class="clearfix"></div>

                </div>

            @endif

            <!-- Group: Full Page -->
            @if($detailGroup['group_type'] == \Activelogiclabs\Administration\Admin\Core::GROUP_WYSIWYG)

                <div class="data-header">
                    <h1>{{ $detailGroup['group_title'] }}</h1>
                    <ul>
                        <li><span><i class="fa fa-pencil"></i> Click field to edit, then click save</span></li>
                        <li><a href="#" class="data-group-submit">Save</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="data-group full_page">

                    @foreach($detailGroup['data'] as $key => $row)
                        @foreach($detailGroup['group_fields'] as $id => $value)
                            {!! $row[$id]->fieldView() !!}
                        @endforeach
                    @endforeach

                </div>

            @endif

        @endforeach

    </form>

@endsection

@section("scripts")


@endsection