function Tree(params) {
  this.forest = {};
  this.defaults = {
    branches: 3,
    trunkSize : 16
  };
  this.currentParams = {};
  this.startPoint = [];
  this.trunkLength = 0;
  this.currentParams = function() {
    this.currentParams = this.defaults;
    return this.currentParams;
  };
  this.getParam = function(key) {
    if(this.currentParams[key] != undefined) {
      return this.currentParams[key];
    } else {
      return this.defaults[key];
    }
  };
  this.setParam = function(key, value) {
    this.currentParams[key] = value;
  };
  this.calcAngle = function(branchNr) {
    if(branchNr == 1) {
      return 90;
    }
    if(branchNr == 2) {
      return 90 + this.forest.getParam('angle');
    }
    if(branchNr == 3) {
      return 90 - this.forest.getParam('angle');
    }
  };
  this.calcNewEndPoint = function(start, length, angle) {
    var angle = 2*Math.PI / 360 * angle; // radians
    angle *= -1;
    x = start[0] + length * Math.cos(angle);
    y = start[1] + length * Math.sin(angle);
    x = Math.round(x);
    y = Math.round(y);

    return this.addWind([x, y]);
  };
  this.addWind = function(point) {
    return [point[0] + this.forest.getParam('wind') / 3, point[1]];
  };
  this.draw = function(startPoint, size, branchLength, branches, depth, color, shiftAngle) {
    if(depth >= 8) {
      return;
    }
    for (var i=1; i <= branches; i++) {
      var angle;
      length = this.forest.randomnessFactor() * branchLength;
      angle = this.calcAngle(i);
      angle += shiftAngle;
      if (1 < depth) {
        angle = angle * this.forest.randomnessFactor();
      }
      endPoint = this.calcNewEndPoint(startPoint, length, angle);

      var ctx = this.forest.context;
      ctx.beginPath();
      ctx.moveTo(startPoint[0], startPoint[1]);
      ctx.lineTo(endPoint[0], endPoint[1]);
      ctx.lineWidth = size;
      ctx.lineJoin = "round";
      ctx.lineCap = "round";
      ctx.strokeStyle = color;
      ctx.stroke();

      this.draw(
        endPoint, 
        size * 0.5,
        branchLength * 0.9,
        this.getParam('branches'),
        depth+1,
        color,
        (angle - 90) % 360
      );
    }
  };
};

var forest = {
  canvas: {},
  context: {},
  trees: [],
  defaults: {
    numberOfTrees : 3,
    randomness : 0,
    angle: 30,
    wind: 0,
  },
  setCanvas: function(canvas) {
    this.canvas = canvas;
    this.context = canvas.getContext('2d');
  },
  randomnessFactor: function() {
    var randomness = this.getParam('randomness');
    return rand(1-(randomness / 200), 1+(randomness / 200))
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
  addTree: function(tree) {
    tree.forest = this;
    this.trees.push(tree);
  },
  clear: function() {
    //remove trees
    this.trees = [];
    //clear canvas
    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
  },
  init: function() {
    this.clear();
    for (var i = 1; i <= this.getParam('numberOfTrees'); i++) {
      var newTree = new Tree();
      newTree.trunkLength = Math.max(10, this.canvas.height/10 * this.randomnessFactor());
      newTree.startPoint = [this.canvas.width * (i/(this.getParam('numberOfTrees')+1)), this.canvas.height];
      this.addTree(newTree);
    }
    this.draw();
  },
  draw: function() {
    for(i in this.trees) {
      this.trees[i].draw(
        this.trees[i].startPoint,
        this.trees[i].getParam("trunkSize"),
        this.trees[i].trunkLength,
        1,
        1,
        "#333",
        0
      );  
    } 
  }
};



function rand(min, max) {
  return Math.random() * (max - min) + min;
}

function randomColor() {
  return '#'+Math.floor(Math.random()*16777215).toString(16);
}
