{#
/**
* @file - template for displaying shop category page
* Variables
*   $category: (object) instance of SCategory
*       $category->getDescription(): method which returns category description
*       $category->getName(): method which returns category name according to currenct locale
*   $products: PropelObjectCollection of (object)s instance of SProducts
*       $product->firstVariant: variable which contains the first variant of product
*       $product->firstVariant->toCurrency(): method which returns price according to current currencya and format
*   $totalProducts: integer contains products count
*   $pagination: string variable contains html code for displaying pagination
*   $pageNumber: integer variable contains the current page number
*   $banners: array of (object)s of SBanners which have to be displayed in current page
*/
#}

{$Comments = $CI->load->module('comments')->init($products)}
<article class="container">
    <!-- Show Banners in circle -->
    <div class="mainFrameBaner">
        <section class="container">
            {$banners = ShopCore::app()->SBannerHelper->getBannersCat(3,$category->id)}
            {if count($banners)}
            <div class="frame_baner">
                <ul class="cycle">
                    {foreach $banners as $banner}
                    <li>
                        <a href="{echo $banner['url']}">
                            <img src="/uploads/shop/banners/{echo $banner['image']}" alt="banner"/>
                        </a>
                    </li>
                    {/foreach}
                </ul>
                <div class="pager"></div>
                <button class="next" type="button"></button>
                <button class="prev" type="button"></button>
            </div>
            {/if}
        </section>
    </div>
    <!-- Show banners in circle -->

    <!-- Block for bread crumbs with a call of shop_helper function to create it according to category model -->
    {widget('path')}

    <!-- main category page content -->
    <div class="row">
        <!-- here filter tpl is including -->
        {include_tpl('filter')}

        <!-- catalog container -->
        <div class="span9 right">

            <!-- category title and products count output -->
            <h1 class="d_i">{echo ShopCore::encode($category->getName())}</h1>
            {if count($products)>0}
            <span class="c_97">{lang('s_found')} {echo $totalProducts} {echo SStringHelper::Pluralize($totalProducts, array(lang('s_product_o'), lang('s_product_t'), lang('s_product_tr')))}</span>
            <div class="clearfix t-a_c frame_func_catalog">

                <!-- sort block -->
                <div class="f_l">
                    <span class="v-a_m">{lang('s_order_by')}:</span>
                    <div class="lineForm w_170 sort">
                        <select class="sort" id="sort" name="order">
                            <option value="" {if !$order_method}selected="selected"{/if}>-{lang('s_no')}-</option>
                            <option value="rating" {if $order_method=='rating'}selected="selected"{/if}>{lang('s_po')} {lang('s_rating')}</option>
                            <option value="price" {if $order_method=='price'}selected="selected"{/if}>{lang('s_dewevye')}</option>
                            <option value="price_desc" {if $order_method=='price_desc'}selected="selected"{/if} >{lang('s_dor')}</option>
                            <option value="hit" {if $order_method=='hit'}selected="selected"{/if}>{lang('s_popular')}</option>
                            <option value="hot" {if $order_method=='hot'}selected="selected"{/if}>{lang('s_new')}</option>
                            <option value="action" {if $order_method=='action'}selected="selected"{/if}>{lang('s_action')}</option>
                        </select>
                    </div>
                </div>

                <!-- products on page count -->
                <div class="f_r">
                    <span class="v-a_m">{lang('s_products_per_page')}:</span>
                    <div class="lineForm w_70 sort">
                        <select class="sort" id="sort2" name="user_per_page">
                            <option value="12" {if ShopCore::$_GET['user_per_page']=='12'}selected="selected"{/if} >12</option>
                            <option value="24" {if ShopCore::$_GET['user_per_page']=='24'}selected="selected"{/if} >24</option>
                            <option value="36" {if ShopCore::$_GET['user_per_page']=='36'}selected="selected"{/if} >36</option>
                            <option value="48" {if ShopCore::$_GET['user_per_page']=='48'}selected="selected"{/if} >48</option>
                        </select>
                    </div>
                </div>

                <!-- selecting product list type -->
                <div class="groupButton list_pic_btn">
                    <button type="button" class="btn showAsTable {if $_COOKIE['listtable'] != 1}active{/if}"><span class="icon-cat_pic"></span><span class="text-el">{lang('s_in_images')}</span></button>
                    <button type="button" class="btn showAsList {if $_COOKIE['listtable'] == 1}active{/if}"><span class="icon-cat_list"></span><span class="text-el">{lang('s_in_list')}</span></button>
                </div>
            </div>

            <!-- displaying category description if page number is 1 -->
            {if $page_number == 1 && $category->getDescription() != '' && $category->getDescription() != ' ' && $category->getDescription() != null}
            <div class="grey-b_r-bord">
                <p><span style="font-weight:bold">{echo ShopCore::encode($category->getName())}</span> &mdash; {echo $category->getDescription()}</p>
            </div>
            {/if}

            <!-- rendering product list if products count more than 0 -->


            <!-- product list container -->
            <ul class="items items_catalog {if $_COOKIE['listtable'] == 1}list{/if}" data-radio-frame>

                <!-- starts loop for array with products -->
                {foreach $products as $product}

                {$product->firstVariant}
                <!-- product block -->
                <!-- check if product is in stock -->
                <li class="{if (int)$product->getallstock() == 0}not-avail{/if} span3">

                    <!-- product info block -->
                    <div class="description">
                        <div class="frame_response">

                            <!-- displaying product's rate -->
                            {$CI->load->module('star_rating')->show_star_rating($product)}

                            <!-- displaying comments count -->
                            {if $Comments[$product->getId()][0] != '0' && $product->enable_comments}
                            <a href="{shop_url('product/'.$product->url.'#comment')}" class="count_response">
                                {echo $Comments[$product->getId()]}
                            </a>
                            {/if}
                        </div>

                        <!-- displaying product name -->
                        <a href="{shop_url('product/'.$product->getUrl())}" class="prodName">{echo ShopCore::encode($product->getName())}</a>
                        {if $product->firstVariant->getNumber() != ''}<span class="c_97 numberCP" id="number">(Артикул: {echo $product->firstVariant->getNumber()}) </span>{/if}
                        {if $product->hasDiscounts()}
                        <span class="d_b old_price">
                            <!--
                            "$model->firstVariant->toCurrency('OrigPrice')" or $model->firstVariant->getOrigPrice()
                            output price without discount
                             To display the number of abatement "$model->firstVariant->getNumDiscount()"
                            -->
                            <span class="f-w_b priceOrigVariant">{echo $product->firstVariant->toCurrency('OrigPrice')}</span>

                            {$CS}
                        </span>                           
                        {/if}
                        <!-- displaying products first variant price and currency symbol -->
                        <div class="price price_f-s_16"><span class="f-w_b priceVariant">{echo $product->firstVariant->toCurrency()}</span> {$CS}&nbsp;&nbsp;<span class="second_cash"></span></div>

                        {if count($product->getProductVariants()) > 1}
                        <div class=" d_i-b v-a_b m-r_30 p-b_10" id="variantProd">
                            <div class="lineForm w_170">
                                <select id="variantSwitcherCategory" name="variant">
                                    {foreach $product->getProductVariants() as $key => $pv}
                                    <option value="{echo $pv->getId()}">
                                        {if $pv->getName()}
                                        {echo ShopCore::encode($pv->getName())}
                                        {else:}
                                        {echo ShopCore::encode($product->getName())}
                                        {/if}                                                   
                                    </option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        {/if}
                        <!-- End. Output of all the options -->
                        <!-- displaying buy button according to its availability in stock -->

                        {if (int)$product->getallstock() == 0}

                        <!-- displaying notify button -->
                        <button data-placement="bottom right"
                                data-place="noinherit"
                                data-duration="500"
                                data-effect-off="fadeOut"
                                data-effect-on="fadeIn"
                                data-drop=".drop-report"
                                data-prodid="{echo $product->getId()}"
                                type="button"
                                class="btn btn_not_avail variant">
                            <span class="icon-but"></span>
                            <span class="text-el">{lang('s_message_o_report')}</span>
                        </button>
                        {/if}
                        <!-- Start. Collect information about Variants, for future processing -->
                        {foreach $product->getProductVariants() as $key => $pv}
                        {if $pv->getStock() > 0}
                        <button  {if $key != 0}style="display:none"{/if} 
                            class="btn btn_buy variant_{echo $pv->getId()} variant" 
                            type="button" 
                            data-id="{echo $pv->getId()}"
                            data-prodid="{echo $product->getId()}"
                            data-varid="{echo $pv->getId()}" 
                            data-price="{echo $pv->toCurrency()}" 
                            data-name="{echo ShopCore::encode($product->getName())}"
                            data-vname="{echo ShopCore::encode($pv->getName())}"
                            data-maxcount="{echo $pv->getstock()}"
                            data-number="{echo $pv->getNumber()}"
                            data-img="{echo $pv->getSmallPhoto()}"
                            data-url="{echo shop_url('product/'.$product->getUrl())}"
                            data-origPrice="{if $product->hasDiscounts()}{echo $pv->toCurrency('OrigPrice')}{/if}"
                            data-stock="{echo $pv->getStock()}"
                            >
                            {lang('s_buy')}
                    </button>
                    {else:}
                    <button
                        style="display: none;" 
                        data-placement="top right"
                        data-place="noinherit"
                        data-duration="500"
                        data-effect-off=    "fadeOut"
                        data-effect-on="fadeIn"
                        data-drop=".drop-report"
                        data-prodid="{echo $product->getId()}" 
                        type="button"
                        class="btn btn_not_avail variant_{echo $pv->getId()} variant">
                        <span class="icon-but"></span>
                        <span class="text-el">{lang('s_message_o_report')}</span>
                    </button>
                    {/if}
                    {/foreach}
                    <!-- End. Collect information about Variants, for future processing -->


                    <div class="d_i-b">

                        <!-- to compare button -->
                        <button class="btn btn_small_p toCompare"  
                                data-prodid="{echo $product->getId()}"  
                                type="button" 
                                data-title="{lang('s_add_to_compare')}"
                                data-sectitle="{lang('s_in_compare')}"
                                data-rel="tooltip">
                            <span class="icon-comprasion_2"></span>
                            <span class="text-el">{lang('s_add_to_compare')}</span>
                        </button>

                        <!-- to wish list button -->
                        <button class="btn btn_small_p toWishlist" 
                                data-prodid="{echo $product->getId()}" 
                                data-varid="{echo $product->firstVariant->getId()}"  
                                type="button" 
                                data-title="{lang('s_add_to_wish_list')}"
                                data-sectitle="{lang('s_in_wish_list')}"
                                data-rel="tooltip">
                            <span class="icon-wish_2"></span>
                            <span class="text-el">{lang('s_add_to_wish_list')}</span>
                        </button>
                    </div>

                    <div class="short_description">
                        {echo ShopCore::app()->SPropertiesRenderer->renderPropertiesInlineNew($product->getId())}
                    </div>

                </div>

                <!-- displaying products small mod image -->

                <div class="photo-block">
                    <a href="{shop_url('product/'.$product->getUrl())}" class="photo">
                        <figure>
                            <span class="helper"></span>
                            <img src="{echo $product->firstVariant->getSmallPhoto()}" 
                                 alt="{echo ShopCore::encode($product->getName())} - {echo $product->getId()}"/>
                        </figure>
                    </a>
                </div>

                <!-- creating hot bubble for products image if product is hot -->
                {if $product->getHot()}
                <span class="top_tovar nowelty">{lang('s_shot')}</span>
                {/if}

                <!-- creating hot bubble for products image if product is action -->
                {if $product->getAction()}
                <span class="top_tovar promotion">{lang('s_saction')}</span>
                {/if}

                <!-- creating hot bubble for products image if product is hit -->
                {if $product->getHit()}
                <span class="top_tovar discount">{lang('s_s_hit')}</span>
                {/if}
            </li>
            {/foreach}
        </ul>
        {else:}
        <div class="alert alert-search-result">
            <div class="title_h2 t-a_c">Категория пуста</div>
        </div>
        {/if}
        <!-- pagination variable from category.php controller -->
        {$pagination}
    </div>
</div>

</article>

{widget('view_product')}
<script type="text/javascript" src="{$THEME}js/cusel-min-2.5.js"></script>
{if widget('view_product') != NULL}
<script type="text/javascript" src="{$THEME}js/jquery.jcarousel.min.js"></script>
{/if}