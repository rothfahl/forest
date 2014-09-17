var tree = {
  canvas: {},
  context: {},
  defaults: {
    'numberOfTrees' : 3,
    'branches': 3,
    'angleLimits' : [30, 150],
    'trunkSize' : 10,
    'delay' : 100
  },
  currentParams: function() {
    this.currentParams = this.defaults;
    return this.currentParams;
  },
  getParam: function(key) {
    if(this.currentParams[key] != undefined) {
      return this.currentParams[key];
    } else {
      return this.defaults[key];
    }
  },
  setParam: function(key, value) {
    this.currentParams[key] = value;
  },
  setCanvas: function(canvas) {
    this.canvas = canvas;
    this.context = canvas.getContext('2d');
  },
  getContext: function() {
    return this.context;
  },
  timers: [],
  clear: function() {
    //stop timers
    for(i in this.timers) { clearTimeout(this.timers[i]) };
    this.timers = [];  
    //clear canvas
    this.context.clearRect(0,0,this.canvas.width,this.canvas.height);
  },
  init: function() {
    this.context.clearRect(0,0,this.canvas.width,this.canvas.height)
    for (var i = 1; i <= this.getParam('numberOfTrees'); i++) {
      trunkLength = this.canvas.height/10 * rand(0.5, 1.5);
      this.drawTree(
        this.getParam('delay'),
        [this.canvas.width * (i/(this.getParam('numberOfTrees')+1)), this.canvas.height],
        this.getParam('trunkSize'),
        trunkLength,
        1, 
        1, 
        '#333', 
        0
      );
    }
  },
  calcAngleRange: function(curPart) {
    var limits = tree.getParam('angleLimits');
    var partLength = (limits[1] - limits[0]) / this.getParam('branches');
    var lower = limits[0] + (curPart * partLength) * rand(0.75, 1.25);
    var upper = lower +  partLength * rand(0.75, 1.25);

    return [lower, upper]; 
  },
  calcNewEndPoint: function(start, length, angleRange) {
    var angle = rand(angleRange[0], angleRange[1]); // degrees
    var angleRad = 2*Math.PI / 360 * angle; // radians
    angleRad *= -1;
    x = start[0] + length * Math.cos(angleRad);
    y = start[1] + length * Math.sin(angleRad);
    x = Math.round(x);
    y = Math.round(y);

    return [x, y, angle];
  },
  drawTree: function(delay, startPoint, size, branchLength, branches, depth, color, shiftAngle) {
    if(size < 0.5) {
      return;
    }
    for (var i=1; i <= branches; i++) {
      length = rand(0.75, 1.25) * branchLength;
      if (depth == 1) {
        endPoint = [startPoint[0], startPoint[1] - branchLength];
        lastAngle = 90;
      } else {
        var curAngleRange = this.calcAngleRange(i-1);
        curAngleRange[0] += shiftAngle;
        curAngleRange[1] += shiftAngle;
        endPoint = this.calcNewEndPoint(startPoint, length, curAngleRange);
        lastAngle = endPoint[2];
      }
      
      this.context.beginPath();
      this.context.moveTo(startPoint[0], startPoint[1]);
      this.context.lineTo(endPoint[0], endPoint[1]);
      this.context.lineWidth = size;
      this.context.lineJoin = "round";
      this.context.lineCap = "round";
      this.context.strokeStyle = color;
      this.context.stroke();
      
      var timer = setTimeout(
        function(delay, endPoint, size, branchLength, n, depth, color, shiftAngle){
          tree.drawTree(delay, endPoint, size, branchLength, n, depth + 1, color, shiftAngle);
        },
        delay,
        delay,
        endPoint, 
        size * 0.6,
        branchLength * 0.9,
        this.getParam('branches'),
        depth,
        color,
        (lastAngle - 90) % 360
      );
      this.timers.push(timer);
    }
  }
};  

function rand(min, max) {
  return Math.random() * (max - min) + min;
}

function randomColor() {
  return '#'+Math.floor(Math.random()*16777215).toString(16);
}