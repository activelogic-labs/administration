@extends("administration::layouts.admin")

@section("header")

    <div class="header" style="height: auto;">
        {{--NOTE: include filter language only when filtering--}}
        @if(count($dataset) == 1)
            <h1>{{ $title }} <small>{{ $dataset[0]->total }} {{ \Illuminate\Support\Str::plural($title) }} found matching filters</small></h1>
        @else
            <h1>{{ $title }}</h1>
        @endif

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
                @if($sortable)
                    <div class="applied-sorts">
                        <div class="sort filter-button">
                            <span class="column"></span>
                            <span class="direction"></span>
                            <a class="remove-sort"><i class="fa fa-times-circle"></i></a>
                        </div>
                        @each('administration::components.sort', $sorts, 'sort')
                    </div>
                @endif
                <div class="filter-actions">
                    @include('administration::components.add-sort')
                    @include('administration::components.add-filter')
                    @include('administration::components.apply-filters')
                </div>
            </div>
        @endif

        <div class="buttons">

            @if($enable_adding_records)
                @can('create', $model)
                    <a href="{{ $detail_url }}"><i class="icon fa fa-plus"></i> Create New {{ \Illuminate\Support\Str::singular($title) }}</a>
                @endcan
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

    @foreach($dataset as $key => $overviewComponent)

        @if(count($dataset) > 1)

            <div>
                <h2>{{ $overviewComponent->label }} <small>{{ $overviewComponent->caption }}</small></h2>
            </div>

        @endif

        @if($overviewComponent->total == 0)

            <div class="missing_records">There are no records...</div>

        @else

            <table class="table">
                <thead>
                    <tr>
                        @foreach($overviewComponent->overviewFields as $value)
                            <th>{{ $value }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($overviewComponent->data as $row)
                        <tr @if($enableDetailView) href="{{ $detail_url . "/" . $row['id']->value }}" @else class="no_link" @endif>
                            @foreach($overviewComponent->overviewFields as $id => $value)
                                <td>{!! $row[$id]->dataView() !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            {{ $overviewComponent->pagination }}

        @endif

    @endforeach

@endsection