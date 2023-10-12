<style>
    .direct-chat-primary .ts>.direct-chat-text:after, .direct-chat-primary .ts>.direct-chat-text:before {
        border-right-color: #00a65a;
    }
    .direct-chat-primary .ts>.direct-chat-text {
        background: #00a65a;
        border-color: #00a65a;
        color: white;
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
                                    <input type="text" name="table_search" class="form-control pull-right" onkeyup="return cari(event, $(this).val())" value="<?=isset($this->session->search['any'])?$this->session->search['any']:''?>" placeholder="Cari Produk">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <div id="result_table"></div>
    <div class="box">
        <div align="center" id="pagination_link"></div>
    </div>
</section>

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
                $('#result_table').html(data.result_data);
                $('#pagination_link').html(data.pagination_link);
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function load_diskusi(id, diskusi) {
        $.ajax({
            url: "<?=base_url().$content.'/get_diskusi'?>",
            type: "POST",
            data: {id_produk: id, id_diskusi: diskusi},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#id_diskusi"+id).val(diskusi);
                    $("#id_produk"+id).val(id);
                    $('#cont_diskusi' + id).html(res.res_diskusi);
                    $('#cont_comment' + id).html(res.res_comment);
                }
            }
        });
    }

    function load_comment(id, id_produk) {
        $.ajax({
            url: "<?=base_url() . 'api/get_comment/bo'?>",
            type: "POST",
            data: {id_diskusi: id},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    var listComment = '';
                    var comment = res.res_comment;
                    if (comment.length > 0) {
                        for (var y=0; y<comment.length; y++) {
                            if (comment[y]['verify'] === 'admin_idk') {
                                listComment += '' +
                                    '<div class="direct-chat-msg right">' +
                                    '<div class="direct-chat-info clearfix" id="cont_status'+replace_slash(comment[y]['id_diskusi_produk'])+'">' +
                                    '<span class="pull-right" title="'+(comment[y]['status']=='0'?'Tampilkan':'Sembunyikan')+'" style="color: '+(comment[y]['status']=='0'?'red':'green')+'" onclick="change_status(\''+comment[y]['id_diskusi_produk']+'\', \''+comment[y]['status']+'\')"><i class="fa '+(comment[y]['status']=='0'?'fa-times':'fa-check')+'"></i></span>' +
                                    '</div>' +
                                    '<div class="direct-chat-info clearfix">' +
                                    '<span class="direct-chat-name pull-right">'+comment[y]['nama_comment']+'</span>' +
                                    '<span class="direct-chat-timestamp pull-left">'+moment(comment[y]['tgl_comment']).format('DD MMM YYYY hh:mm A')+'</span>' +
                                    '</div>' +
                                    '<img class="direct-chat-img" src="'+comment[y]['foto']+'" alt="Message User Image">' +
                                    '<div class="direct-chat-text">' +
                                    comment[y]['comment'] +
                                    '</div>' +
                                    '</div>';
                            } else {
                                listComment += '' +
                                    '<div class="direct-chat-msg">' +
                                    '<div class="direct-chat-info clearfix" id="cont_status'+replace_slash(comment[y]['id_diskusi_produk'])+'">' +
                                    '<span class="pull-right" title="'+(comment[y]['status']=='0'?'Tampilkan':'Sembunyikan')+'" style="color: '+(comment[y]['status']=='0'?'red':'green')+'" onclick="change_status(\''+comment[y]['id_diskusi_produk']+'\', \''+comment[y]['status']+'\')"><i class="fa '+(comment[y]['status']=='0'?'fa-times':'fa-check')+'"></i></span>' +
                                    '</div>' +
                                    '<div class="direct-chat-info clearfix">' +
                                    '<span class="direct-chat-name pull-left">'+comment[y]['nama_comment']+'</span>' +
                                    '<span class="direct-chat-timestamp pull-right">'+moment(comment[y]['tgl_comment']).format('DD MMM YYYY hh:mm A')+'</span>' +
                                    '</div>' +
                                    '<img class="direct-chat-img" src="'+comment[y]['foto']+'" alt="Message User Image">' +
                                    '<div class="direct-chat-text">' +
                                    comment[y]['comment'] +
                                    '</div>' +
                                    '</div>';
                            }
                        }
                        $("#cont_comment"+id_produk).html(listComment);
                    }
                }
            }
        });
    }

    function comment(id) {
        $('#form_comment'+id).validate({
            rules: {
                komentar: {
                    required: true
                }
            },
            //For custom messages
            messages: {
                komentar:{
                    required: "Komentar tidak boleh kosong!"
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
                $.ajax({
                    url: "<?=base_url().'api/comment/admin'?>",
                    type: "POST",
                    data: $("#form_comment"+id).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                    },
                    complete: function() {
                        $('.first-loader').remove();
                    },
                    success: function (res) {
                        if (res.status) {
                            document.getElementById("form_comment"+id).reset();
                            load_comment(res.id_comment, id);
                        }
                    }
                })
            }
        });
    }

    function change_status(id, st) {
        $.ajax({
            url: "<?=base_url().$content.'/change_status'?>",
            type: "POST",
            data: {id_diskusi: id, status: st},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                $("#cont_status"+replace_slash(id)).html(res.status);
            }
        });
    }
</script>