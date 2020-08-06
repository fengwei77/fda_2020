/* setupVeeValidate.js */

import Vue from 'vue'
import VeeValidate from 'vee-validate'
import localeService from 'services/localeService'

// support lang
import tw from 'vee-validate/dist/locale/zh_TW'
import en from 'vee-validate/dist/locale/en'

// global config
const config = {
    // 初始語系 (tw / en)
    locale: localeService.getCurrentLang(),
    // 觸發檢核的時機(輸入or離開輸入框)
    events: 'input|blur',
    // 預設各語系檢核失敗提示的文字 (需要對應 locale 設定)
    dictionary: { tw, en }
}

Vue.use(VeeValidate, config)
