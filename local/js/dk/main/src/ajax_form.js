import {Methods} from "../methods/src/index";

export function init() {
    Methods.on("submit", ".ajax_form", function (event) {
        event.preventDefault();
        toggleLoadingSubmit(this, true);
        const formElement = this;
        const totalErrorElement = formElement.querySelector(".total-error");
        if (totalErrorElement) {
            Methods.slideToggle(totalErrorElement, "up");
        }
        grecaptcha.ready(function () {
            grecaptcha.execute(reCAPTCHASiteKey, {action: 'submit'}).then(function (token) {
                const input = formElement.querySelector(`input[name="g-token"]`);
                input.value = token;

                BX.ajax.runComponentAction("dk:ajax_form", "submit", {
                    mode: "class",
                    data: new FormData(formElement)
                }).then(
                    response => {
                        if (response.data.success) {
                            BX.onCustomEvent(window, 'onFormSubmitSuccess');
                            formElement.reset();
                        }
                        const form = formElement.classList.contains("form") ? formElement : formElement.closest(".form");
                        if (response.data.message) {
                            const formSuccessElement = Methods.createStructure({inner: response.data.message}).firstElementChild;
                            form.append(formSuccessElement);
                            form.classList.add("is-success");
                            Methods.fadeToggle(formSuccessElement, "show");
                            if (!response.data.success) {
                                setTimeout(() => {
                                    Methods.fadeToggle(formSuccessElement, "hide");
                                    form.classList.remove("is-success");
                                    setTimeout(() => {
                                        formSuccessElement.remove();
                                    }, 500);
                                }, 5000);
                            }
                        } else if (response.data.field) {
                            const errorElement = formElement.querySelector(`input[name=${response.data.field}]`);
                            if (errorElement) {
                                errorElement.classList.add("is-error");
                                errorElement.addEventListener("focus", function () {
                                    this.classList.remove("is-error");
                                }, {
                                    once: true
                                });
                            }
                        }
                        toggleLoadingSubmit(formElement, false);
                    },
                    response => {
                        BX.onCustomEvent(window, 'onFormSubmitError');
                        const totalErrorElement = formElement.querySelector(".total-error");
                        if (totalErrorElement) {
                            totalErrorElement.querySelector(".total-error-content").textContent = response.errors[0].message;
                            Methods.slideToggle(totalErrorElement, "down");
                        }
                        toggleLoadingSubmit(formElement, false);
                    }
                );
            });
        });
    });
}

function toggleLoadingSubmit(form, loading) {

    const loader = Methods.createStructure({
        type: "span",
        classes: "loader"
    });

    form.querySelectorAll(`[type="submit"]`).forEach(submitter => {
        if (!loading) {
            submitter.classList.remove("is-loading");
            submitter.disabled = false;
            submitter.querySelector(".loader").remove();
        } else {
            submitter.append(loader);
            submitter.classList.add("is-loading");
            submitter.disabled = true;
        }
    });
}