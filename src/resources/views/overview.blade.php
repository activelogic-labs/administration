@extends("administration::layouts.admin")

@section("header")
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="buttons">

            @if($enable_adding_records)
            <a href="{{ $detail_url }}"><i class="icon fa fa-plus"></i> Create New {{ \Illuminate\Support\Str::singular($title) }}</a>
            @endif

            @if($enable_exporting_records)
            <a href="{{ $export_url }}"><i class="icon fa fa-upload"></i> Export {{ \Illuminate\Support\Str::plural($title) }}</a>
            @endif

            @foreach($title_buttons as $button)
                <a href="{{ $button["route_uri"] }}"><i class="icon fa {{ $button["icon"] }}"></i> {{ $button["title"] }}</a>
            @endforeach
        </div>
    </div>
@endsection

@section("content")

    @if($data->total() == 0)

        <div class="missing_records">There are no records...</div>

    @else

        <table class="table">
            <thead>
                <tr>
                    @foreach($overviewFields as $key => $value)
                        <th>{{ $value }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($data as $key => $row)
                    <tr @if($enableDetailView) href="{{ $detail_url . "/" . $key }}" @else class="no_link" @endif>
                        @foreach($overviewFields as $id => $value)
                            <td>{!! $row[$id]->dataView() !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div align="center">
            {{ $page_links }}
        </div>

    @endif

@endsection