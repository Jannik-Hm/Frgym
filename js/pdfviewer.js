    let currentPageIndex = 0;
    let pageMode = 1;
    let cursorIndex = Math.floor(currentPageIndex / pageMode);
    let pdfInstance = null;
    let totalPagesCount = 0;
    textcontentarray = [];

    const viewport = document.querySelector("#viewport");
    window.initPDFViewer = function (pdfURL) {
        pdfjsLib.getDocument(pdfURL).then(pdf => {
            pdfInstance = pdf;
            totalPagesCount = pdf.numPages;
            pageMode = pdf.numPages;
            standardzoom = getzoom();
            render();
        });
    };

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
        const canvas = container.children[0];
        const textLayer = container.children[1];
        const context = canvas.getContext("2d");
        canvas.height = pdfViewport.height;
        canvas.width = pdfViewport.width;

        page.render({
            canvasContext: context,
            viewport: pdfViewport
        }).then(() => {
            return page.getTextContent();
        }).then((textContent) => {
            textcontentarray[page.pageIndex] = {"height": canvas.offsetHeight + 'px', "width": canvas.offsetWidth + 'px', "textContent": textContent, "container": textLayer, "viewport": textViewport};
            textLayer.style.left = canvas.offsetLeft + 'px';
            textLayer.style.top = canvas.offsetTop + 'px';
            textLayer.style.height = canvas.offsetHeight + 'px';
            textLayer.style.width = canvas.offsetWidth + 'px';
            pdfjsLib.renderTextLayer({
                textContent: textContent,
                container: textLayer,
                viewport: textViewport,
                textDivs: []
            });
        });
    }

    function renderText(page, zoom = 1){
        const textLayer = viewport.children[page].children[1];
        textLayer.style.left = document.getElementById("viewport").children[page].offsetLeft + 'px';
        textLayer.style.top = document.getElementById("viewport").children[page].offsetTop + 'px';
        textLayer.style.height = textcontentarray[page].height;
        textLayer.style.width = textcontentarray[page].width;
        textLayer.style.scale = zoom;
    }
