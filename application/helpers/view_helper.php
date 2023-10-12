<?php
/**
 * Created by PhpStorm.
 * User: annashrulyusuf
 * Date: 19/11/2020
 * Time: 15:38
 */
if ( ! function_exists('temp_product'))
{
    function temp_product($text)
    {
        return /** @lang text */
            '<div class="col-lg-3 col-md-6 col-6 mt-4 pt-2">
                    <div data-aos="<?=$i%2==0?\'zoom-in\':\'zoom-out\'?>" data-aos-duration="1000" class="card shop-list border-0 position-relative" style="background:#f5f6fa!important;box-shadow: 3px 3px 0px 0 #2f55d4!important;">
                        <ul class="label list-unstyled mb-0">
                            <li><a href="javascript:void(0)" class="badge badge-pill badge-primary"><?=$diskon?></a></li>
                            <li><a href="javascript:void(0)" class="badge badge-pill badge-success">Best Seller</a></li>
                        </ul>
                        <div class="shop-image position-relative overflow-hidden rounded shadow">
                            <a href="shop-product-detail.html"><img src="<?=base_url().\'assets/frontend/\'?>images/shop/product/s1.jpg" class="img-fluid" alt=""></a>
                            <a href="shop-product-detail.html" class="overlay-work">
                                <img src="<?=base_url().\'assets/frontend/\'?>images/shop/product/s-1.jpg" class="img-fluid" alt="">
                            </a>
                            <ul class="list-unstyled shop-icons">
                                <li class="mt-2"><a href="javascript:void(0)" data-toggle="modal" data-target="#productview" class="btn btn-icon btn-pills btn-soft-primary"><i data-feather="eye" class="icons"></i></a></li>
                                <li class="mt-2"><a href="shop-cart.html" class="btn btn-icon btn-pills btn-soft-warning"><i data-feather="shopping-cart" class="icons"></i></a></li>
                            </ul>
                        </div>
                        <div class="card-body content pt-4 p-2">
                            <a href="<?=base_url().\'store/product?product_id=\'.$row[\'id_produk\']?>" class="text-dark product-name h6"><?=$row[\'nama\']?></a>
                            <div class="d-flex justify-content-between mt-1">
                                <h6 class="text-muted small font-italic mb-0 mt-1"><?=number_format($hrg_jual)?> <del class="text-danger ml-2"><?=number_format($hrgcoret)?></del> </h6>

                            </div>
                        </div>
                    </div>
                </div>';
    }
}

