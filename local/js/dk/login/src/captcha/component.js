import {template} from "./template";

export const component =  {
    name: "Captcha",
    props: {
        code: {
            type: String,
            required: false
        }
    },
    data: () => ({
        lang: {
            title: BX.message.CAPTCHA_TITLE,
            placeholder: BX.message.CAPTCHA_PLACEHOLDER
        }
    }),
    computed: {
        url() {
            return `/bitrix/tools/captcha.php?captcha_sid=${this.code}`;
        }
    },
    template: template
};