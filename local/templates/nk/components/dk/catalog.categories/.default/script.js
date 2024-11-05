DK.Methods.on("click", ".catalog-category", function (event) {
    if (event.target.tagName.toLowerCase() === "a") return;
    const parentElement = this.parentNode,
        listElement = parentElement.querySelector(".catalog-categories__children");
    if (!listElement) return;
    if (parentElement.classList.contains("closed")) {
        DK.Methods.slideToggle(listElement, "down");
        setTimeout(() => {
            parentElement.classList.remove("closed")
        }, 300);
    } else {
        DK.Methods.slideToggle(listElement, "up");
        setTimeout(() => {
            parentElement.classList.add("closed")
        }, 300);
    }
});