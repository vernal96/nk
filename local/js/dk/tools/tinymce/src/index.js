BX.addCustomEvent('OnEditorInitedAfter', function (BXEditor) {
    if (BXEditor.id === "filesrc_pub") return;
    BX.loadScript(`https://cdn.tiny.cloud/1/${tinyMceAPI}/tinymce/7/tinymce.min.js`, () => {
        if (typeof (tinymce) === 'undefined') return;
        let editor;
        BXEditor.Destroy();
        setTimeout(() => {
            BXEditor.dom.pValueInput.style.display = '';
            editor = tinymce.init({
                language: 'ru',
                target: BXEditor.dom.pValueInput,
                plugins: ['anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount', 'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'mentions', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            });
            const selectorName = BXEditor.id.startsWith('PROP') ? BXEditor.id.replace(/TEXT/g, 'TYPE') : BXEditor.id + '_TYPE';
            document.querySelectorAll('input[name=\'' + selectorName + '\']').forEach(function (element) {
                element.addEventListener('change', function (event) {
                    editor.then(response => {
                        if (!event.target.id.includes('_editor')) {
                            BXEditor.dom.pValueInput.value = response[0].getContent();
                            response[0].hide();
                        } else {
                            response[0].show();
                        }
                    })
                });
            });
        });
    });
});