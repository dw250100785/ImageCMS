<div class="d_n">{if $category['cat_id']}{$categoryId=$category['cat_id']}{else:}{$categoryId = $categoryDef['id']}{/if}</div>
<section class="mini-layout">
    <div class="frame_title clearfix">
        <div class="pull-left">
            <span class="help-inline"></span>
            <span class="title">SEO Эксперт</span>
        </div>
        <div class="pull-right">
            <div class="d-i_b">
                <a href="{$ADMIN_URL}/admin/components/init_window/mod_seoexpert/productsCategories" class="t-d_n m-r_15 pjax"><span class="f-s_14">←</span> <span class="t-d_u">{lang('Back','admin')}</span></a>
                <button onclick="" type="button" class="btn btn-small btn-primary formSubmit submitButton" data-form="#createDiscountForm" data-submit>
                    <i class="icon-ok icon-white"></i>Сохранить
                </button>
                {echo create_language_select($languages, $locale, "/admin/components/init_window/mod_seoexpert/productCategoryEdit/".$categoryId)}
            </div>
        </div>
    </div>
    <form method="post" action="/admin/components/init_window/mod_seoexpert/productCategoryEdit/{$categoryId}/{$locale}" enctype="multipart/form-data" id="createDiscountForm">
        <table class="table table-striped table-bordered table-condensed content_big_td module-cheep">
            <thead><tr><th colspan="6">{lang('Create','mod_seoexpert')}</th></tr></thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <div class="inside_padd discount-out">
                            <div class="form-horizontal">
                                <label class="">
                                    <span class="span4">
                                        {lang('Choose category','mod_seoexpert')}
                                    </span>
                                    <span class="span8 discount-name">
                                        <input id="autocomleteCategory" type="text" 
                                               autocomplete="off" 
                                               name='categoryNameTMP' 
                                               readonly="readonly"
                                               value="{if $category['settings']['categoryNameTMP']}{$category['settings']['categoryNameTMP']}{else:}{$categoryDef['name']}{/if}" />
                                        <input id="autocomleteCategoryId" type="hidden" 
                                               name='category_id' 
                                               value="{$categoryId}" />
                                    </span>
                                </label>
                                <hr/>
                                <label class="">
                                    <span class="span4">
                                        <span data-title="Variables, can use to:" class="popover_ref" data-original-title="">
                                            <i class="icon-info-sign"></i>
                                        </span>
                                        <div class="d_n">
                                            <b>%ID%</b> - ID наименования<br/>
                                            <b>%name%</b> - название продукта<br/>
                                            <b>%category%</b> - категория, которой присвоен продукт<br/>
                                            <b>%brand%</b> - бренд, которому присвоен продукт<br/>
                                            <b>%price%</b> - стоимость продукта<br/>
                                            <b>%CS%</b> - основная валюта витрины<br/>
                                            <b>%p_xxxx%</b> - виведет значение свойства, вместе xxxx - id свойства <br/>
                                        </div>
                                        Meta-title шаблон:
                                    </span>
                                    <span class="span8 discount-name"><input type="text" autocomplete="off" name='productTemplate' value="{$category['settings']['productTemplate']}" /></span>
                                </label>
                                <label class="">
                                    <span class="span4">
                                        <span data-title="Variables, can use to:" class="popover_ref" data-original-title="">
                                            <i class="icon-info-sign"></i>
                                        </span>
                                        <div class="d_n">
                                            <b>%ID%</b> - ID наименования<br/>
                                            <b>%name%</b> - название продукта<br/>
                                            <b>%desc%</b> - описание продукта<br/>
                                            <b>%category%</b> - категория, которой присвоен продукт<br/>
                                            <b>%brand%</b> - бренд, которому присвоен продукт<br/>
                                            <b>%price%</b> - стоимость продукта<br/>
                                            <b>%CS%</b> - основная валюта витрины<br/>
                                            <b>%p_xxxx%</b> - виведет значение свойства, вместе xxxx - id свойства <br/>
                                        </div>
                                        Meta-description шаблон:
                                    </span>
                                    <span class="span8 discount-name"><input type="text" autocomplete="off" name='productTemplateDesc' value="{$category['settings']['productTemplateDesc']}" /></span>
                                </label>

                                <label class="">
                                    <span class="span4">
                                        Длина описания:
                                    </span>
                                    <span class="span8 discount-name"><input type="text" autocomplete="off" name='productTemplateDescCount' value="{$category['settings']['productTemplateDescCount']}" /></span>
                                </label>

                                <label class="">
                                    <span class="span4">
                                        <span data-title="Переменные, которые можно использовать:" class="popover_ref" data-original-title="">
                                            <i class="icon-info-sign"></i>
                                        </span>
                                        <div class="d_n">
                                            <b>%name%</b> - название продукта<br/>
                                            <b>%category%</b> - категория, которой присвоен продукт<br/>
                                            <b>%brand%</b> - бренд, которому присвоен продукт<br/>
                                            <b>%p_xxxx%</b> - виведет значение свойства, вместе xxxx - id свойства <br/>
                                        </div>
                                        Meta-keywords шаблон:
                                    </span>
                                    <span class="span8 discount-name"><input type="text" autocomplete="off" name='productTemplateKey' value="{$category['settings']['productTemplateKey']}" /></span>
                                </label>

                                <label class="">
                                    <span class="span4">Активный:</span>
                                    <span style="width: 16px;" class="span1"><input name="useProductPattern"  {if $category['settings']['useProductPattern'] == 1} checked="checked" {/if}value="1" type="checkbox"/></span>
                                </label>

                                <label class="">
                                    <span class="span4">Использовать только для пустых метаданных:</span>
                                    <span style="width: 16px;" class="span1"><input name="useProductPatternForEmptyMeta"  {if $category['settings']['useProductPatternForEmptyMeta'] == 1} checked="checked" {/if}value="1" type="checkbox"/></span>
                                </label>
                            </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</section>