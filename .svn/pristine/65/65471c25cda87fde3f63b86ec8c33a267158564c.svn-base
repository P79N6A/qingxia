<div class="chart">
    <canvas id="barChart" style="height:800px"></canvas>
</div>

<script type="text/javascript">
    var areaChartData = {
        labels: [@forelse($data['list'] as $chart)"{{$chart->bookName}}"@if($loop->index < count($data['list'])-1),@endif @endforeach] ,

        datasets: [
            {
                label               : 'Digital Goods',
                fillColor           : '#00a65a',
                strokeColor         : '#00a65a',
                pointColor          : '#00a65a',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [@forelse($data['list'] as $chart){{$chart->scount}}@if($loop->index < count($data['list'])-1),@endif @endforeach]
            }
        ]
    }
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
    //barChartData.datasets[0].fillColor   = '#00a65a'
    //barChartData.datasets[0].strokeColor = '#00a65a'
    //barChartData.datasets[0].pointColor  = '#00a65a'
    var barChartOptions                  = {
        scaleBeginAtZero        : true,
        scaleShowGridLines      : true,
        scaleGridLineColor      : 'rgba(0,0,0,.05)',
        scaleGridLineWidth      : 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines  : true,
        barShowStroke           : true,
        barStrokeWidth          : 2,
        barValueSpacing         : 5,
        barDatasetSpacing       : 1,
        responsive              : true,
        maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false;
    barChart.Bar(barChartData, barChartOptions);

</script>