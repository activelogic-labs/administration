@extends("administration::layouts.admin")

@section("header")
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="buttons">
            <a href="{{ $detail_url }}"><i class="icon fa fa-plus"></i> Create New {{ \Illuminate\Support\Str::singular($title) }}</a>
            <a href="{{ $export_url }}"><i class="icon fa fa-upload"></i> Export {{ \Illuminate\Support\Str::plural($title) }}</a>

            @foreach($title_buttons as $button)
                <a href="{{ $button["route_uri"] }}"><i class="icon fa fa-upload"></i> Export {{ $button["title"] }}</a>
            @endforeach
        </div>
    </div>
@endsection

@section("content")

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
                <tr href="{{ $detail_url . "/" . $key }}">
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

@endsection