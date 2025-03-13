import {template} from "./template";
import {getComponent, submit} from "../../index";
import {component as Back} from "../../back/component";
import {component as Captcha} from "../../captcha/component";

export const component =  {
    name: "Auth",
    data: () => ({
        action: "auth",
        loading: false,
        formType: "",
        errorMessage: "",
        storePassword: false,
        post: [],
        get: [],
        rememberOtp: false,
        captchaCode: "",
        lang: {
            login: {
                title: BX.message.LOGIN_TITLE_LOGIN,
                placeholder: BX.message.LOGIN_PLACEHOLDER_LOGIN,
            },
            password: {
                title: BX.message.LOGIN_TITLE_LOGIN,
                placeholder: BX.message.LOGIN_PLACEHOLDER_LOGIN,
            },
            storePassword: BX.message.LOGIN_STORE_PASSWORD,
            submit: BX.message.LOGIN_AUTH_SUBMIT,
            forgetPassword: BX.message.LOGIN_FORGET_PASSWORD
        }
    }),
    computed: {
        isLoginForm() {
            return this.action === "auth";
        }
    },
    methods: {
        submit(event) {
            submit(event, this, () => {}, (response) => {
                if (response.data.formType === "logout") {
                    location.reload();
                    return(2);
                }
            });
        }
    },
    emits: ["onUpdateContent"],
    mounted() {
        getComponent(this);
    },
    components: {
        Back,
        Captcha
    },
    template: template
};