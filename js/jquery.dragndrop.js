/**
 * Simple Drag & Drop
 * @Version: 1.0.1
 * @Author: Patrick Springstubbe
 * @Contact: @JediNobleclem
 * @Website: springstubbe.us
 * @Source: https://github.com/nobleclem/jQuery-DragNDrop
 *
 * Usage:
 *     $('ul').dragndrop();
 *     $('ul').dragndrop('unload');
 *     $('ul').dragndrop('reload');
 *     $('ul').dragndrop({
 *         onDrop: function( element, droppedElement ){}
 *     });
 *
 **/
(function($){
    var defaults = {
        loadStyles: true, // load default Drag & Drop Styles

        // Callbacks
        onDrop: function( element, droppedElement ) {}
    };

    var stylesLoaded = false;
    var stylesDefaults = $('<style type="text/css">.jqdndLoaded > * { cursor: move; user-select: none; } .jqdndLoaded .jqdndDragging { opacity: 0.5; }</style>');

    /* Add Plugin to jQuery */
    $.fn.dragndrop = function( options ) {
        if( !this.length ) {
            return;
        }

        var args = arguments;
        var ret;

        // initialize drag and drop on element(s)
        if( (options === undefined) || (typeof options === 'object' ) ) {
            return this.each(function(){
                if( !$.data( this, 'plugin_dragndrop' ) ) {
                    $.data( this, 'plugin_dragndrop', new DragNDrop( this, options ) );
                }
            });
        // execute built in method (unload, reload, etc) on element(s)
        } else if( (typeof options === 'string') && (options[0] !== '_') && (options !== 'init') ) {
            this.each(function(){
                var instance = $.data( this, 'plugin_dragndrop' );

                if( (instance instanceof DragNDrop) && (typeof instance[ options ] === 'function') ) {
                    ret = instance[ options ].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }
            });

            return ret;
        }
    };


    /* Setup Functionality */
    function DragNDrop( element, options )
    {
        this.element   = element;
        this.options   = $.extend( true, {}, defaults, options );
        this.dragElem  = null;

        if( !stylesLoaded ) {
            stylesLoaded = true;

            if( $('head > link:eq(0)').length ) {
                stylesDefaults.insertBefore( $('head > link:eq(0)') );
            }
            else if( $('head > style:eq(0)').length ) {
                stylesDefaults.insertBefore( $('head > style:eq(0)') );
            }
            else {
                $('head').append( stylesDefaults );
            }
        }

        this.load();
    }

    DragNDrop.prototype = {
        load: function() {
            var instance = this;
            var items    = $(instance.element).find('> *');

            // make sure this has not already been loaded
            if( $(instance.element).data( 'plugin_dragndrop' ) ) {
                return true;
            }

            $(instance.element).addClass('jqdndLoaded');

            items.attr('draggable', true ).each(function(){
                $(this)
                    .on( 'dragstart', function( event ){
                        instance.dragElem = this;

                        event.originalEvent.dataTransfer.effectAllowed = 'move';
                        event.originalEvent.dataTransfer.setData('text/plain', '');
                        $(this).addClass('jqdndDragging');
                    })
                    .on( 'dragenter', function( event ){})
                    .on( 'dragover', function( event ){
                        event.preventDefault();
                        event.originalEvent.dataTransfer.dropEffect = 'move';

                        if( this == instance.dragElem ) {
                            return;
                        }
                        else if( $(this).prevAll().filter( instance.dragElem ).length ) {
                            $(instance.dragElem).insertAfter( $(this) );
                        }
                        else {
                            $(instance.dragElem).insertBefore( $(this) );
                        }

                        return false;
                    })
                    .on( 'dragleave', function( event ){})
                    .on( 'drop', function( event ){
                        event.preventDefault();

                        if( typeof instance.options.onDrop == 'function' ) {
                            instance.options.onDrop( instance.element, instance.dragElem );
                        }
                    })
                    .on( 'dragend', function( event ){
                        event.preventDefault();
                        $(this).removeClass('jqdndDragging');

                        instance.dragElem = null;
                    })
            });
        },

        unload: function(){
            var instance = this;
            var items    = $(instance.element).find('> *');

            $(instance.element).removeClass('jqdndLoaded');

            items.removeAttr('draggable').each(function(){
                $(this)
                    .off( 'dragstart' )
                    .off( 'dragenter' )
                    .off( 'dragover' )
                    .off( 'dragleave' )
                    .off( 'drop' )
                    .off( 'dragend' );
            });

            $(instance.element).data( 'plugin_dragndrop', null );
        },

        reload: function() {
            this.unload();
            this.load();
        },

        reset: function(){}
    };
}(jQuery));
