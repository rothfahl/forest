<!DOCTYPE HTML>
<html>
  <head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
    <style>
      body {
        margin: 0px;
        padding: 0px;
      }
      canvas {
        margin: auto;
        top: 0; left: 0; right: 0;
        border-bottom: solid grey 1px;
      }
    </style>
    
  </head>
  <body>
    <div>
    <canvas id="canvas"></canvas>
    </div>
    <p>
      <label for="number-of-trees">Number of trees: <span id="value-number-of-trees"></label>
      <input type="text" id="number-of-trees" style="border:0; color:#f6931f; font-weight:bold;">
    </p>
    <div id="slider-number-of-trees"></div>
    <p>
      <label for="delay">Delay (ms): <span id="value-delay"></label>
      <input type="text" id="delay" style="border:0; color:#f6931f; font-weight:bold;">
    </p>
    <div id="slider-delay"></div>
  </body>
  <script type="text/javascript" src="tree.js"></script>
  <script type="text/javascript">

    var canvas = document.getElementById('canvas');
    var height = $(window).height() * 0.75;
    var width = $(window).width();
    canvas.width = width;
    canvas.height = height;
    tree.setCanvas(canvas);
    tree.init();

    $(document).ready(function(){
      $(function() {
        $("#slider-number-of-trees").slider({
          value: tree.defaults.numberOfTrees,
          min: 1,
          max: 30,
          slide: function( event, ui ) {
            console.log('numberOfTrees changed');
            tree.clear();
            numberOfTrees = ui.value;
            $("#value-number-of-trees").text(numberOfTrees);
            tree.setParam('numberOfTrees', numberOfTrees);
            tree.init();
          }
        });
        $("#slider-delay").slider({
          value: tree.defaults.delay,
          min: 0,
          max: 2000,
          step: 100,
          slide: function( event, ui ) {
            console.log('delay changed');
            tree.clear();
            delay = ui.value;
            $("#value-delay").text(delay);
            tree.setParam('delay', delay);
            tree.init();
          }
        });
      });
    });


    </script>
</html>      