<?php

class PaymentCreatePage
{
        
    public static $URL                      = '/admin/components/run/shop/paymentmethods/create';
    public static $Title                    = '.title';
    
    //блок створення
    public static $BlockCreateTitle         = '//section[@class="mini-layout"]//th';

    //кнопки
    public static $ButtonBack               = '.t-d_u';
    public static $ButtonCreate             = '.btn.btn-small.btn-success.formSubmit';
    public static $ButtonCreateExit         = '.btn.btn-small.formSubmit:nth-child(3)';
    
    //чекбокси
    public static $CheckActive              = ".niceCheck";

    //Лейбли
    public static $InputNameLabel           = '[for="Name"]';
    public static $InputDescriptionLabel    = '[for="Description"]';
    
    public static $CheckActiveLabel         = '.frame_label.active';
    
    public static $SelectCurrencyLabel      = '[for="CurrencyId"]';
    public static $SelectPaymentSystemLabel = '[for="inputRecCount"]';
    
    //поля для вводу
    public static $InputName                = '#Name';
    public static $InputDescription         = '#Description';
    
    //Селекти
    public static $SelectCurrency           = '#CurrencyId';
    public static $SelectPaymentSystem      = '[name="PaymentSystemName"]';
    
    public static function SelectCurrencyOption($row) {
        return "//select[@id='CurrencyId']/option[$row]";
    }
    
    public static function SelectPaymentSystemOption($row){
        return "//select[@name='PaymentSystemName']/option[$row]";
    }
}