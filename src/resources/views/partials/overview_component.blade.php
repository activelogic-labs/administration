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
        <div class="pagination_alignment">
            {{ $overviewComponent->pagination }}
        </div>

    @endif

@endforeach