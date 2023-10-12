<style>
    #map {
        height: 400px;
        width: 100%;
    }

    #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }

    #infowindow-content .title {
        font-weight: bold;
    }

    #infowindow-content {
        display: none;
    }

    #map #infowindow-content {
        display: inline;
    }

    .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
    }

    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }

    .pac-controls {
        display: inline-block;
        padding: 5px 11px;
    }

    .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 100%;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }
    #target {
        width: 345px;
    }
    .pac-container {
        background-color: #FFF;
        z-index: 1050;
        position: fixed;
        display: inline-block;
        float: left;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title"><?=$title?></h3>
                        </div>

                        <div class="col-md-4">
                            <div class="box-tools">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <input type="text" name="table_search" class="form-control pull-right" onkeyup="return cari(event, $(this).val())" value="<?=isset($this->session->search['any'])?$this->session->search['any']:''?>" placeholder="Cari">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary bg-blue" onclick="add()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive" id="result_table"></div>
                <div align="center" id="pagination_link"></div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_lokasi" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_lokasi" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Lokasi</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Lokasi">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'gambar'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>
                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>" name="<?=$label?>" accept="image/*">
                                <p class="help-block">Gambar akan ditampilkan sebagai menu pada website utama.</p>
                            </div>
                        </div>
                        <div class="form-group" id="img_preview">
                            <label class="col-sm-2 control-label">Gambar Sekarang</label>

                            <div class="col-sm-10">
                                <img class="img_preview" id="preview">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'tlp1'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Telepon</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control positive-integer" id="<?=$label?>" autocomplete="off" placeholder="Telepon">
                            </div>
                        </div>
                        <div class="form-group form-inline">
                            <label for="<?=$label?>" class="col-sm-2 control-label">Jam Operasional</label>
                            <div class="form-group col-sm-5" style="margin: 0">
                                <?php $label = 'jam_buka'; ?>
                                <label for="<?=$label?>" class="col-sm-4 control-label">Jam Buka</label>

                                <div class="col-sm-6">
                                    <input type="text" name="<?=$label?>" data-autoclose="true" class="form-control clockpicker" id="<?=$label?>" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-5" style="margin: 0">
                                <?php $label = 'jam_tutup'; ?>
                                <label for="<?=$label?>" class="col-sm-4 control-label">Jam Tutup</label>

                                <div class="col-sm-6">
                                    <input type="text" name="<?=$label?>" data-autoclose="true" class="form-control clockpicker" id="<?=$label?>" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Cari Lokasi</label>

                            <div class="col-sm-10">
                                <input id="pac-input" class="controls form-control" type="text" placeholder="Cari Lokasi / Tandai Di Peta">
                                <div id="map"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'alamat'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Alamat</label>

                            <div class="col-sm-10">
                                <textarea type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" rows="4" autocomplete="off" placeholder="Alamat"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="lng" id="lng">
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="param" id="param" value="add">
                <input type="hidden" name="id" id="id">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $("#pac-input").keypress(function (e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    $('#result_table').on('show.bs.dropdown', function () {
        document.querySelector('style').textContent += "@media only screen and (max-width: 500px) {.dropdown-position {position: relative}} @media only screen and (min-width: 500px) {.table-responsive {overflow: inherit !important}}";
    }).on('hide.bs.dropdown', function () {
        document.querySelector('style').textContent += "@media only screen and (min-width: 500px) {.table-responsive {overflow: auto}}";
    });

    function initMap(zoom_=14, lat_=-6.9228583, lng_=107.6058134, id_='map', param_='edit') {
        var uluru = {lat: lat_, lng: lng_};
        var map = new google.maps.Map(document.getElementById(id_), {
            zoom: zoom_,
            center: uluru
        });

        var geocoder = new google.maps.Geocoder;

        var marker = new google.maps.Marker({
            map: map
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        //map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function(marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    title: place.name,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                    $("#alamat").val(place.formatted_address);
                    $("#lat").val(place.geometry.location.lat());
                    $("#lng").val(place.geometry.location.lng());
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });

        if (param_ == 'set' || $("#param").val()=='edit') {
            marker.setPosition(uluru);
        }

        google.maps.event.addListener(map, 'click', function(e) {
            if (param_ == 'edit') {
                var latLng = e.latLng;
                marker.setPosition(latLng);
                $("#lat").val(latLng.lat());
                $("#lng").val(latLng.lng());
                markers.forEach(function(marker) {
                    marker.setMap(null);
                });
                geocoder.geocode({
                    'latLng': latLng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            $("#alamat").val(results[0].formatted_address);
                            $("#pac-input").val('');
                        }
                    }
                });
            }
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqD1Z03FoLnIGJTbpAgRvjcchrR-NiICk&libraries=places" async defer></script>

<script>
    $(document).ready(function(){
        load_data(1);
    }).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        load_data(page);
    });

    function load_data(page,data={})
    {
        $.ajax({
            url:"<?=base_url().$content.'/get_data/';?>"+page,
            type:"POST",
            data:data,
            dataType:"JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success:function(data)
            {
                $('#result_table').html(data.result_table);
                $('#pagination_link').html(data.pagination_link);
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function add() {
        $("#modal_title").text("Tambah Lokasi");
        $("#param").val("add");
        $("#modal_lokasi").modal("show");
        initMap();
        document.getElementById("img_preview").style.display = 'none';
        setTimeout(function () {
            $("#nama").focus();
        }, 600);
    }

    function edit(id) {
        $.ajax({
            url: "<?=base_url().$content.'/edit'?>",
            type: "POST",
            data: {id: id},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#modal_title").text("Edit Lokasi");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#nama").val(res.res_lokasi['nama']);
                    $("#tlp1").val(res.res_lokasi['tlp1']);
                    $("#alamat").val(res.res_lokasi['alamat']);
                    $("#lng").val(res.res_lokasi['lng']);
                    $("#lat").val(res.res_lokasi['lat']);
                    $("#jam_buka").val(res.res_lokasi['jam_buka']);
                    $("#jam_tutup").val(res.res_lokasi['jam_tutup']);
                    $("#preview").attr("src", "<?=base_url()?>"+res.res_lokasi['gambar']);
                    document.getElementById("img_preview").style.display = 'block';
                    initMap(18, parseFloat(res.res_lokasi['lat']), parseFloat(res.res_lokasi['lng']), 'map', 'edit');
                    $("#modal_lokasi").modal("show");
                    setTimeout(function () {
                        $("#nama").focus();
                    }, 600);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    function hapus(id) {
        if (confirm("Akan menghapus data?")) {
            $.ajax({
                url: "<?=base_url() . $content . '/hapus'?>",
                type: "POST",
                data: {id: id},
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        load_data(1);
                    } else {
                        alert("Error deleting data!");
                    }
                },
                error: function(xhr, status, error) {
                    alert("Data tidak bisa dihapus!");
                    console.log(xhr.responseText);
                }
            });
        }
    }

    $('#form_lokasi').validate({
        rules: {
            nama: {
                required: true
            },
            telepon: {
                required: true
            },
            alamat: {
                required: true
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama merk tidak boleh kosong!"
            },
            telepon:{
                required: "Telepon tidak boleh kosong!"
            },
            alamat:{
                required: "Alamat tidak boleh kosong!"
            }
        },
        errorElement : 'div',
        errorPlacement: function(error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            var myForm = document.getElementById('form_lokasi');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        $("#modal_lokasi").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_lokasi").on("hide.bs.modal", function () {
        document.getElementById("form_lokasi").reset();
        $( "#form_lokasi" ).validate().resetForm();
    });
</script>