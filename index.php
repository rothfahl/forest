<!DOCTYPE HTML>
<html>
  <head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
    <script src="lib/jquery.ui.touch-punch.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
    <style>
      body {
        height: 100vh;
        margin: 0px;
        padding: 0px;
      }
      #canvas-container {
        height: 66%;
      }
      canvas {
        padding: 0;
        margin: 0;
        border-bottom: solid grey 1px;
      }
      #sliders {
        width: 50%;
        margin: 0 auto;
      }
      #sliders p {
        margin: 10px 0 5px 0;
      }
      label {
        font-family: monospace;
      }
    </style>
    
  </head>
  <body>
    <div id="canvas-container">
      <canvas id="canvas"></canvas>
    </div>
    <div id="sliders">
      <p>
        <label for="number-of-trees">Number of trees: <span class="value" id="value-number-of-trees"></label>
        <input type="text" id="number-of-trees" style="border:0; color:#f6931f; font-weight:bold;">
      </p>
      <div id="slider-number-of-trees"></div>
      <p>
        <label for="delay">Delay (ms): <span class="value" id="value-delay"></label>
        <input type="text" id="delay" style="border:0; color:#f6931f; font-weight:bold;">
      </p>
      <div id="slider-delay"></div>
    </div>
  </body>
  <script type="text/javascript" src="tree.js"></script>
  <script type="text/javascript">

    var canvas = document.getElementById('canvas');
    tree.setCanvas(canvas);
    tree.init();

    $(document).ready(function(){
      //Run function when browser resizes
      $(window).resize(resizeCanvas);
      function resizeCanvas() {
        console.log("new width=", $("#canvas-container").outerWidth(), "new height=", $("#canvas-container").outerHeight());
        canvasElement = $("#canvas");
        canvasElement.attr('width', $("#canvas-container").width());
        canvasElement.attr('height', $("#canvas-container").height());
        //re-draw trees
        tree.clear();
        tree.init(); 
      }
      //Initial call 
      resizeCanvas();

      // sliders
      $("#slider-number-of-trees").slider({
        value: tree.defaults.numberOfTrees,
        min: 1,
        max: 20,
        slide: function( event, ui ) {
          console.log('numberOfTrees changed');
          tree.clear();
          numberOfTrees = ui.value;
          $("#value-number-of-trees").text(numberOfTrees);
          tree.setParam('numberOfTrees', numberOfTrees);
          tree.init();
        }
      }).prev().find(".value").text(tree.defaults.numberOfTrees);
      
      $("#slider-delay").slider({
        value: tree.defaults.delay,
        min: 0,
        max: 1000,
        step: 100,
        slide: function( event, ui ) {
          console.log('delay changed');
          tree.clear();
          delay = ui.value;
          $("#value-delay").text(delay);
          tree.setParam('delay', delay);
          tree.init();
        }
      }).prev().find(".value").text(tree.defaults.delay);
    });
    </script>
</html>      