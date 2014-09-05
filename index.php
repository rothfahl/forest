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
    <script type="text/javascript">
    $(document).ready(function(){
      $(function() {
        $("#slider-number-of-trees").slider({
          range: "min",
          value: 2,
          min: 1,
          max: 20,
          slide: function( event, ui ) {
            numberOfTrees = ui.value;
            init(numberOfTrees);
          }
        });
      });
    });
    </script>
  </head>
  <body>
    <div>
    <canvas id="canvas"></canvas>
    </div>
    <p>
      <label for="number-of-trees">Number of trees:</label>
      <input type="text" id="number-of-trees" style="border:0; color:#f6931f; font-weight:bold;">
    </p>
    <div id="slider-number-of-trees"></div>

    <script type="text/javascript">
      var canvas = document.getElementById('canvas');
      var context = canvas.getContext('2d');

      var height = $(window).height() * 0.75;
      var width = $(window).width();
      canvas.width = width;
      canvas.height = height;

      var angleLimits = [30, 150];
      
      init(2, 50);

      function init(numberOfTrees, delay) {
        context.clearRect(0,0,canvas.width,canvas.height)
        for (var i = 1; i <= numberOfTrees; i++) {
          trunkLength = height/10 * rand(0.8, 1.2);
          drawTree(delay, [width * (i/(numberOfTrees+1)), height], 16, trunkLength, 1, 1, '#333', 0);
        }
      }

      function drawTree(delay, startPoint, size, branchLength, branches, depth, color, shiftAngle) {
        if(size < 0.5) {
          return;
        }
        
        for (var i=1; i <= branches; i++) {
          length = rand(0.8, 1.2) * branchLength;
          //console.log("generation = " + depth, "length = "+ branchLength);
          if (depth == 1) {
            endPoint = [startPoint[0], startPoint[1] - branchLength];
            lastAngle = 90;
          } else {
            curAngleRange = calcAngleRange(angleLimits, branches, i-1);
            //console.log("angeRange before shift = " + curAngleRange);
            curAngleRange[0] += shiftAngle;
            curAngleRange[1] += shiftAngle;
            endPoint = calcNewEndPoint(startPoint, length, curAngleRange);
            lastAngle = endPoint[2];
          }
          
          context.beginPath();
          context.moveTo(startPoint[0], startPoint[1]);
          context.lineTo(endPoint[0], endPoint[1]);
          context.lineWidth = size;
          context.lineJoin = "round";
          context.lineCap = "round";
          context.strokeStyle = color;
          context.stroke();
          
          window.setTimeout(
            function(delay, endPoint, size, branchLength, n, depth, color, shiftAngle){
              drawTree(delay, endPoint, size, branchLength, n, depth + 1, color, shiftAngle);
            },
            delay, 
            delay,
            endPoint, 
            size / 2,
            branchLength * 0.9,
            rand(3,5),
            depth,
            color,
            (lastAngle - 90) % 360
          );
        }
      }

      function calcNewEndPoint(start, length, angleRange) {
        angle = rand(angleRange[0], angleRange[1]); // degrees
        angleRad = 2*Math.PI / 360 * angle; // radians
        angleRad *= -1;
        x = start[0] + length * Math.cos(angleRad);
        y = start[1] + length * Math.sin(angleRad);
        x = Math.round(x);
        y = Math.round(y);

        return [x, y, angle];
      }

      function rand(min, max) {
        return Math.random() * (max - min) + min;
      }

      function randomColor()
      {
        return '#'+Math.floor(Math.random()*16777215).toString(16);
      }

      function calcAngleRange(limits, parts, curPart)
      {
        var partLength = (limits[1] - limits[0]) / parts;
        var lower = limits[0] + (curPart * partLength) * rand(0.75, 1.25);
        var upper = lower +  partLength * rand(0.75, 1.25);
        return [lower, upper]; 
      }
    </script>
  </body>
</html>      