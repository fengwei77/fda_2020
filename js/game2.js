//問題陣列
//答案陣列
//瓶子陣列
const jars = [
    '.jar1',
    '.jar2',
    '.jar3',
    '.jar4'
];
const ans_check_arr = [[4],[4],[1],[3],[1,2],[1,3]];
//瓶子動作
const t = gsap.timeline();
let game_level = 0;
let change_no = 1;
let answer_check = '';
let answer = [];

t.pause();
// var interval = setInterval(function () {
//     game_level++;
//     change_no++;
//     answer_check = '';
//     t.restart();
//     if (game_level === 5) {
//         clearInterval(interval);
//     }
// //do whatever here..
// }, 6000);
setTimeout(function () {
    t.play();
}, 500);
// setTimeout(function () {
//     game_level++;
//     change_no++;
//     t.restart();
// }, 8000);
for (i = 0; i < 4; i++) {
    t.to(jars[i], {
        onStart:next_scene,
        keyframes: [
            {
                duration: 1,
                opacity: 1
            },
            {
                duration: 2,
                opacity: 1
            },
            {
                onUpdate: checkHit,
                onUpdateParams: [jars[i]],
                duration: 1 + gsap.utils.random([0, 0.3, 0.5, 0.8]),
                rotate: gsap.utils.random([-60, 60]),
                y: 400,
                ease: CustomEase.create("custom", "M0,0 C0.14,0 0.242,0.438 0.272,0.561 0.313,0.728 0.392,0.963 0.4,1 0.408,0.985 0.431,0.968 0.472,0.906 0.527,0.821 0.599,0.871 0.612,0.88 0.688,0.93 0.719,0.981 0.726,0.998 0.788,0.914 0.84,0.936 0.859,0.95 0.878,0.964 0.897,0.985 0.911,0.998 0.922,0.994 0.939,0.984 0.954,0.984 0.969,0.984 1,1 1,1 ")
            },
            {
                duration: .05,
                opacity: 0
            },
            {
                duration: 0,
                y: 0,
                opacity: 0
            },
            {
                duration: 2
            }
        ]
    }, 0)
}
// t.repeat(6).eventCallback('onRepeat' , function(){
//     console.log(this);
// });
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
    if (this.hitTest(jars[2])) {
        // console.log(jars_1[2]);
    }
}

function throwComplete() {
    console.log(this.x);
}

//物體碰撞

function checkHit(target) {
    // console.log(a);
    if (answer_check == '') {
        if (Draggable.hitTest(target, "#player", "50%")) {
            answer_check = target.slice(-1);
            $(target).css('opacity',0);
            answer.push(target.slice(-1))
            check_answer(target.slice(-1));
            // console.log(answer);
        }
    }
    // if (game_level === 5) {
    //     // clearInterval(interval);
    //     $('.q6wrap').hide();
    // }
}


function change_jars_image(no) {
    for (let i = 0; i < 4; i++) {
        $(jars[i]).attr('src', 'images/gameb/q' + no + 'p.png');
    }
}

function next_scene() {
    if (change_no == 2) {
        $('.jar_box').css('padding-right', '50px');
    } else {
        $('.jar_box').css('padding-right', '70px');
    }
    for (let i = 0; i < 4; i++) {
        $(jars[i]).attr('src', 'images/gameb/q' + change_no + 'p.png');
        // t.to(jars[i], {  y: 0 ,opacity:1} );
    }
}


const wrong_mag_t = gsap.timeline();
const success_mag_t = gsap.timeline();

wrong_mag_t.pause();
success_mag_t.pause();
wrong_mag_t.to('#wrong_msg', {
    keyframes: [
        {
            duration: 0.5,
            opacity: 1
        },
        {
            duration: 2,
            opacity: 1
        },
        {
            duration: 1,
            opacity: 0
        }
    ],
    onStart:function(){
        console.log(change_no);
        $('.q' + (change_no) + 'wrap').hide(800, function() {

        });
    },
    onComplete:function(){
        $('.q' + (change_no+1) + 'wrap').show(800, function() {
            game_level++;
            change_no++;
            answer_check = '';
            t.restart();
            if (game_level === 5) {
                $('.q6wrap').hide();
            }
        });
    }
});
success_mag_t.to('#success_msg', {
    keyframes: [
        {
            duration: 0.5,
            opacity: 1
        },
        {
            duration: 2,
            opacity: 1
        },
        {
            duration: 1,
            opacity: 0
        }
    ],
    onStart:function(){
        console.log(change_no);
        $('.q' + (change_no) + 'wrap').hide(0, function() {

        });
    },
    onComplete:function(){
        $('.q' + (change_no+1) + 'wrap').show(800, function() {
            game_level++;
            change_no++;
            answer_check = '';
            t.restart();
            if (game_level === 5) {
                $('.q6wrap').hide();
            }
        });
    }
});


function check_answer(ans){
    // console.log(ans);
    // console.log('inArray='+ ans_check_arr[0] );
    let temp = ans_check_arr[game_level];
    if(temp.indexOf(ans) >= 0 ||temp == ans){
        //答對
        success_mag_t.restart();
    }else{
        //答錯
        wrong_mag_t.restart();
    }
}