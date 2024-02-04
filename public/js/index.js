document.addEventListener("readystatechange", (event) => {
    if (event.currentTarget.readyState !== "complete") return;

    document.addEventListener("htmx:beforeSwap", (event) => {
        switch (event.detail.xhr.status) {
            case 419:
            case 422:
                event.detail.shouldSwap = true;
                event.detail.isError = false;
        }
    });
});
