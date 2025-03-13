export const template = `
<form enctype="multipart/form-data" method="post" class="cart__form" :class="{'cart__form--full': fullWidth}" v-if="ready" @submit.prevent="submit">
    <input type="hidden" value="1" name="fastpay" v-if="fastpay">
    <div class="note note--error cart__form-item cart__form-item--full" v-if="totalError">
            <div class="text-content">
                {{ totalError }}
            </div>
        </div>
    <label class="input input--required cart__form-item">
        <span class="input__title">{{ lang.title.name }}</span>
        <input 
        type="text" 
        :placeholder="lang.placeholder.name" 
        v-model="form.name" 
        @change="saveFieldName"
        :class="{'is-error': errorFields.includes('name')}"
        @focus="removeError('name')"
        >
    </label>
    <label class="input input--required cart__form-item">
        <span class="input__title">{{ lang.title.phone }}</span>
        <input 
        type="tel" 
        :placeholder="lang.placeholder.phone" 
        v-model="form.phone"
        @input="phoneInput"
        @paste="phonePaste"
        @keydown="phoneKeydown"
        @change="saveFieldPhone"
        :class="{'is-error': errorFields.includes('phone')}"
        @focus="removeError('phone')"
        >
    </label>
    <label class="input cart__form-item">
        <span class="input__title">{{ lang.title.email }}</span>
        <input 
        type="email" 
        :placeholder="lang.placeholder.email" 
        v-model="form.email" 
        @change="saveFieldEmail"
        :class="{'is-error': errorFields.includes('email')}"
        @focus="removeError('email')"
        >
    </label>
    <Selector
    :options="ft"
    mainClass="cart__form-item"
    :placeholder="lang.placeholder.ft"
    @onChange="setFormFt"
    :title="lang.title.ft"
    :value="form.ft"
    :isError="errorFields.includes('ft')"
    ></Selector>
    <template v-if="form.ft == 'jur'">
        <label class="input input--required cart__form-item cart__form-item--full">
            <span class="input__title">{{ lang.title.inn }}</span>
            <input 
            type="number" 
            :placeholder="lang.placeholder.inn" 
            v-model="form.inn"
            @change="getCompanyByInn(), saveFieldINN()"
            :class="{'is-error': errorFields.includes('inn')}"
            @focus="removeError('inn')"
            >
        </label>
        <div class="note note--info cart__form-item cart__form-item--full" v-if="companyData">
            <div class="text-content">
                <strong>{{ lang.org.name }}:</strong> {{ companyData.name.short_with_opf }}
                <br>
                <template v-if="companyData.fio">
                <strong>{{ lang.org.fio }}:</strong> {{ companyData.fio.surname }} {{ companyData.fio.name }} {{ companyData.fio.patronymic }}
                </template>
                <template v-if="companyData.management">
                <strong>{{ lang.org.director }}:</strong> {{ companyData.management.name }}
                </template>
                <br>
                <strong>{{ lang.org.address }}:</strong> {{ companyData.address.value }}
            </div>
        </div>
        <div class="note note--error cart__form-item cart__form-item--full" v-if="companyDataError">
            <div class="text-content">
                {{ lang.org.notFound }}
            </div>
        </div>
    </template>
    <div class="cart__form-item cart__form-item--full">
        <div class="form-label">{{ lang.placeholder.delivery }}</div>
        <div class="form-fieldset form-fieldset--horizontal">
            <label class="checkbox checkbox--radio" v-for="deliveryType in delivery">
                <input type="radio" :value="deliveryType.value" v-model="form.delivery" @change="saveFieldDeliveryType">
                <span class="checkbox__fake"></span>
                <span class="checkbox__content">{{ deliveryType.title }}</span>
            </label>
        </div>
    </div>
    <template v-if="form.delivery == 'delivery'">
        <div class="cart__form-item cart__form-item--full" v-if="!fastpay">
            <div class="note note--info text-content">
                <p v-for="deliveryInfoItem in deliveryInfo">
                    <b>{{ deliveryInfoItem.title }}</b>
                    <br>
                    {{ deliveryInfoItem.description }}
                </p>
            </div>
        </div>
        <Selector
        :options="deliveryCities"
        mainClass="cart__form-item"
        :placeholder="lang.placeholder.city"
        :value="form.deliveryData.city"
        :title="lang.title.city"
        :isError="errorFields.includes('city')"
        @onChange="setFormCity"
        ></Selector>
        <label class="input cart__form-item">
            <span class="input__title">{{ lang.title.street }}</span>
            <input type="text" :placeholder="lang.placeholder.street" v-model="form.deliveryData.street" @change="saveFieldStreet">
        </label>
        <label class="input cart__form-item cart__form-item--min">
            <span class="input__title">{{ lang.title.home }}</span>
            <input 
            type="text" 
            :placeholder="lang.placeholder.house" 
            v-model="form.deliveryData.house" 
            @change="saveFieldHouse"
            :class="{'is-error': errorFields.includes('house')}"
            @focus="removeError('house')"
            >
        </label>
        <label class="input cart__form-item cart__form-item--min">
            <span class="input__title">{{ lang.title.corpus }}</span>
            <input 
            type="text" 
            :placeholder="lang.placeholder.corpus" 
            v-model="form.deliveryData.corpus" 
            @change="saveFieldCorpus"
            :class="{'is-error': errorFields.includes('corpus')}"
            @focus="removeError('corpus')"
            >
        </label>
        <label class="input cart__form-item cart__form-item--min">
            <span class="input__title">{{ lang.title.entrance }}</span>
            <input 
            type="number" 
            :placeholder="lang.placeholder.entrance" 
            v-model="form.deliveryData.entrance" 
            @change="saveFieldEntrance"
            :class="{'is-error': errorFields.includes('entrance')}"
            @focus="removeError('entrance')"
            >
        </label>
        <label class="input cart__form-item cart__form-item--min">
            <span class="input__title">{{ lang.title.office }}</span>
            <input 
            type="number" 
            :placeholder="lang.placeholder.office" 
            v-model="form.deliveryData.office" 
            @change="saveFieldOffice"
            :class="{'is-error': errorFields.includes('office')}"
            @focus="removeError('office')"
            >
        </label>
    </template>
    <Selector
     v-if="form.delivery == 'self'"
    :options="markets"
    mainClass="cart__form-item cart__form-item--full"
    :placeholder="lang.placeholder.market"
    :value="form.deliveryData.marketId"
    @onChange="setFormMarket"
    :title="lang.title.market"
    :isError="errorFields.includes('marketId')"
    ></Selector>
    <label class="input-file cart__form-item cart__form-item--auto" v-if="useFile">
        <input type="file" name="files" @change="checkFile">
        <span
            class="input-file__button button button--transparent button--bordered button--min button--medium">
            <i class="icon icon--file button-hover-reverse"></i>
            {{ lang.placeholder.file }} <template v-if="form.files.length > 0">({{form.files.length}})</template>
        </span>
        <span class="input-file__text">{{ lang.placeholder.fileMax }} {{ maxFileSize }}Mb</span>
    </label>
    <div class="note note--error input cart__form-item cart__form-item--full" v-if="fileError.length">
        <div class="text-content">
        <b>{{ lang.fileError }}</b>
        <ul>
            <li v-for="fileName in fileError" :key="fileName">{{ fileName }}</li>
        </ul>
        </div>
    </div>
    <div class="input cart__form-item cart__form-item--full">
        <span class="input__title">{{ lang.title.comment }}</span>
        <textarea :placeholder="lang.placeholder.comment" v-model="form.comment" @change="saveFieldComment"></textarea>
    </div>
    <label class="checkbox form-confirm cart__form-item cart__form-item--full">
        <input type="checkbox" v-model="agree">
        <span class="checkbox__fake"></span>
        <span class="checkbox__content" v-html="lang.agreeText"></span>
    </label>
    <button 
    class="button button--long cart__form-item cart__form-item--auto" 
    :class="{'is-loading': loading}" 
    :disabled="!agree || loading" 
    type="submit"
    >
        <span class="loader" v-if="loading"></span>
        {{ lang.submit }}
    </button>
</form>
<div class="cart__form" v-else>
    <div class="cart__form-item">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item">
        <div class="content-loader content-loader--input-h"></div> 
    </div>
    <div class="cart__form-item cart__form-item--full">
        <div class="form-label">
            <div class="content-loader content-loader--14h content-loader--20"></div>
        </div>
        <div class="form-fieldset form-fieldset--horizontal">
            <div class="content-loader content-loader"></div>
            <div class="content-loader content-loader"></div>
        </div>
    </div>
    <div class="cart__form-item">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item cart__form-item--min">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item cart__form-item--min">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item cart__form-item--min">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item cart__form-item--min">
        <div class="content-loader content-loader--input-h"></div>
    </div>
    <div class="cart__form-item cart__form-item--full">
        <div class="content-loader  content-loader--textarea-h"></div>
    </div>
    <div class="cart__form-item cart__form-item--full">
        <div class="content-loader"></div>
        <div class="content-loader"></div>
        <div class="content-loader"></div>
    </div>
    <div class="cart__form-item cart__form-item--full">
        <div class="content-loader content-loader--20h content-loader--40"></div>
    </div>
</div>
`;