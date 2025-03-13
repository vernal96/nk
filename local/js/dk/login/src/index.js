import {component as Login} from "./root/component";

const componentName = "dk:login";
BX.ready(() => BX.Vue3.createApp(Login).mount("#authorize"));

export function parseResponseParams(component, data) {
    if (typeof data === "object"  && data !== null) {
        Object.entries(data).forEach(([key, value]) => {
            if (key in component) {
                component[key] = value;
            }
        });
    }
}

export function submit(event, component, callbackBefore = (_) => {}, callbackAfter = (_) => {}, getParams = {}) {
    event.preventDefault();
    if (component.loading) return;
    component.loading = true;
    BX.ajax.runComponentAction(componentName, component.action, {
        mode: "class",
        data: new FormData(event.target),
        getParameters: getParams
    }).then(response => {
        callbackBefore(response);
        parseResponseParams(component, response.data);
        component.$emit("onUpdateContent");
        component.loading = false;
        callbackAfter(response);
    });
}

export function getComponent(component, params = {}) {
    if (component.loading) return;
    component.loading = true;
    BX.ajax.runComponentAction(componentName, component.action, {
        mode: "class",
        getParameters: params,
        method: "GET"
    }).then(response => {
        parseResponseParams(component, response.data);
        component.$emit("onUpdateContent");
        component.loading = false;
    });
}