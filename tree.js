var tree = {
  canvas: {},
  context: {},
  defaults: {
    'numberOfTrees' : 3,
    'branches': 3,
    'angleLimits' : [150, 30],
    'angle': 30,
    'trunkSize' : 10,
    'randomness' : 0,
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
  randomnessFactor: function() {
    var randomness = this.getParam('randomness');
    return rand(1-(randomness / 200), 1+(randomness / 200))
  },
  timers: [],
  clear: function() {
    //stop timers
    for(i in this.timers) { clearTimeout(this.timers[i]) };
    this.timers = [];  
    //clear canvas
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
  },
  init: function() {
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height)
    for (var i = 1; i <= this.getParam('numberOfTrees'); i++) {
      //trunkLength should be at least 10
      trunkLength = Math.max(10, this.canvas.height/10 * this.randomnessFactor());
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
  calcAngle: function(branchNr) {
    if(branchNr == 1) {
      return 90 + this.getParam('angle');
    }
    if(branchNr == 2) {
      return 90;
    }
    if(branchNr == 3) {
      return 90 - this.getParam('angle');
    }
  },
  calcNewEndPoint: function(start, length, angle) {
    var angle = 2*Math.PI / 360 * angle; // radians
    angle *= -1;
    x = start[0] + length * Math.cos(angle);
    y = start[1] + length * Math.sin(angle);
    x = Math.round(x);
    y = Math.round(y);

    return [x, y];
  },
  drawTree: function(delay, startPoint, size, branchLength, branches, depth, color, shiftAngle) {
    if(size < 0.5) {
      return;
    }
    for (var i=1; i <= branches; i++) {
      length = this.randomnessFactor() * branchLength;
      if (depth == 1) {
        endPoint = [startPoint[0], startPoint[1] - branchLength];
        angle = 90;
      } else {
        angle = this.calcAngle(i);
        angle = angle * this.randomnessFactor();
        angle += shiftAngle;
        endPoint = this.calcNewEndPoint(startPoint, length, angle);
      }
      console.log(angle);
      
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
        (angle - 90) % 360
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