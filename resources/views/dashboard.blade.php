@extends('app')

@section('content')
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
      'Nissan' => 1.1,
      'Imprimart' => 2,
      
    );

// Set graph colors
$color = array(
      '#154360',
      '#27AE60',
      '#999999',
      '#3498DB',
      '#A93226',
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
  'title' => 'Incrementos por meses',
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
<div class="col-xs-12">
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