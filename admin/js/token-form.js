const { __ } = wp.i18n;

function KeywordsComponent( props ) {

    var FormTokenField = wp.components.FormTokenField;

    return wp.element.createElement( FormTokenField , {
        label: props.label,
        value: props.tokens,
        maxLength: 10,
        onChange: function (content) {
            this.value = content;
            wp.data.dispatch('core/editor').editPost({ meta: {_xbs_meta_keywords_field: content } });
        }
    });
}

function DescriptionComponent ( props ) {

    var TextareaControl = wp.components.TextareaControl;

    return wp.element.createElement ( TextareaControl, {
        label: props.label,
        value: props.description.replace('"', '\''),
        help: props.help,
        onChange: function (content) {
            this.value = content;
            wp.element.render(
                wp.element.createElement( DescriptionComponent, { 
                    label: this.label, 
                    help: props.help,
                    description: content
                }),
                document.getElementById('xbs-description-component')
            );
            wp.data.dispatch('core/editor').editPost({ meta: {_xbs_meta_description_field: content.replace(/,/g, '\'') } });
        }
    });
}

wp.domReady (function() {
    
    wp.element.render(
        wp.element.createElement( KeywordsComponent, {
            label: __('Palabras clave','xbs-lang'),
            tokens: wp.data.select('core/editor').getCurrentPost().meta['_xbs_meta_keywords_field']
        }),
        document.getElementById('xbs-keywords-component')
    );

    wp.element.render(
        wp.element.createElement( DescriptionComponent, { 
            label: __('Meta descripción','xbs-lang'), 
            help: __('Introduce aquí la descripción SEO','xbs-lang'),
            description: wp.data.select('core/editor').getCurrentPost().meta['_xbs_meta_description_field']
        }),
        document.getElementById('xbs-description-component')
    );

});