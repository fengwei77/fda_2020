//問題陣列
//答案陣列
//瓶子陣列
const jars_1 = [
    'jar1_1',
    'jar1_2',
    'jar1_3',
    'jar1_4'
];
//瓶子動作
const t = gsap.timeline();
setTimeout(
    () => {
        for (i = 0; i < 4; i++) {
            t.to('.' + jars_1[i], {
                duration: 2 + rand(0,100),
                y: 600,
                ease: 'bounce'
            })
        }
    }, 1000);
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