//問題陣列
//答案陣列
//瓶子陣列
//瓶子動作
//貓

var dragMe = Draggable.create("#player", {
    type: "x",
    edgeResistance: 1,
    inertia: true,
    bounds: ".track",
    onDragEnd: getThePosition,
    onThrowComplete: throwComplete
});
function getThePosition() {
    console.log(this.endX);
}
function throwComplete() {
    console.log(this.x);
}
//物體碰撞