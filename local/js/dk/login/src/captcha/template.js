export const template = `
<div class="form-fieldset form-fieldset--horizontal form-fieldset--min-gap" v-if="code">
    <input type="hidden" name="captcha_sid" :value="code">
    <img :src="url" alt="CAPTCHA" class="captcha form-fieldset-item">
    <label class="input form-fieldset-item" style="width: 100%;">
        <span class="input__title">{{ lang.title }}</span>
        <input type="text" name="captcha_word" value="" :placeholder="lang.placeholder"/>
    </label>
</div>
`;