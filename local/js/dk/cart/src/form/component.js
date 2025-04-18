import {template} from "./template";
import {PhoneEvents} from "dk.main.methods";
import {Selector} from "dk.ui.selector";

export const Form = {
    name: "Form",
    props: {
        useFile: {
            type: Boolean,
            required: false,
            default: false
        },
        fullWidth: {
            type: Boolean,
            required: false,
            default: false
        },
        fastpay: {
            type: Boolean,
            required: false,
            default: false
        },
        successFunction: {
            type: Function,
            required: false,
            default: null
        }
    },
    data: () => ({
        loading: false,
        totalError: "",
        lang: {
            placeholder: {
                name: BX.message.DK_NK_PLACEHOLDER_NAME,
                phone: BX.message.DK_NK_PLACEHOLDER_PHONE,
                email: BX.message.DK_NK_PLACEHOLDER_EMAIL,
                ft: BX.message.PLACEHOLDER_FT,
                inn: BX.message.PLACEHOLDER_INN,
                delivery: BX.message.PLACEHOLDER_DELIVERY,
                city: BX.message.PLACEHOLDER_CITY,
                street: BX.message.PLACEHOLDER_STREET,
                house: BX.message.PLACEHOLDER_HOUSE,
                corpus: BX.message.PLACEHOLDER_CORPUS,
                entrance: BX.message.PLACEHOLDER_ENTRANCE,
                office: BX.message.PLACEHOLDER_OFFICE,
                market: BX.message.PLACEHOLDER_MARKET,
                file: BX.message.PLACEHOLDER_FILE,
                fileMax: BX.message.PLACEHOLDER_FILE_MAX,
                comment: BX.message.PLACEHOLDER_COMMENT,
            },
            title: {
                name: BX.message.DK_NK_TITLE_NAME,
                phone: BX.message.DK_NK_TITLE_PHONE,
                email: BX.message.DK_NK_TITLE_EMAIL,
                comment: BX.message.DK_NK_TITLE_COMMENT,
                ft: BX.message.TITLE_FT,
                inn: BX.message.TITLE_INN,
                city: BX.message.TITLE_CITY,
                street: BX.message.TITLE_STREET,
                home: BX.message.TITLE_HOUSE,
                corpus: BX.message.TITLE_CORPUS,
                entrance: BX.message.TITLE_ENTRANCE,
                office: BX.message.TITLE_OFFICE,
                market: BX.message.TITLE_MARKET
            },
            submit: BX.message.CART_SUBMIT,
            agreeText: "",
            org: {
                name: BX.message.CART_ORG_NAME,
                director: BX.message.CART_ORG_DIRECTOR,
                fio: BX.message.CART_ORG_FIO,
                address: BX.message.CART_ORG_ADDRESS,
                notFound: BX.message.CART_ORG_NOT_FOUND,
            },
            fileError: BX.message.CART_FILE_ERROR
        },
        deliveryInfo: DK.deliveries,
        agree: false,
        form: {
            name: "",
            phone: "",
            email: "",
            ft: "",
            inn: "",
            delivery: "delivery",
            deliveryData: {
                city: "",
                street: "",
                house: "",
                corpus: "",
                entrance: "",
                office: "",
                marketId: 0
            },
            comment: "",
            files: []
        },
        ft: [
            {
                value: "jur",
                title: BX.message.FT_JUR
            },
            {
                value: "pht",
                title: BX.message.FT_PHT
            },
        ],
        delivery: [
            {
                value: "delivery",
                title: BX.message.DELIVERY
            },
            {
                value: "self",
                title: BX.message.SELF_DELIVERY
            }
        ],
        companyData: null,
        companyDataError: false,
        fileError: [],
        deliveryCities: [],
        markets: [],
        errorFields: []
    }),
    computed: {
        ready() {
            return this.deliveryCities.length && this.markets.length && this.lang.agreeText;
        }
    },
    watch: {
        ready(value) {
            if (value) {
                this.$emit("onReady");
            }
        }
    },
    emits: ["onReady", "onSuccess"],
    methods: {
        phoneInput(event) {
            PhoneEvents.onInput(event);
        },
        phonePaste(event) {
            PhoneEvents.onPaste(event);
        },
        phoneKeydown(event) {
            PhoneEvents.onKeyDown(event);
        },
        setFormFt(value) {
            this.form.ft = value;
            this.saveField(value, "ft");
            this.removeError("ft");
        },
        setFormCity(value) {
            this.form.deliveryData.city = value;
            this.saveField(value, "city");
            this.removeError("city");
        },
        setFormMarket(value) {
            this.form.deliveryData.marketId = value;
            this.saveField(value, "marketId");
            this.removeError("marketId");
        },
        getDeliveryCities() {
            BX.ajax.runComponentAction("dk:cart", "getDeliveryCities", {
                mode: "class"
            }).then(response => {
                this.deliveryCities = response.data;
            });
        },
        getMarkets() {
            BX.ajax.runComponentAction("dk:cart", "getMarkets", {
                mode: "class"
            }).then(response => {
                this.markets = response.data;
            });
        },
        getAgreeText() {
            BX.ajax.runComponentAction("dk:cart", "getAgreeText", {
                mode: "class"
            }).then(response => {
                this.lang.agreeText = response.data;
            });
        },
        checkFile(event) {
            this.form.files = [];
            this.fileError = [];
            Array.from(event.target.files).forEach(file => {
                if (file.size < maxFileSize * 1000000) {
                    this.form.files.push(file);
                } else {
                    this.fileError.push(file.name);
                }
            });
        },
        getCompanyByInn() {
            if (this.form.inn === "") {
                this.companyDataError = false;
                this.companyData = null;
                return;
            }
            BX.ajax.runComponentAction("dk:cart", "getCompanyByInn", {
                mode: "class",
                data: {
                    inn: this.form.inn
                }
            }).then(response => {
                if (response.data) {
                    this.companyData = response.data;
                    this.companyDataError = false;
                } else {
                    this.companyData = null;
                    this.companyDataError = true;
                }
            });
        },
        getUserData() {
            BX.ajax.runComponentAction("dk:cart", "getUserData", {
                mode: "class",
            }).then(response => {
                if (response.data) {
                    const data = response.data;
                    this.form.name = data.name;
                    this.form.phone = data.phone;
                    this.form.email = data.email;
                    this.form.inn = data.inn;
                    this.form.ft = data.ft;
                    this.form.comment = data.comment;
                    if (data.delivery) {
                        this.form.delivery = data.delivery;
                    }
                    this.form.deliveryData.city = +data.city;
                    this.form.deliveryData.street = data.street;
                    this.form.deliveryData.house = data.house;
                    this.form.deliveryData.corpus = data.corpus;
                    this.form.deliveryData.entrance = data.entrance;
                    this.form.deliveryData.office = data.office;
                    this.form.deliveryData.marketId = data.marketId;
                }
            });
        },
        saveFieldName() {
            this.saveField(this.form.name, "name");
        },
        saveFieldPhone() {
            this.saveField(this.form.phone, "phone");
        },
        saveFieldEmail() {
            this.saveField(this.form.email, "email");
        },
        saveFieldINN() {
            this.saveField(this.form.inn, "inn");
        },
        saveFieldDeliveryType() {
            this.saveField(this.form.delivery, "delivery");
        },
        saveFieldStreet() {
            this.saveField(this.form.deliveryData.street, "street");
        },
        saveFieldHouse() {
            this.saveField(this.form.deliveryData.house, "house");
        },
        saveFieldCorpus() {
            this.saveField(this.form.deliveryData.corpus, "corpus");
        },
        saveFieldEntrance() {
            this.saveField(this.form.deliveryData.entrance, "entrance");
        },
        saveFieldOffice() {
            this.saveField(this.form.deliveryData.office, "office");
        },
        saveFieldComment() {
            this.saveField(this.form.comment, "comment");
        },
        saveField(value, fieldName) {
            BX.ajax.runComponentAction("dk:cart", "saveOrderField", {
                mode: "class",
                data: {
                    field: fieldName,
                    value: value
                }
            });
        },
        submit(event) {
            if (this.loading) return;
            this.loading = true;
            BX.ajax.runComponentAction("dk:cart", "submit", {
                mode: "class",
                data: new FormData(event.target)
            }).then(
                response => {
                    BX.onCustomEvent(window, 'onCartSubmitSuccess', {
                        sum: response.data.sum
                    });
                    this.totalError = "";
                    if (!response.data.success) {
                        this.errorFields = response.data.fields;
                        this.loading = false;
                        if (response.data.error) {
                            this.totalError = response.data.error;
                        }
                    } else {
                        if (this.fastpay) {
                            this.loading = false;
                            if (this.successFunction) {
                                this.successFunction(response.data.message);
                            }
                        } else {
                            location.reload();
                        }
                    }
                },
                response => {
                    this.totalError = response.errors[0].message;
                    this.loading = false;
                }
            );
        },
        removeError(fieldName) {
            this.errorFields = this.errorFields.filter(item => item !== fieldName);
        }
    },
    beforeMount() {
        this.getUserData();
    },
    mounted() {
        this.getDeliveryCities();
        this.getMarkets();
        this.getAgreeText();
    },
    components: {
        Selector
    },
    template: template
};