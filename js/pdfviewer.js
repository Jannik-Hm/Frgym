(function () {
    let currentPageIndex = 0;
    let pageMode = 1;
    let cursorIndex = Math.floor(currentPageIndex / pageMode);
    let pdfInstance = null;
    let totalPagesCount = 0;

    const viewport = document.querySelector("#viewport");
    window.initPDFViewer = function (pdfURL) {
        pdfjsLib.getDocument(pdfURL).then(pdf => {
            pdfInstance = pdf;
            totalPagesCount = pdf.numPages;
            pageMode = pdf.numPages;
            console.log(pdf);
            // initPager();
            // initPageMode();
            render();
        });
    };

    function onPagerButtonsClick(event) {
        const action = event.target.getAttribute("data-pager");
        if (action === "prev") {
            if (currentPageIndex === 0) {
                return;
            }
            currentPageIndex -= pageMode;
            if (currentPageIndex < 0) {
                currentPageIndex = 0;
            }
            render();
        }
        if (action === "next") {
            if (currentPageIndex === totalPagesCount - 1) {
                return;
            }
            currentPageIndex += pageMode;
            if (currentPageIndex > totalPagesCount - 1) {
                currentPageIndex = totalPagesCount - 1;
            }
            render();
        }
    }

    function onPageModeChange(event) {
        pageMode = Number(event.target.value);
        render();
    }

    function render() {
        cursorIndex = Math.floor(currentPageIndex / pageMode);
        const startPageIndex = cursorIndex * pageMode;
        const endPageIndex =
            startPageIndex + pageMode < totalPagesCount
                ? startPageIndex + pageMode - 1
                : totalPagesCount - 1;

        const renderPagesPromises = [];
        for (let i = startPageIndex; i <= endPageIndex; i++) {
            renderPagesPromises.push(pdfInstance.getPage(i + 1));
        }

        Promise.all(renderPagesPromises).then(pages => {
            const pagesHTML = `<div><canvas></canvas><div class="textLayer"></div></div>`.repeat(pages.length);
            viewport.innerHTML = pagesHTML;
            pages.forEach(renderPage);
        });
    }

    function renderPage(page) {
        const container =
            viewport.children[page.pageIndex - cursorIndex * pageMode];
        let pdfViewport = page.getViewport(container.offsetWidth / page.getViewport(1).width * 3);
        let textViewport = page.getViewport(container.offsetWidth / page.getViewport(1).width);
        scale = 1;
        // let pdfViewport = page.getViewport(3);
        // let textViewport = page.getViewport(3);
        const canvas = container.children[0];
        const textLayer = container.children[1];
        const context = canvas.getContext("2d");
        canvas.height = pdfViewport.height;
        canvas.width = pdfViewport.width;
        console.log("pdfviewport");
        console.log(pdfViewport);
        console.log("textviewport");
        console.log(textViewport);

        page.render({
            canvasContext: context,
            viewport: pdfViewport
        }).then(() => {
            return page.getTextContent();
        }).then((textContent) => {
            console.log(textContent);
            textLayer.style.left = canvas.offsetLeft + 'px';
            textLayer.style.top = canvas.offsetTop + 'px';
            textLayer.style.height = canvas.offsetHeight + 'px';
            textLayer.style.width = canvas.offsetWidth + 'px';
            console.log(textContent);
            pdfjsLib.renderTextLayer({
                textContent: textContent,
                container: textLayer,
                viewport: textViewport,
                textDivs: []
            });
        });

        // console.log(page);

        // Wait for rendering to finish
        // renderTask.promise.then(function () {
        //     pageRendering = false;
        //     var textContent = page.getTextContent();
        //     textContent.then(function (text) { // return content promise
        //         console.log(text.items);
        //         // Text Layer CSS

        //         console.log(page.getTextContent());


        //     });
        // console.log(page.getTextContent());
        // })//.then(function () {
        //     // Returns a promise, on resolving it will return text contents of the page
        //     console.log(page.getTextContent());
        // }).then(function (textContent) {
        // });

    }
})();
