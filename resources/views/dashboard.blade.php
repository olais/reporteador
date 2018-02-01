@extends('app')

@section('content')


<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">



      function circular(){

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart2);

      }
      circular();

      var intevalo = setInterval(circular,500);
     

      function drawChart2() {

     $.post("llenadashboard",{ "_token": token },
          function(resul){
          uno=resul.dato;
          var data = google.visualization.arrayToDataTable([
          ['Task', 'Ordenes por día'],
          ['Preprensa',  uno],
          ['Indigo',      2],
          ['Encuadernado',  2],
          ['Empaque', 2],
          ['Envíos',    7]
        ]);
         var options = {
          title: 'Productividad'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);

           },'json');
         
        

      
      }







      google.charts.load('current', {'packages':['timeline']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();

        dataTable.addColumn({ type: 'string', id: 'President' });
        dataTable.addColumn({ type: 'date', id: 'Start' });
        dataTable.addColumn({ type: 'date', id: 'End' });
        dataTable.addRows([
          [ 'Washington', new Date(1789, 3, 30), new Date(1797, 2, 4) ],
          [ 'Adams',      new Date(1797, 2, 4),  new Date(1801, 2, 4) ],
          [ 'Jefferson',  new Date(1801, 2, 4),  new Date(1809, 2, 4) ]]);

        chart.draw(dataTable);
      }
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart3);

      function drawChart3() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2013',  1000,      400],
          ['2014',  1170,      460],
          ['2015',  660,       1120],
          ['2016',  1030,      540]
        ]);

        var options = {
          title: 'Company Performance',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }

    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart4);
    function drawChart4() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["Copper", 8.94, "#b87333"],
        ["Silver", 10.49, "silver"],
        ["Gold", 19.30, "gold"],
        ["Platinum", 21.45, "color: #e5e4e2"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Density of Precious Metals, in g/cm^3",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawSeriesChart);

    function drawSeriesChart() {

      var data = google.visualization.arrayToDataTable([
        ['ID', 'Life Expectancy', 'Fertility Rate', 'Region',     'Population'],
        ['CAN',    80.66,              1.67,      'North America',  33739900],
        ['DEU',    79.84,              1.36,      'Europe',         81902307],
        ['DNK',    78.6,               1.84,      'Europe',         5523095],
        ['EGY',    72.73,              2.78,      'Middle East',    79716203],
        ['GBR',    80.05,              2,         'Europe',         61801570],
        ['IRN',    72.49,              1.7,       'Middle East',    73137148],
        ['IRQ',    68.09,              4.77,      'Middle East',    31090763],
        ['ISR',    81.55,              2.96,      'Middle East',    7485600],
        ['RUS',    68.6,               1.54,      'Europe',         141850000],
        ['USA',    78.09,              2.05,      'North America',  307007000]
      ]);

      var options = {
        title: 'Correlation between life expectancy, fertility rate ' +
               'and population of some world countries (2010)',
        hAxis: {title: 'Life Expectancy'},
        vAxis: {title: 'Fertility Rate'},
        bubble: {textStyle: {fontSize: 11}}
      };

      var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
      chart.draw(data, options);
    }
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart5);

      function drawChart5() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]);

        var options = {
          title: 'Company Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }

    </script>
  </head>
  <body>
     <div id="piechart" style="width: 900px; height: 500px;"></div>
     <div id="timeline" style="height: 180px;"></div>
     <div id="chart_div" style="width: 100%; height: 500px;"></div>
     <div id="barchart_values" style="width: 900px; height: 300px;"></div>
     <div id="series_chart_div" style="width: 900px; height: 500px;"></div>
      <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
  </body>
</html>




<div class="col-xs-12">
    <div class="row">
     <div class="col-xs-6">
  <?php
      /** Include class */
include( 'grafica/GoogChart.class.php' );


$chart = new GoogChart();

      $data = array(
            'FotoSmile STD' =>500,
            'FotoSmile PRO' =>300,
            'FotoFoto' =>100,
            'Pwinty' => 36.5,
            'Nissan' => 20,
            'Imprimart' => 2,
           );

      // Set graph colors
      $color = array(
            '#154360',
            '#27AE60',
            '#999999',
            '#3498DB',
            '#C0392B',
            '#F1C40F',
          );


      $chart->setChartAttrs( array(
        'type' => 'pie',
        'title' => 'Ordenes Facturadas',
        'data' => $data,
        'size' => array( 400, 300 ),
        'color' => $color
        ));

      echo $chart;



  ?>
</div>
<div class="col-xs-6">
  <?php  
  // Set multiple graph data
  $dataMultiple = array( 
    'Febrero 2018' => array(
      'STD' =>90,
      'PRO' =>70,
      'FotoFoto' =>100,
      'Pwinty' =>60,
      'Nissan' => 1,
      'Imprimart' => 2,
    ),
    'Enero 2018' => array(
      'STD' =>20,
      'PRO' =>30,
      'FotoFoto' =>50,
      'Pwinty' => 40,
      'Nissan' => 1,
      'Imprimart' => 2,
    ),
  );

/* # Chart 2 # */

$chart->setChartAttrs( array(
  'type' => 'bar-vertical',
  'title' => 'Incrementos por mes',
  'data' => $dataMultiple,
  'size' => array(1000, 200 ),
  'color' => $color,
  'labelsXY' => true,
  ));
// Print chart
echo $chart;


  ?>
</div>
</div>
</div>

<div class="col-xs-12" style='margin-top:50px;'>
    <div class="row">
     <div class="col-xs-6">
      <?php
        // Set timeline graph data
$dataTimeline = array( 
    '2016' => array(
      'STD' =>90,
      'PRO' =>70,
      'FotoFoto' =>100,
      'Pwinty' =>60,
      'Nissan' => 1,
      'Imprimart' => 2,
      ),
    '2017' => array(
      'STD' =>20,
       'PRO' =>30,
      'FotoFoto' =>50,
      'Pwinty' => 40,
      'Nissan' => 1,
      'Imprimart' => 2,
      ),
    '2018' => array(
      'STD' =>20,
      'PRO' =>30,
      'FotoFoto' =>50,
      'Pwinty' => 40,
      'Nissan' => 1,
      'Imprimart' => 2,
      ),
  );

/* # Chart 3 # */

$chart->setChartAttrs( array(
  'type' => 'sparkline',
  'title' => 'Incrementos Anuales',
  'data' => $dataTimeline,
  'size' => array( 550, 200 ),
  'color' => $color,
  'labelsXY' => true,
  'fill' => array( '#eeeeee', '#aaaaaa' ),
  ));
// Print chart
echo $chart;

      ?>
    </div>
    </div>
</div>


 @endsection