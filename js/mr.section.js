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
            var data = $(this).guillotine('getData');
            $(this).trigger('guillotinechange',data);
            $(this).guillotine('fit');
        });

        // Make sure the 'load' event is triggered at least once (for cached images)
        if ($(this).prop('complete')) $(this).trigger('load')
    }

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
                $remove.addClass('mr-preview-remove-img-section');
                $img.addClass('mr-preview-img-section').attr('src', e.target.result);
                $section.empty();
                $section.append($img);

                // Initialization crop widget
                $img.init_crop({
                    nav_block: $section.siblings('.mr-control-panel'),
                    width: default_options.width,
                    height: default_options.height,
                    data_block: $section.siblings('.mr-data-inputs')
                });

                $section.siblings('.mr-data-inputs').find('.mr-origin-height').val($img.height());
                $section.siblings('.mr-data-inputs').find('.mr-origin-width').val($img.width());
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}
