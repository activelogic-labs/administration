@extends("active-admin::layouts.admin")

@section("header")
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="buttons">
            <a href="/"><i class="icon"></i>Create A Player</a>
        </div>
    </div>
@endsection

@section("content")

    <h1>Upcoming Courses <span>In the next 7 days</span></h1>

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
        <ul class="pagination">
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
        </ul>
    </div>

@endsection