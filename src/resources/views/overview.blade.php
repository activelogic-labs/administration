@extends("administration::layouts.admin")

@section("header")
    <div class="header" style="height: auto;">
        {{--NOTE: include filter language only when filtering--}}
        <h1>{{ $title }} <small>{{ $data->total()}} {{ \Illuminate\Support\Str::plural($title) }} found matching filters</small></h1>
        @if($filterable)
            <div class="filters">
                <div class="applied-filters">
                    <div class="filter filter-button">
                        <span class="column"></span>
                        <span class="value"></span>
                        <a class="remove-filter"><i class="fa fa-times-circle"></i></a>
                    </div>
                    @each('administration::components.filter', $filters, 'filter')
                </div>
                <div class="filter-actions">
                    @include('administration::components.sort', $sorts, 'sorts')
                    @include('administration::components.add-filter')
                    @include('administration::components.apply-filters')
                </div>
            </div>
        @endif

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
        <div class="clearfix"></div>
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
                    <tr @if($enableDetailView) href="{{ $detail_url . "/" . $row['id']->value }}" @else class="no_link" @endif>
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