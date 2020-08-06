<?php
header('Set-Cookie: cross-site-cookie=http://google.com/; SameSite=None; Secure');
?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
<link href="{{ url('js/is-loading/css/index.min.css') }}" rel="stylesheet">
<style>
    input {
        border: 1px solid silver;
        border-radius: 4px;
        background: white;
        padding: 5px 10px;
    }

    .dirty {
        border-color: #5A5;
        background: #EFE;
    }

    .dirty:focus {
        outline-color: #8E8;
    }

    .error {
        border-color: red;
        background: #FDD;
    }

    .error:focus {
        outline-color: #F99;
    }

</style>
<body>
<div id="app">
    <form id="frm_01" class="form_content" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="csrf" name="csrf" value=""/>
        <input type="hidden" id="fb_id" name="fb_id" value=""/>
        <input type="hidden" id="fb_email" name="fb_email" value=""/>
        <input type="hidden" id="fb_name" name="fb_name" value=""/>
        <div class="form radius10">
            <div class="formleft">
                <ul>
                    <li>姓名：@{{ username }}</li>
                    <li>性別：@{{ gender }}</li>
                    <li>生日：@{{ year + '/' + month + '/' + day }}</li>
                    <li>電話：@{{ phone }}</li>
                    <li>手機：@{{ mobile }}</li>
                    <li>Email：@{{ email }}</li>
                    <li>住址：@{{ county + district + address }}</li>
                </ul>
            </div>

            <div class="formright">
                <ul>
                    <li>
                        <input v-model="$v.username.$model" :class="status($v.username)" required>
                    </li>
                    <li>
                        <input type="radio"  value="male" v-model="gender" required>
                        <label for="male">男</label>
                        <br>
                        <input type="radio" id="female" value="female" v-model="gender" required>
                        <label for="female">女</label>
                    </li>
                    <li>
                        <select v-model="year" class="inputcss bir_01">
                            <option :value="null" disabled selected="selected">請選擇</option>
                            <option :value="2021-yyyy" v-for="yyyy in years" :key="yyyy">@{{ 2021-yyyy }}</option>
                        </select>年
                        <select v-model="month" class="inputcss bir_01">
                            <option :value="null" disabled selected="selected">請選擇</option>
                            <option v-for="mm in months.length" :value="mm">@{{ mm }}</option>
                        </select>
                        月
                        <select v-model="day" class="inputcss bir_01">
                            <option :value="null" disabled selected="selected">請選擇</option>
                            <option v-for="dd in getDays" :value="dd">@{{ dd }}</option>
                        </select>
                        日
                    </li>
                    <li><input type="number" class="inputcss" v-model="phone"></li>
                    <li><input type="number" class="inputcss" v-model="mobile"></li>
                    <li><input type="email" class="inputcss" v-model="email" placeholder="Email">
                    </li>
                    <li>
                        <select v-model="county" class="inputcss bir_01" @change="resetDistrict">
                            <option :value="null" disabled selected="selected">請選擇</option>
                            <option :value="item" v-for="item in getCounty" :key="item">@{{ item }}</option>
                        </select>
                        <select v-model="district" class="inputcss bir_01">
                            <option :value="null" disabled selected="selected">請選擇</option>
                            <option :value="item" v-for="item in getDistrict" :key="item">@{{ item }}
                            </option>
                        </select>
                    </li>
                    <li>
                        <input   required>
                    </li>
                    <li class="agreerow"><input type="checkbox" v-model="agreeTerms"> 我已詳讀並同意抽獎及活動<a
                            href="rule.html" target="_blank">相關規定</a>。
                    </li>
                    <!-- <li><span>備註：請逐項完整填寫上列資訊，每人僅可填寫乙次。</span></li> -->
                </ul>
            </div>
            <button :class="{'-disable': formCheck !== 'submit'}" :type="formCheck" @click="validateAlert" @submit="submitHandler">確認送出</button>


            <!--送出按鈕為disable狀態時，請把class名稱改為dissendbtn-->
        </div>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.26.0/babel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.10.4/polyfill.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.4.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.js"
        integrity="sha512-PyKhbAWS+VlTWXjk/36s5hJmUJBxcGY/1hlxg6woHD/EONP2fawZRKmvHdTGOWPKTqk3CPSUPh7+2boIBklbvw=="
        crossorigin="anonymous"></script>
<script src="{{ url('js/is-loading/dist/isLoading.min.js') }}"></script>
<script src="{{ url('js/vuelidate/dist/vuelidate.min.js') }}"></script>
<script src="{{ url('js/vuelidate/dist/validators.min.js') }}"></script>
<script src="{{ url('js/zipcode.js') }}"></script>
<script src="{{ url('js/axios.min.js') }}"></script>

<script type="text/babel" src="{{ url('js/reg.js') }}"></script>

</body>
</html>

