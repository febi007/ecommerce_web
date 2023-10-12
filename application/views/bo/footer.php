<!-- Material Design -->
<script src="<?=base_url().'assets/'?>dist/js/material.min.js"></script>
<script src="<?=base_url().'assets/'?>dist/js/ripples.min.js"></script>
<script>
    $.material.init();
</script>
<!-- FastClick -->
<script src="<?=base_url().'assets/'?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url().'assets/'?>dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="<?=base_url().'assets/'?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?=base_url().'assets/'?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url().'assets/'?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?=base_url().'assets/'?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=base_url().'assets/'?>dist/js/demo.js"></script>
<!-- PACE -->
<script src="<?=base_url().'assets/'?>plugins/pace/pace.min.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?=base_url().'assets/'?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?=base_url().'assets/'?>plugins/sweetalert2/sweetalert2.all.js"></script>
<!-- Clock Picker -->
<script src="<?=base_url().'assets/'?>plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="<?=base_url().'assets/'?>plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('.table-responsive').on('show.bs.dropdown', function () {
            document.querySelector('style').textContent += "@media only screen and (max-width: 500px) {.dropdown-position {position: relative}} @media only screen and (min-width: 500px) {.table-responsive {overflow: inherit}}";
        }).on('hide.bs.dropdown', function () {
            document.querySelector('style').textContent += "@media only screen and (min-width: 500px) {.table-responsive {overflow: auto}}";
        })
    }).ajaxStart(function() {
        Pace.restart();
    });

    /*Numeric format*/
    $(".numeric").numeric();
    $(".positive-numeric").numeric({ negative: false }, function() { alert("No negative values"); this.value = ""; this.focus(); });
    $(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });
    $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
    $(".decimal-2-places").numeric({ decimalPlaces: 2 });
    $('.currency').autoNumeric('init', {mDec: '0', lZero: 'deny'});

    /*Clockpicker*/
    $(".clockpicker").clockpicker();

    $(".colorpicker").colorpicker({
        format: 'hex',
        sliders: {
            saturation: {
                maxLeft: 200,
                maxTop: 200,
                callLeft: 'setSaturation',
                callTop: 'setBrightness'
            },
            hue: {
                maxLeft: 0,
                maxTop: 200,
                callLeft: false,
                callTop: 'setHue'
            },
            alpha: {
                maxLeft: 0,
                maxTop: 200,
                callLeft: false,
                callTop: 'setAlpha'
            }
        }
    });

    function set_date(periode, type) {
        var date = periode.split(" - ");
        if (type == 'datetimerange') {
            $('.'+type).daterangepicker(
                {
                    timePicker: true,
                    timePickerIncrement: 5,
                    locale: {
                        format: 'YYYY-MM-DD h:mm A'
                    },
                    startDate: moment(date[0]).format('YYYY-MM-DD h:mm A'),
                    endDate: moment(date[1]).format('YYYY-MM-DD h:mm A')
                }
            );
        } else if (type == 'daterangesingle') {
            $('.'+type).daterangepicker(
                {
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    singleDatePicker: true,
                    startDate: moment(date[0]).format('YYYY-MM-DD')
                }
            );
        } else if (type == 'daterange') {
            $('.'+type).daterangepicker(
                {
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    startDate: moment(date[0]).format('YYYY-MM-DD'),
                    endDate: moment(date[1]).format('YYYY-MM-DD')
                }
            );
        } else if (type == 'daterange2') {
            $('.'+type).daterangepicker(
                {
                    ranges: {
                        'Hari Ini': [moment(), moment()],
                        'KemarIn': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                        '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                        'Minggu Ini': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
                        'Minggu Lalu': [moment().subtract(1, 'weeks').startOf('isoWeek'), moment().subtract(1, 'weeks').endOf('isoWeek')],
                        'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                        'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                        'Tahun Lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                    },
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    startDate: moment(date[0]).format('YYYY-MM-DD'),
                    endDate: moment(date[1]).format('YYYY-MM-DD')
                }
            );
        }
    }

    /*Daterange picker*/
    $('.datetimerange').daterangepicker(
        {
            timePicker: true,
            timePickerIncrement: 5,
            locale: {
                format: 'YYYY-MM-DD h:mm A'
            },
            startDate: moment(),
            endDate: moment().add(5,'days')
        }
    );
    $('.daterangesingle').daterangepicker(
        {
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true,
            startDate: moment()

        }
    );
    $('.daterange').daterangepicker(
        {
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: moment(),
            endDate: moment()
        }
    );
    $('.daterange2').daterangepicker(
        {
            ranges: {
                'Hari Ini': [moment(), moment()],
                'KemarIn': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                'Minggu Ini': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
                'Minggu Lalu': [moment().subtract(1, 'weeks').startOf('isoWeek'), moment().subtract(1, 'weeks').endOf('isoWeek')],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                'Tahun Lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            },
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: moment(),
            endDate: moment()
        }
    );

    $(".input-diskon").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: shift+=
            (e.keyCode == 187 && (e.shiftKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    function to_rp(angka, param=null){
        if(angka != '' || angka != 0){
            var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2    = '';
            for(var i = 0; i < rev.length; i++){
                rev2  += rev[i];
                if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                    rev2 += ',';
                }
            }

            var dec		= parseFloat(angka, 10).toString().split('.');
            if(dec[1] > 0){ dec = dec[1]; } else { dec = '00'; }

            //return 'IDR : ' + rev2.split('').reverse().join('') + ',-';
            if (param == null) {
                return rev2.split('').reverse().join('') + '.' + dec;
            } else {
                return rev2.split('').reverse().join('');
            }
        } else {
            //return 'IDR : ';
            return '0';
        }
    }

    function hapuskoma(str) {
        str = str.toString();
        while (str.search(",") >= 0) {
            str = (str + "").replace(',', '');
        }
        return str;
    }

    function hide_notif(param) {
        $("#ntf_"+param).text('');
    }

    function cek_data(table, column, id, callback) {
        $.ajax({
            url: "<?=base_url().'site/cek_data_2/'?>"+table+'/'+column+'/'+id,
            type: "GET",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                callback(table, column, id, res);
            }
        });
    }

    function to_svg() {
        jQuery('img.svg').each(function(){
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function(data) {
                // Get the SVG tag, ignore the rest
                var $svg = jQuery(data).find('svg');

                // Add replaced image's ID to the new SVG
                if(typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if(typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass+' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr('xmlns:a');

                // Replace image with new SVG
                $img.replaceWith($svg);

            }, 'xml');

        });
    }

    function replace_slash(str) {
        str = str.toString();
        while (str.search("/") >= 0) {
            str = (str + "").replace('/', '_');
        }
        return str;
    }

    function set_ckeditor(id){
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace(id, {
                filebrowserImageBrowseUrl : '<?=base_url('assets/kcfinder/browse.php');?>',
                height: '100%',
                width: '100%',
                toolbar: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Undo', 'Redo' ] },
                    '/',
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
                    { name: 'links', items: [ 'Link', 'Unlink' ] },
                    { name: 'insert', items: [ 'Image', 'Table' ] },
                    '/',
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                    { name: 'others', items: [ '-' ] },
                    { name: 'about', items: [ 'About' ] }
                ]
            });
            //bootstrap WYSIHTML5 - text editor
            //$(".textarea").wysihtml5();
        });
    }

    $.fn.modal.Constructor.prototype.enforceFocus = function() {
        modal_this = this
        $(document).on('focusin.modal', function (e) {
            if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                modal_this.$element.focus()
            }
        })
    };

    function sweetImage (src,title) {
        swal({
            showConfirmButton: false,
            showCloseButton: false,
            showCancelButton: false,
            title: title,
            imageUrl: src,
            width: '800px'
        })
    }

    $("input[type=text]").bind('paste', function(event) {
        var _this = this;
        setTimeout(function() {
            var data = $(_this);
            var text = data.val().toString();
            while (text.search("'") >= 0) {
                text = (text + "").replace("'", "`");
            }
            data.val(text);
        }, 100);
    });

    $("input[type=text]").keypress(function(event) {
        var _this = this;
        setTimeout(function() {
            var data = $(_this);
            var text = data.val().toString();
            while (text.search("'") >= 0) {
                text = (text + "").replace("'", "`");
            }
            data.val(text);
        }, 100);
    });
</script>
