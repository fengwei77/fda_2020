//問題陣列
//答案陣列
//瓶子陣列
const jars_1 = [
    '.jar1_1',
    '.jar1_2',
    '.jar1_3',
    '.jar1_4'
];
//瓶子動作
const t = gsap.timeline();
setTimeout(
    () => {
        for (i = 0; i < 4; i++) {
            t.to(jars_1[i], {
                keyframes: [
                    {
                        onUpdate:checkHit,
                        onUpdateParams:[jars_1[i]],
                        duration: 4 + gsap.utils.random([0, 0.3, 0.5, 0.8]),
                        rotate: gsap.utils.random([-60, 60]),
                        y: 420,
                        ease: CustomEase.create("custom", "M0,0 C0.14,0 0.242,0.438 0.272,0.561 0.313,0.728 0.392,0.963 0.4,1 0.408,0.985 0.431,0.968 0.472,0.906 0.527,0.821 0.599,0.871 0.612,0.88 0.688,0.93 0.719,0.981 0.726,0.998 0.788,0.914 0.84,0.936 0.859,0.95 0.878,0.964 0.897,0.985 0.911,0.998 0.922,0.994 0.939,0.984 0.954,0.984 0.969,0.984 1,1 1,1 ")
                    },
                    {
                        duration: .05,
                        opacity: 0
                    }
                ],

            }, 0)
        }
    }, 800);
//貓

let dragMe = Draggable.create("#player", {
    type: "x",
    edgeResistance: 1,
    inertia: true,
    bounds: ".track",
    onDragEnd: getThePosition,
    onThrowComplete: throwComplete
});


function getThePosition() {
    if (this.hitTest(jars_1[2])) {
        // console.log(jars_1[2]);
    }

}

function throwComplete() {
    console.log(this.x);
}

//物體碰撞
function checkHit(target) {
    // console.log(a);
    if (Draggable.hitTest( target,"#player")){
        console.log(target);
    }
}
