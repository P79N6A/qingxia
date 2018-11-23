<div class="chart">
    <canvas id="barChart" style="height:300px"></canvas>
</div>
<script type="text/javascript">
    var areaChartData = {
        labels: [@forelse($datas as $chart)"{{$chart->rgDate}}"@if($loop->index < count($datas)-1),@endif @endforeach] ,
        // labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
            {
                label               : 'Digital Goods',
                fillColor           : '#00a65a',
                strokeColor         : '#00a65a',
                pointColor          : '#00a65a',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                //  data                : [65, 59, 80, 81, 56, 55, 40]
                data                : [@forelse($datas as $chart){{$chart->scount}}@if($loop->index < count($datas)-1),@endif @endforeach]
            }
        ]
    }
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
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
    barChart.Line(barChartData, barChartOptions);
</script>