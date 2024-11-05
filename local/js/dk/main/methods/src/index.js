export const Methods = {

    //Динамическое событие по элементу
    on(event, object, func = function () {
    }) {
        document.addEventListener(event, function (e) {
            const eTarget = e.target.closest(object);
            if (eTarget == null) return;
            func.call(eTarget, e);
        });
    },

// Работает с объектами input типа checkbox, содержащими data-form-confirm. Должен быть потомком элемента .form или form, который содержит submit элементы
    checkAgree() {
        const confirmElementDOM = 'input[data-form-confirm]',
            formDOM = 'form',
            parentFormElementDOM = '.form',
            queryElementDOM = '[type=submit]';
        for (let agree of document.querySelectorAll(confirmElementDOM)) changeAgree(agree);
        this.on('change', confirmElementDOM, function () {
            changeAgree(this);
        });

        function changeAgree(object) {
            const parent = object.closest(formDOM) ? object.closest(formDOM) : object.closest(parentFormElementDOM),
                submits = parent.querySelectorAll(queryElementDOM);
            if (!submits) return;
            for (let agree of parent.querySelectorAll(confirmElementDOM)) {
                for (let submit of submits) submit.disabled = false;
                if (!agree.checked) {
                    for (let submit of submits) submit.disabled = true;
                    break;
                }
            }
        }
    },

//Ссылка по якорям
    clickAnchors() {
        this.on('click', 'a[href^="#"]', function (e) {
            e.preventDefault();
            const element = document.getElementById(this.getAttribute('href').substr(1));
            if (!element) return;
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    },

//Обрабатывает контент модальных окон, которые передаются в параметре data-content.
    initModalPlaceholder(prefix = '.modal__') {
        this.on('click', '.modal-trigger[data-src*="#"]', function () {
            const dataContent = this.dataset.content,
                modalObject = document.getElementById(this.dataset.src.substr(1));
            if (!modalObject) return;
            let customContent = [];
            if (dataContent) {
                try {
                    customContent = JSON.parse(`{${dataContent}}`);
                } catch (error) {
                }
            }
            for (let defaultElement of modalObject.querySelectorAll('[data-default]')) {
                let defaultValue = defaultElement.dataset.default;
                if (defaultElement.nodeName.toLowerCase() === 'input') defaultElement.value = defaultValue;
                else defaultElement.innerHTML = defaultValue;
            }
            for (let customItem in customContent) {
                let divDOM = modalObject.querySelector(prefix + customItem),
                    inputDOM = modalObject.querySelector(`input[name="${customItem}"]`);
                if (divDOM) divDOM.innerHTML = customContent[customItem];
                if (inputDOM) inputDOM.value = customContent[customItem];
            }
        });
    },

//Перемещает элемент при изменении размера экрана data-move - размер экрана, data-break - обратно, data-to - куда перемещать
    moveElements() {
        const prefix = 'movedIn';
        for (let moveElement of document.querySelectorAll('[data-move]')) {
            let data = moveElement.dataset,
                windowWidth = window.innerWidth,
                dataSize = (data.move) ? +data.move : 576,
                dataBreak = (data.break) ? +data.break : false,
                toElement = (data.to) ? document.getElementById(data.to) : false,
                oldPosition = document.getElementById(prefix + data.to);
            if (!toElement) return;
            if (windowWidth < dataSize && !oldPosition && windowWidth >= dataBreak) {
                let newOldPosition = document.createElement('div');
                newOldPosition.id = prefix + data.to;
                newOldPosition.style.display = 'none';
                moveElement.before(newOldPosition);
                toElement.append(moveElement);
            } else if ((windowWidth >= dataSize || (dataBreak && dataBreak > windowWidth)) && oldPosition) {
                if (!oldPosition) return;
                oldPosition.after(moveElement);
                oldPosition.remove();
            }
        }
    },

    fixedHeader(headerId = 'header', fixedId = 'fixedHeader') {
        const header = document.getElementById(headerId);
        if (!header) return;
        const headerHeight = header.offsetHeight,
            offset = 50,
            scrollPosition = window.scrollY,
            fixedHeaderElement = document.getElementById(fixedId),
            fixedHeader = fixedHeaderElement ? fixedHeaderElement : header.cloneNode(true);
        fixedHeader.setAttribute('id', 'fixedHeader');
        fixedHeader.classList.add('fixed-header', 'mm-item');
        this.css(fixedHeader, {
            width: `calc(100vw - ${window.innerWidth - document.documentElement.clientWidth}px)`
        });
        if (!fixedHeaderElement) {
            document.body.prepend(fixedHeader);
            fixedHeader.querySelectorAll(".header-catalog, .header-catalog-overlay").forEach(element => {
                element.remove();
            });
        }
        if (scrollPosition > headerHeight + offset) {
            setTimeout(() => fixedHeader.classList.add("is-active"), 0);
        } else {
            if (!fixedHeader) return;
            fixedHeader.classList.remove("is-active");
        }
    },

    pageUp() {
        const pageUpBtn = document.getElementById('pageup'),
            topShow = 280,
            scrollPosition = window.scrollY,
            documentFooter = document.querySelector('footer'),
            footerHeight = documentFooter ? documentFooter.offsetHeight : 0;
        if (!pageUpBtn) return;
        if (scrollPosition > topShow && scrollPosition < document.body.offsetHeight - window.innerHeight - footerHeight) pageUpBtn.classList.add("is-active");
        else pageUpBtn.classList.remove("is-active");
    },

// Добавляет CSS стили для элемента в формате JSON
    css(element, css) {
        for (const style in css) {
            element.style[style] = css[style];
        }
    },

// Клик вне блока
    outsideClick(element, func) {
        element = (typeof (element) == 'string') ? document.querySelector(element) : element;
        document.addEventListener('click', (e) => {
            const withinBoundaries = e.composedPath().includes(element);
            if (!withinBoundaries) {
                func(element);
            }
        })
    },

// Разворачивает и сворачивает объект.
// Передается объект, метод (по умолчанию NULL. Значения: down|up), скорость (по умолчанию 300)
    slideToggle(element, method = "", speed = 300,) {
        if (['right', 'left', 'backLeft', 'backRight'].includes(method)) {
            this.css(element, {'transition': 'transform ' + speed + 'ms'});
            let parent = document.createElement('div');
            this.css(parent, {'display': 'block', 'overflow': 'hidden'});
            element.before(parent);
            parent.append(element);
            if (element.offsetWidth === 0 && !['backLeft', 'backRight'].includes(method)) {
                this.css(element, {
                    'display': 'block',
                    'transform': 'translateX(' + ((method === 'left') ? '-100' : '100') + '%)'
                });
                this.css(parent, {'width': element.offsetWidth + 'px'});
                setTimeout(() => element.style.transform = 'translateX(0)', 0);
                setTimeout(() => {
                    parent.before(element);
                    parent.remove();
                    element.removeAttribute('style');
                    element.style.display = 'block';
                }, speed);
            } else if (element.offsetWidth > 0 && /back/.test(method)) {
                element.style.transform = 'translateX(0)';
                this.css(parent, {'width': element.offsetWidth + 'px'});
                setTimeout(() => element.style.transform = 'translateX(' + ((method === 'backLeft') ? '-100' : '100') + '%)', 0);
                setTimeout(() => {
                    parent.before(element);
                    parent.remove();
                    element.removeAttribute('style');
                    element.style.display = 'none';
                }, speed);
            }
        } else {
            this.css(element, {'transition': 'height ' + speed + 'ms', 'overflow': 'hidden'});
            let height = element.clientHeight + 'px';
            if (element.offsetHeight === 0 && method !== 'up') {
                this.css(element, {'display': 'block', 'height': 'auto'});
                height = element.clientHeight + 'px';
                element.style.height = '0px';
                setTimeout(() => element.style.height = height, 0);
                setTimeout(() => {
                    element.removeAttribute('style');
                    element.style.display = 'block';
                }, speed);
            } else if (element.offsetHeight > 0 && method !== 'down') {
                height = element.clientHeight + 'px';
                element.style.height = height;
                setTimeout(() => element.style.height = '0px', 0);
                setTimeout(() => {
                    element.removeAttribute('style');
                    element.style.display = 'none';
                }, speed);
            }
        }
    },

// Показывает/скрывает объект.
// Передаются объект, скорость (по умолчанию 300), метод (по умолчанию NULL. Значения: 'show')
    fadeToggle(element, method = null, speed = 300) {
        if (element.offsetHeight === 0 && method !== 'hide') {
            this.css(element, {'transition': 'opacity ' + speed + 'ms', 'display': 'block', 'opacity': 0});
            setTimeout(() => element.style.opacity = 1, 0);
            setTimeout(() => {
                element.removeAttribute('style');
                element.style.display = 'block';
            }, speed);
        } else if (element.offsetHeight !== 0 && method !== 'show') {
            this.css(element, {'transition': 'opacity ' + speed + 'ms'});
            setTimeout(() => element.style.opacity = 0, 0);
            setTimeout(() => {
                element.removeAttribute('style');
                element.style.display = 'none';
            }, speed);
        }
    },

    setCustomSelect() {
        const mainClass = 'selector',
            selectedClass = 'is-selected',
            activeClass = "is-active",
            reverseClass = 'is-reverse',
            disabledClass = 'is-disabled',
            optionClass = 'option',
            separator = '__',
            modSeparator = '_',
            mobileClass = 'mobile',
            elements = ['label', 'title', 'btn', 'list'];
        for (let select of document.querySelectorAll(`select:not(.input-initialized):not(.input-ignore)`)) {
            let main = document.createElement('div');
            main.classList.add(mainClass);
            if (this.isMobile()) main.classList.add(`${mainClass}${modSeparator}${mobileClass}`);
            for (let element of elements) {
                window[element] = document.createElement('div');
                window[element].classList.add(`${mainClass}${separator}${element}`);
            }
            select.after(main);
            label.append(title, btn);
            main.append(select, label, list);
            label.addEventListener('click', function (e) {
                let parent = this.parentNode,
                    list = parent.querySelector(`.${mainClass}${separator}${elements[3]}`);
                if (window.innerHeight - list.getBoundingClientRect().top - parent.offsetHeight < list.offsetHeight) parent.classList.add(reverseClass);
                else parent.classList.remove(reverseClass);
                parent.classList.toggle(activeClass);
            });
            this.outsideClick(main, (element) => element.classList.remove(activeClass));
            setSelectList(select);
            select.addEventListener('change', () => setSelectList(select));
            select.classList.add("input-initialized");

            function setSelectList(select) {
                let parent = select.closest(`.${mainClass}`),
                    list = parent.querySelector(`.${mainClass}${separator}${elements[3]}`),
                    title = parent.querySelector(`.${mainClass}${separator}${elements[1]}`);
                list.innerHTML = '';
                for (let option of select.options) {
                    let optionDOM = document.createElement('div'),
                        optionDOMClassList = optionDOM.classList;
                    optionDOM.textContent = option.textContent;
                    optionDOM.dataset.value = option.value;
                    optionDOMClassList.add(`${mainClass}${separator}${optionClass}`);
                    if (option.selected) {
                        optionDOMClassList.add(selectedClass);
                        title.textContent = option.textContent;
                        if (option.disabled) title.classList.add(disabledClass);
                        else title.classList.remove(disabledClass);
                    }
                    if (option.disabled) optionDOMClassList.add(disabledClass);
                    list.append(optionDOM);
                    optionDOM.addEventListener('click', function (e) {
                        if (this.classList.contains(disabledClass)) return;
                        this.closest(`.${mainClass}`).classList.remove(activeClass);
                        select.value = this.dataset.value;
                        select.dispatchEvent(new Event('change'))
                    });
                }
            }
        }
    },

    getCookie(key = false) {
        let cookies = [];
        for (let cookie of document.cookie.split(';')) {
            cookie = cookie.split('=');
            cookies[decodeURIComponent(cookie[0]).trim()] = decodeURIComponent(cookie[1]);
        }
        return (!key) ? cookies : cookies[key];
    },

    setCookie(name, value, options = {}) {
        if (!options.path) options.path = '/';
        if (!options.samesite) options.samesite = 'lax';
        console.log(options);
        if (options.expires instanceof Date) {
            options.expires = options.expires.toUTCString();
        }
        let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
        for (let optionKey in options) {
            updatedCookie += "; " + optionKey;
            let optionValue = options[optionKey];
            if (optionValue !== true) {
                updatedCookie += "=" + optionValue;
            }
        }
        document.cookie = updatedCookie;
    },

    deleteCookie(name) {
        this.setCookie(name, "", {
            'max-age': -1
        });
    },

    isMobile() {
        return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent));
    },

    initViberLink() {
        if (!this.isMobile()) return;
        const viberLink = document.querySelector('a[href^="viber://"]');
        if (viberLink) {
            const url = new URL(viberLink.href),
                number = url.searchParams.get('number').replace(/\D/, '');
            if (number) viberLink.href = `viber://add?number=${number}`;
        }
    },

    createStructure(element) {
        const type = element.type ? element.type : "div",
            classes = element.classes,
            children = element.children,
            content = element.content,
            inner = element.inner,
            data = element.data,
            variable = element.variable,
            id = element.id,
            newElement = document.createElement(type);
        if (classes) {
            if (typeof classes == "string") newElement.setAttribute("class", classes.trim());
            else classes.forEach(cls => newElement.classList.add(cls));
        }
        if (id) {
            newElement.id = id;
        }
        if (content) newElement.textContent = content;
        if (inner) newElement.innerHTML = inner;
        if (data) for (const dataElement in data) newElement.dataset[dataElement] = data[dataElement];
        if (children) children.forEach(child => newElement.append(this.createStructure(child)));
        if (variable) window[variable] = newElement;
        return newElement;
    },

    toggleStFormParam() {
        const horizontalValue = "1",
            verticalValue = "0",
            stSelector = "input[name='st']",
            windowWidthBreakpoint = 576,
            toggleMarker = "changed";
        document.querySelectorAll(stSelector).forEach(element => {
            if (window.innerWidth < windowWidthBreakpoint) {
                if (element.value === horizontalValue) {
                    element.value = verticalValue;
                    element.classList.add(toggleMarker);
                }
            } else {
                if (element.classList.contains(toggleMarker)) {
                    element.value = horizontalValue;
                    element.classList.remove(toggleMarker);
                }
            }
        });
    },

    toggleTextSlider() {
        this.on("click", ".slider-text-header", event => {
            const content = event.target.nextElementSibling,
                parent = event.target.parentNode;
            if (content.offsetHeight === 0) {
                parent.classList.add("is-active");
                this.slideToggle(content, "down");
            } else {
                parent.classList.remove("is-active");
                this.slideToggle(content, "up");
            }
        });
    },

    getScrollBarWidth(element = document.body) {
        const div = document.createElement("div");
        div.style.overflowY = "scroll";
        div.style.width = "50px";
        div.style.height = "50px";
        element.append(div);
        const scrollWidth = div.offsetWidth - div.clientWidth;
        div.remove();
        return scrollWidth;
    },

    toggleBodyOverflow(hidden = true) {
        if (hidden) {
            this.css(document.body, {
                overflow: "hidden",
                height: "100%",
                paddingRight: window.innerWidth - document.documentElement.clientWidth + "px"
            });
        } else {
            document.body.removeAttribute("style");
        }

    },

    scrollToHeaderElement() {
        const elementStart = document.querySelector("[data-page-loader-start]");
        if (elementStart) {
            let moreOffset = 0;
            const fixedHeaderNode = document.getElementById("fixedHeader");
            if (fixedHeaderNode) {
                moreOffset += fixedHeaderNode.clientHeight;
            }
            const elementStartPosition = elementStart.getBoundingClientRect().top + window.pageYOffset - moreOffset;
            window.scrollTo({
                top: elementStartPosition,
                behavior: "smooth"
            });
        }
    },

    bxPreloader() {
        const containerSelector = "[data-page-loader-container]",
            loadingClass = "is-loading",
            loaderContainerClass = "loader-container",
            loaderSelector = "loader";
        const loaderElements = [];
        document.querySelectorAll(containerSelector).forEach(elementNode => {
            const parentNode = elementNode.parentNode;
            if (!parentNode.id.startsWith("comp_")) return;
            loaderElements.push(parentNode);
            if (!parentNode.classList.contains(loaderContainerClass))
                parentNode.classList.add(loaderContainerClass);
        });
        BX.showWait = (node, msg) => {
            loaderElements.forEach(elementNode => {
                elementNode.classList.add(loadingClass);
                elementNode.append(this.createStructure({
                    classes: "loaded loader--gray"
                }));
            });
        };

        BX.closeWait = (node, obMsg) => {
            if (!node) return;
            document.querySelectorAll(containerSelector).forEach(elementNode => {
                loaderElements.forEach(elementNode => {
                    elementNode.classList.remove(loadingClass);
                    elementNode.querySelectorAll(loaderSelector).forEach(loaderNode => loaderNode.remove());
                });
            });
            this.scrollToHeaderElement();
        }
    },

    setPhonesMask() {
        for (let phone of document.querySelectorAll(`input[type="tel"]:not(.input-initialized):not(.input-ignore)`)) {
            phone.addEventListener('keydown', PhoneEvents.onKeyDown);
            phone.addEventListener('paste', PhoneEvents.onPaste);
            phone.addEventListener('input', PhoneEvents.onInput);
            phone.classList.add("input-initialized");
        }
    }

}

export const PhoneEvents = {
    onKeyDown(e) {
        if (e.key === undefined) return;
        if (['Delete', 'Backspace', 'Enter'].includes(e.key) || e.key.match(/^arrow/i)) return;
        if (!e.altKey && !e.ctrlKey && !e.shiftKey && e.key.match(/\D/)) e.preventDefault();
    },
    onPaste(e) {
        this.dispatchEvent(new Event('input'));
    },
    onInput(e) {
        const input = e.target;
        let value = input.value.replace(/\D/ig, '').substr(0, 13);
        if (value[0] === '9') value = `7${value}`;
        let rusFormat = [7, 8, 9].includes(+value[0]),
            result = '',
            startPosition = ((e.inputType && e.inputType.match(/^deleteContent/)) || (input.selectionStart < input.value.length)) ? input.selectionStart : false;
        for (let idx in value) {
            idx = +idx;
            let num = value[idx];
            if (idx === 0) {
                if (num === '8') result += '8';
                else if (num === '9') result += '+7 (9';
                else result += `+${num}`;
            } else {
                if (rusFormat) {
                    if (idx === 1) result += ` (${num}`;
                    else if ([2, 3, 5, 6, 8, 10].includes(idx)) result += `${num}`;
                    else if ([7, 9].includes(idx)) result += `-${num}`;
                    else if (idx === 4) result += `) ${num}`;
                } else result += `${num}`;
            }
        }
        input.value = result;
        if (startPosition && value.length < 11 && rusFormat) {
            this.selectionStart = startPosition;
            this.selectionEnd = startPosition;
        }
    }
}