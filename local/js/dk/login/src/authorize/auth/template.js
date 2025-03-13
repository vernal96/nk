export const template = `
<form action="?" @submit="submit" method="post">
    <div class="modal__form">
        <div class="note note--error" v-if="errorMessage" v-html="errorMessage"></div>
        <input type="hidden" :name="postField.key" :value="postField.value" v-for="postField in post">
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="AUTH">
        <template v-if="isLoginForm">
            <label class="input">
                 <span class="input__title">{{ lang.login.title }}</span>
                 <input type="text" name="USER_LOGIN" value="" :placeholder="lang.login.placeholder" autocomplete="off"/>
            </label>
            <label class="input">
                 <span class="input__title">{{ lang.password.title }}</span>
                 <input type="password" name="USER_PASSWORD" value="" :placeholder="lang.password.placeholder" autocomplete="off"/>
            </label>
            <label class="checkbox" v-if="storePassword">
                 <input type="checkbox" name="USER_REMEMBER" value="Y">
                 <span class="checkbox__fake"></span>
                 <span class="checkbox__content">{{ lang.storePassword }}</span>
            </label>
        </template>
        <template v-else-if="isOtpForm">
            <label class="input">
                 <span class="input__title">Одноразовый пароль</span>
                 <input type="text" name="USER_OTP" value=""
                          placeholder="Введите одноразовый пароль"/>
            </label>
            <label class="checkbox" v-if="rememberOtp">
                 <input type="checkbox" name="OTP_REMEMBER" value="1">
                 <span class="checkbox__fake"></span>
                 <span class="checkbox__content">Запомнить код</span>
            </label>
        </template>
        <Captcha :code="captchaCode"></Captcha>
        </div>
    <div class="modal__footer">
        <button type="submit" class="button button--orange button--100" :class="{'is-loading': loading}" :disabled="loading">
            <span class="loader" v-if="loading"></span>
            {{ lang.submit }}
        </button>
        <button class="simple-link" style="font-size: 0.9em;" type="button" @click="getComponent('forgotpasswd', 'forgotpasswd')" v-if="isLoginForm">{{ lang.forgetPassword }}</button>
    </div>
</form>
`;