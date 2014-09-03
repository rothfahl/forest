<!DOCTYPE HTML>
<html>
  <head>
    <style>
      body {
        margin: 0px;
        padding: 0px;
      }
      canvas {
        margin: auto;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        border-bottom: solid grey 1px;
      }
    </style>
  </head>
  <body>
    <canvas id="canvas" width="1200" height="600"></canvas>
    <script type="text/javascript">
      var canvas = document.getElementById('canvas');
      var context = canvas.getContext('2d');

      var height = canvas.height;
      var width = canvas.width;
      var delay = 0;
      var angleLimits = [30, 150];
      var numberOfTrees = 1;

      for (var i = 1; i <= numberOfTrees; i++) {
        drawTree([width * (i/(numberOfTrees+1)), height], 16, rand(80, 150), 1, 1, '#333', 0);
      }

      function drawTree(startPoint, size, branchLength, branches, depth, color, shiftAngle) {
        if(size < 0.5) {
          return;
        }
        
        
        for (var i=1; i <= branches; i++) {
          length = rand(0.8, 1.2) * branchLength;
          console.log("generation = " + depth, "length = "+ branchLength);
          if (depth == 1) {
            endPoint = [startPoint[0], startPoint[1] - branchLength];
            lastAngle = 90;
          } else {
            curAngleRange = calcAngleRange(angleLimits, branches, i-1);
            console.log("angeRange before shift = " + curAngleRange);
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
            function(endPoint, size, branchLength, n, depth, color, shiftAngle){
              drawTree(endPoint, size, branchLength, n, depth + 1, color, shiftAngle);
            },
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