/**
 * Created by MackRais on 06.11.15.
 * @author Oleh Boiko
 * @site http://mackrais.zz.mu
 */

/**
 * Initialization widget
 * @param default_options
 */
function mr_section_init(default_options) {
    default_options.x = default_options.x ? default_options.x : 0;
    default_options.y = default_options.y ? default_options.y : 0;
    default_options.width = default_options.width ? default_options.width : 400;
    default_options.height = default_options.height ? default_options.height : 300;
    default_options.scale = default_options.scale ? default_options.scale : 1;
    default_options.angle = default_options.angle ? default_options.angle : 0;

    var font_size = 15 * (default_options.width / default_options.height) + 'px';
    var font_size_icon = 40 * (default_options.width / default_options.height) + 'px';
    var $removeBtn = $('<span class="glyphicon  glyphicon-remove mr-remove " ></span>');

    $(default_options.section).find('h2').css("font-size", font_size);
    $(default_options.section).find('.fa-photo').css("font-size", font_size_icon);


    /**
     * Upload image by upload button
     */
    $('.mr-upload-btn-section').click(function (e) {
        e.preventDefault();
        $(this).parent().siblings('input:file').click(); //always one
    });

    /**
     * Upload image by click section
     */
    $(default_options.section).click(function () {
        if ($(this).find('img').length == 0)
            $(this).siblings('input:file').click();
    });

        $(document)
            .off('click.mrCropRemoveImg')
            .on('click.mrCropRemoveImg',default_options.section+' .mr-remove',function () {
            var $block;
            var $templateClear;
            if($(this).parents('.mr-section-base:eq(0)').length){
                $block = $(this).parents('.mr-section-base:eq(0)');
            }
            if($block && $block.find('.mr-tmp-clear-block').length){
                $templateClear = $block.find('.mr-tmp-clear-block').clone();
                $templateClear.removeClass('hidden');
            }
            if($(default_options.section).length){
                $(default_options.section).empty().append($templateClear);
                if($templateClear.length){
                    $templateClear.find('h2').css("font-size", font_size);
                    $templateClear.find('.fa-photo').css("font-size", font_size_icon);
                }
                $(default_options.id_input_file).replaceWith( $(default_options.id_input_file).clone( true ) );
                if($block.find('.mr-remove-input').length){
                    $block.find('.mr-remove-input').val(1);
                }
            }
        });



    /**
     * Dynamic preview image and Initialization widget croping
     */
    $(default_options.id_input_file).change(function () {
        $(this).siblings('.mr-data-inputs').find('.mr-x').val(default_options.x);
        $(this).siblings('.mr-data-inputs').find('.mr-y').val(default_options.y);
        $(this).siblings('.mr-data-inputs').find('.mr-w').val(default_options.width);
        $(this).siblings('.mr-data-inputs').find('.mr-h').val(default_options.height);
        $(this).siblings('.mr-data-inputs').find('.mr-scale').val(default_options.scale);
        $(this).siblings('.mr-data-inputs').find('.mr-angle').val(default_options.angle);

        var $section = $(this).siblings('[data-role="upload_image"]');
         readURL(this, $section);

    });


    /**
     * Init cropping block
     * @param options
     */
    $.fn.init_crop = function (options) {

        var _this = $(this);
        // Make sure the image is completely loaded before calling the plugin
        $(this).one('load', function () {
            // Initialize plugin (with custom event)
            $(this).guillotine({
                width: options.width,
                height: options.height,
                eventOnChange: 'guillotinechange'
            });

            // Display inital data
            var data = $(this).guillotine('getData');
            for (var key in data) {
                $('#' + key).html(data[key]);
            }

            // Bind button actions
            options.nav_block.find('.mr-rotate-left').click(function () {
                _this.guillotine('rotateLeft');
            });
            options.nav_block.find('.mr-rotate-right').click(function () {
                _this.guillotine('rotateRight');
            });
            options.nav_block.find('.mr-fit').click(function () {
                _this.guillotine('fit');
            });
            options.nav_block.find('.mr-zoom-in').click(function () {
                _this.guillotine('zoomIn');
            });
            options.nav_block.find('.mr-zoom-out').click(function () {
                _this.guillotine('zoomOut');
            });

            // Update data on change
            $(this).on('guillotinechange', function (ev, data, action) {
                data.scale = parseFloat(data.scale.toFixed(4));

                options.data_block.find('.mr-origin-height').val($(this).height());
                options.data_block.find('.mr-origin-width').val($(this).width());
                for (var k in data) {
                    options.data_block.find('.mr-' + k).val(data[k]);
                }
            });

            // Centered image after load
            var datas = $(this).guillotine('getData');
            $(this).trigger('guillotinechange',datas);
            $(this).guillotine('fit');
        });

        // Make sure the 'load' event is triggered at least once (for cached images)
        if ($(this).prop('complete')) $(this).trigger('load')
    };

    /**
     * Dynamic generate preview image
     * @param input
     * @param $section
     * @returns {boolean}
     */
    function readURL(input, $section) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var $img = $('<img />');

            var $remove = $('<span>✖</span>');
            var type = input.files[0].type.toString();
            var type_sigment = type.split("/");

            if (type_sigment[0] !== 'image') {
                $(input).val('');
                return false;
            }

            reader.onload = function (e) {
                var blob = b64toBlob(e.target.result,type);
                var blobUrl = URL.createObjectURL(blob);
                $remove.addClass('mr-preview-remove-img-section');
                $img.addClass('mr-preview-img-section').attr('src', blobUrl);
                $section.empty();
                $section.append($img);

                if($section.length){
                    $section.append($removeBtn);
                }
                if($section.parents('.mr-section-base:eq(0)').length){
                    if($section.parents('.mr-section-base:eq(0)').find('.mr-remove-input').length){
                        $section.parents('.mr-section-base:eq(0)').find('.mr-remove-input').val(0);
                    }
                }
                // Initialization crop widget
                $img.init_crop({
                    nav_block: $section.siblings('.mr-control-panel'),
                    width: default_options.width,
                    height: default_options.height,
                    data_block: $section.siblings('.mr-data-inputs')
                });

                $section.siblings('.mr-data-inputs').find('.mr-origin-height').val($img.height());
                $section.siblings('.mr-data-inputs').find('.mr-origin-width').val($img.width());
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     *
     * @param b64Data
     * @param contentType
     * @returns {*}
     */
    function getB64Data(b64Data,contentType) {
        b64Data = b64Data || '';
        b64Data =  b64Data.replace("data:", "");
        b64Data =  b64Data.replace(";base64,", "");
        b64Data =  b64Data.replace(contentType, "");
        return b64Data;
    }

    /**
     *
     * @param b64Data
     * @param contentType
     * @param sliceSize
     * @returns {*}
     */
    function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;
        b64Data = getB64Data(b64Data,contentType);
        var byteCharacters = atob(b64Data);
        var byteArrays = [];
        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);
            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }
            var byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }
        return new Blob(byteArrays, {type: contentType});
    }
}
