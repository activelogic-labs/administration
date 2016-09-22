<div class="module-area text-center">
    <canvas id="{{ $slug }}" style="float:right;" width="50" height="50"></canvas>
    <div class="constant">{{ $data }}%</div>
    <div class="title">{{ $title }}</div>
</div>

@push('js')
<script>

    var ctx = $("#{{ $slug }}");
    var dataPoint = {{ $data-100 }};

    var data = {
        labels: [],
        datasets: [
            {
                data: [dataPoint, 100-dataPoint],
                backgroundColor: [
                    "{{ $options['secondary_color'] }}",
                    "{{ $options['primary_color'] }}"
                ],
                hoverBackgroundColor: []
            }]
    };

    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {}
    });

</script>
@endpush