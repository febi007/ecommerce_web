<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?=base_url().$account['foto']?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?=$account['nama']?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?=$page=='dashboard'?'active':null?>">
                <a href="<?=base_url().'site'?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php $side_menu=array('0', 'situs', 'tentang_kami', 'cara_belanja', 'syarat', 'kebijakan', 'resolusi', 'sosial_media', 'video_home', 'video_share', 'karir', 'home_setting', 'home_slide' ,'harga_setting','shipping_service'); ?>
            <li class="treeview <?=array_search($page, $side_menu)?'active':null?>" <?php (((int)substr($access->access,0,10))==0 && ((int)substr($access->access,111,10))==0)?'style="display:none;"':null?>>
                <a href="#">
                    <i class="fa fa-gear"></i> <span>Pengaturan</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=$page=='situs'?'active':null?>" <?php (substr($access->access,0,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/situs'?>"><i class="fa fa-globe"></i> Situs</a></li>
                    <li class="<?=$page=='tentang_kami'?'active':null?>" <?php (substr($access->access,1,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/tentang_kami'?>"><i class="fa fa-info-circle"></i> Tentang Kami</a></li>
                    <li class="<?=$page=='cara_belanja'?'active':null?>" <?php (substr($access->access,2,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/cara_belanja'?>"><i class="fa fa-cart-plus"></i> Cara Belanja</a></li>
                    <li class="<?=$page=='syarat'?'active':null?>" <?php (substr($access->access,3,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/syarat'?>"><i class="fa fa-qrcode"></i> Syarat & Ketentuan</a></li>
                    <li class="<?=$page=='kebijakan'?'active':null?>" <?php (substr($access->access,4,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/kebijakan'?>"><i class="fa fa-book"></i> Kebijakan Privasi</a></li>
                    <li class="<?=$page=='resolusi'?'active':null?>" <?php (substr($access->access,5,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/resolusi'?>"><i class="fa fa-key"></i> Pusat Resolusi</a></li>
                    <li class="<?=$page=='sosial_media'?'active':null?>" <?php (substr($access->access,6,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/sosial_media'?>"><i class="fa fa-comments"></i> Sosial Media</a></li>
                    <!-- <li class="<?=$page=='video_home'?'active':null?>" <?php (substr($access->access,7,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/video_home'?>"><i class="fa fa-video-camera"></i> Video Home</a></li> -->
                    <li class="<?=$page=='home_slide'?'active':null?>" <?php (substr($access->access,111,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/home_slide'?>"><i class="fa fa-ellipsis-h"></i> Slide Home</a></li>
                     <li class="<?=$page=='karir'?'active':null?>" <?php (substr($access->access,9,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/karir'?>"><i class="fa fa-briefcase"></i> Karir</a></li>
                    <!-- <li class="<?=$page=='home_setting'?'active':null?>" <?php (substr($access->access,10,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/home_setting'?>"><i class="fa fa-home"></i> Home Setting</a></li> -->
                    <li class="<?=$page=='harga_setting'?'active':null?>" <?php (substr($access->access,8,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/harga_setting'?>"><i class="fa fa-money"></i> Harga Setting</a></li>
                    <li class="<?=$page=='shipping_service'?'active':null?>" <?php (substr($access->access,8,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'pengaturan/shipping_service'?>"><i class="fa fa-truck"></i> Service</a></li>
                </ul>
            </li>
            <?php $side_menu=array('0', 'data_user', 'user_level', 'member', 'bank', 'rekening', 'lokasi', 'berita', 'galeri', 'testimoni', 'kategori_berita', 'kurir'); ?>
            <li class="treeview <?=array_search($page, $side_menu)?'active':null?>" <?php (((int)substr($access->access,11,20))==0)?'style="display:none;"':null?>>
                <a href="#">
                    <i class="fa fa-list-alt"></i> <span>Master Data</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php $side_menu=array('0', 'data_user', 'user_level'); ?>
                    <li class="<?=array_search($page, $side_menu)?'active':null?>" <?php (substr($access->access,11,2)!=1)?'style="display:none;"':null?>>
                        <a href="#"><i class="fa fa-user"></i> User
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu active">
                            <li class="<?=$page=='data_user'?'active':null?>" <?php (substr($access->access,11,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/data_user'?>"><i class="fa fa-user-plus"></i> Data User</a></li>
                            <li class="<?=$page=='user_level'?'active':null?>" <?php (substr($access->access,12,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/user_level'?>"><i class="fa fa-user-secret"></i> User Level</a></li>
                        </ul>
                    </li>
                    <li><a href="<?=base_url().'masterdata/member'?>" <?php (substr($access->access,13,1)!=1)?'style="display:none;"':null?>><i class="fa fa-users"></i> Member</a></li>
                    <?php $side_menu=array('0', 'bank', 'rekening'); ?>
                    <li class="<?=array_search($page, $side_menu)?'active':null?>" <?php (substr($access->access,14,2)!=1)?'style="display:none;"':null?>>
                        <a href="#"><i class="fa fa-bank"></i> Bank
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu active">
                            <li class="<?=$page=='bank'?'active':null?>" <?php (substr($access->access,14,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/bank'?>"><i class="fa fa-bank"></i> Data Bank</a></li>
                            <li class="<?=$page=='rekening'?'active':null?>" <?php (substr($access->access,15,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/rekening'?>"><i class="fa fa-money"></i> Rekening Bank</a></li>
                        </ul>
                    </li>
                    <li class="<?=$page=='lokasi'?'active':null?>" <?php (substr($access->access,16,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/lokasi'?>"><i class="fa fa-map-marker"></i> Lokasi</a></li>
                    <?php $side_menu=array('0', 'berita', 'kategori_berita'); ?>
                    <li class="<?=array_search($page, $side_menu)?'active':null?>" <?php (substr($access->access,17,2)!=1)?'style="display:none;"':null?>>
                        <a href="#"><i class="fa fa-newspaper-o"></i> Berita
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu active">
                            <li class="<?=$page=='kategori_berita'?'active':null?>" <?php (substr($access->access,18,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/kategori_berita'?>"><i class="fa fa-server"></i> Kategori Berita</a></li>
                            <li class="<?=$page=='berita'?'active':null?>" <?php (substr($access->access,17,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/berita'?>"><i class="glyphicon glyphicon-book"></i> Data Berita</a></li>
                        </ul>
                    </li>
                    <li class="<?=$page=='kurir'?'active':null?>" <?php (substr($access->access,20,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'masterdata/kurir'?>"><i class="fa fa-truck"></i> Data Kurir</a></li>
                </ul>
            </li>
            <?php $side_menu=array('0', 'data_produk', 'group', 'kelompok', 'merk', 'promo', 'diskusi', 'ulasan', 'model', 'voucher', 'bestsellers'); ?>
            <li class="treeview <?=array_search($page, $side_menu)?'active':null?>" <?php (((int)substr($access->access,31,20))==0)?'style="display:none;"':null?>>
                <a href="#">
                    <i class="fa fa-th-large"></i> <span>Produk</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=$page=='data_produk'?'active':null?>" <?php (substr($access->access,31,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/data_produk'?>"><i class="fa fa-th-large"></i> Data Produk</a></li>
                    <li class="<?=$page=='group'?'active':null?>" <?php (substr($access->access,32,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/group'?>"><i class="fa fa-th-list"></i> Group</a></li>
                    <li class="<?=$page=='kelompok'?'active':null?>" <?php (substr($access->access,33,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/kelompok'?>"><i class="fa fa-th"></i> Kelompok</a></li>
                    <li class="<?=$page=='merk'?'active':null?>" <?php (substr($access->access,34,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/merk'?>"><i class="fa fa-registered"></i> Merk</a></li>
                    <li class="<?=$page=='promo'?'active':null?>" <?php (substr($access->access,35,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/promo'?>"><i class="glyphicon glyphicon-tag"></i> Promo</a></li>
                    <li class="<?=$page=='model'?'active':null?>" <?php (substr($access->access,38,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/model'?>"><i class="fa fa-support"></i> Model</a></li>
                    <li class="<?=$page=='voucher'?'active':null?>" <?php (substr($access->access,39,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/voucher'?>"><i class="fa fa-ticket"></i> Voucher</a></li>
                    <li class="<?=$page=='bestsellers'?'active':null?>" <?php (substr($access->access,40,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'produk/bestsellers'?>"><i class="fa fa-thumbs-o-up"></i> Bestsellers</a></li>
                </ul>
            </li>
            <?php $side_menu=array('0', 'data_stok', 'adjustment'); ?>
            <li class="treeview <?=array_search($page, $side_menu)?'active':null?>" <?php (((int)substr($access->access,51,20))==0)?'style="display:none;"':null?>>
                <a href="#">
                    <i class="glyphicon glyphicon-list-alt"></i> <span>Inventory</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=$page=='adjustment'?'active':null?>" <?php (substr($access->access,52,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'inventory/adjustment'?>"><i class="glyphicon glyphicon-transfer"></i> Adjustment</a></li>
                </ul>
            </li>
            <?php $side_menu=array('0', 'orders'); ?>
            <li class="treeview <?=array_search($page, $side_menu)?'active':null?>" <?php (((int)substr($access->access,71,20))==0)?'style="display:none;"':null?>>
                <a href="#">
                    <i class="fa fa-shopping-cart"></i> <span>Penjualan</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=$page=='orders'?'active':null?>" <?php (substr($access->access,71,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'penjualan/orders'?>"><i class="fa fa-cart-arrow-down"></i> Data Order</a></li>
                </ul>
            </li>
            <?php $side_menu=array('0', 'penjualan', 'feedback'); ?>
            <li class="treeview <?=array_search($page, $side_menu)?'active':null?>" <?php (((int)substr($access->access,91,20))==0)?'style="display:none;"':null?>>
                <a href="#">
                    <i class="fa fa-bar-chart"></i> <span>Laporan</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=$page=='penjualan'?'active':null?>" <?php (substr($access->access,91,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'laporan/penjualan'?>"><i class="fa fa-shopping-cart"></i> Penjualan</a></li>
                    <li class="<?=$page=='feedback'?'active':null?>" <?php (substr($access->access,92,1)!=1)?'style="display:none;"':null?>><a href="<?=base_url().'laporan/feedback'?>"><i class="fa fa-envelope"></i> Kritik & Saran</a></li>
                </ul>
            </li>
            <!--<li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Multilevel</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level One
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu active">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
            </li>-->
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
