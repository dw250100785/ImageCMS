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

<div class="frame-crumbs">
    <div class="container">
        {widget('path')}
    </div>
</div>

<div class="frame-inside page-category">
    <div class="container">
        <div class="right-catalog">
            <div class="f-s_0 title-category">
                <div class="frame-title">
                    <h1 class="d_i title">{echo $category->getName()}</h1>
                </div>
                <span class="count">({$totalProducts} {echo SStringHelper::Pluralize($totalProducts, array('товар','товара','товаров'))})</span>
            </div>
            {if $totalProducts == 0}
                <!--                Start. Empty category-->
                <div class="msg layout-highlight layout-highlight-msg">
                    <div class="info">
                        <span class="icon_info"></span>
                        <span class="text-el">По вашему запросу товаров не найдено</span>
                    </div>
                </div>
                <!--            End. Empty category-->
            {/if}
            <!--Start. Banners block-->
            {$banners = ShopCore::app()->SBannerHelper->getBannersCat(3,$category->id)}
            {if count($banners)}
                <div class="frame-baner-catalog frame-baner">
                    <section class="carousel_js baner container">
                        <div class="content-carousel">
                            <ul class="cycle">
                                {foreach $banners as $banner}
                                    <li>
                                        {if trim($banner.url)}
                                            <a href="{site_url($banner.url)}">
                                                <img data-src="{media_url('/uploads/shop/banners/'.$banner.image)}" alt="{ShopCore::encode($banner.name)}" />
                                            </a>
                                        {else:}
                                            <span>
                                                <img data-src="/uploads/shop/banners/{$banner.image}" alt="{ShopCore::encode($banner.name)}" />
                                            </span>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                            <span class="preloader-baner"></span>
                            <div class="pager"></div>
                        </div>
                        <div class="group-button-carousel">
                            <button type="button" class="prev arrow"></button>
                            <button type="button" class="next arrow"></button>
                        </div>
                    </section>
                </div>
            {/if}
            <!--End. Banners-->
            {include_tpl('catalogue_header')}
            {if $totalProducts > 0}
                <ul class="animateListItems items items-catalog {if $_COOKIE['listtable'] == 0} list{else:} table{/if}" id="items-catalog-main">
                    <!--                    include template for one product item-->
                    {include_tpl('one_product_item')}
                </ul>
                <!-- render pagination-->
                {$pagination}
            {/if}
        </div>
        <div class="filter">
            <!--        Load filter-->
            {$CI->load->module('smart_filter')->init()}
        </div>
        <!--widget for popular products in this category-->
    </div>
</div>
<div class="horizontal-carousel">
    {widget('popular_products')}
</div>
{widget('latest_news')}