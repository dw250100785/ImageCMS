<?php
class OrdersListPage
{   
       //Orders List Button
    
      public static $ListURLorders = "/admin/components/run/shop/orders/index";
      public static $ListTitle = "span.title";
      public static $ListButtFilter = "//div[2]/div/button[1]";
      public static $ListButtCancelFilter = "//section/div[1]/div[2]/div/a[1]";
      public static $ListButtSetStatuse = "//section/div[1]/div[2]/div/div/button";
      public static $ListButtSetStatusList = "//section/div[1]/div[2]/div/div/ul";
      public static $ListButtDelete = "//section/div[1]/div[2]/div/button[2]";
      public static $ListButtCreateOrder = "//section/div[1]/div[2]/div/a[2]";
      
      //Orders List Header
      
      public static $ListHeaderCheckBox = "//thead/tr[1]/th[1]/span/span";
      public static $ListHeaderID = "//th[2]/a";
      public static $ListHeaderStatus = "//th[3]/a";
      public static $ListHeaderDate = "//th[4]/a";
      public static $ListHeaderCustomer = "//th[5]";
      public static $ListHeaderProduct = "//th[6]";
      public static $ListHeaderPrice = "//th[7]/a";
      public static $ListHeaderPlaymentStatus = "//th[8]/a";
      
      //Orders List Fields
      
      public static $ListFieldID = "//td[2]/div/input";
      public static $ListFieldStatus = 'select[name="status_id"]';
      public static $ListFieldDateFrom = '//form[@id="ordersListFilter"]/section/div[2]/table/thead/tr[2]/td[4]/label[2]/span[2]/input';
      public static $ListFieldDateTO = '#dp1405345659189';
      public static $ListFieldCustomer = "//td[5]/input";
      public static $ListFieldProduct = './/*[@id="ordersFilterProduct"]';
      public static $ListFieldPriceFrom = "//td[7]/label[1]/span[2]/input";
      public static $ListFieldPriceTo = "//tr[2]/td[7]/label[2]/span[2]/input";
      public static $ListFieldPlaymentStatus = "//td[8]/select";
      
      //Orders List Rows
      
      public static $ListRow1CheckBox = "//tr[1]/td[1]/span/span";
      public static $ListRow1ID = "//tr[1]/td[2]/a";
      public static $ListRow1Status= "//section/div[2]/table/tbody/tr[1]/td[3]/span";
      public static $ListRow1DateFromTo = "//section/div[2]/table/tbody/tr[1]/td[4]";
      public static $ListRow1Customer = "//section/div[2]/table/tbody/tr[1]/td[5]/a";
      public static $ListRow1ButtInformation = "//section/div[2]/table/tbody/tr[1]/td[6]/div[1]/i";
      public static $ListRow1Price = "//section/div[2]/table/tbody/tr[1]/td[7]";
      public static $ListRow1SetStatus = "//section/div[2]/table/tbody/tr[1]/td[8]/span";
      
      //Orders List Pagination
      
      public static $ListPagiNameSelect = "//section/div[3]/div[2]";
      public static $ListPagiSelect = "//div/select";
      public static $ListPagiButtOffBack = ".//*[@id='ordersListFilter']/section/div[3]/div[2]/ul/li[1]/span";
      public static $ListPagiButtONBack = ".//*[@id='ordersListFilter']/section/div[3]/div[2]/ul/li[1]/a";
      public static $ListPagiButtOffForvard = ".//*[@id='ordersListFilter']/section/div[3]/div[2]/ul/li[2]/span";
      public static $ListPagiButtONForvard = ".//*[@id='ordersListFilter']/section/div[3]/div[2]/ul/li[2]/a";
      public static $ListPagiButt1ON = ".//*[@id='ordersListFilter']/section/div[3]/div[1]/ul/li[1]/a";
      public static $ListPagiButt1OFF= ".//*[@id='ordersListFilter']/section/div[3]/div[1]/ul/li[1]/span";
      public static $ListPagiButt2ON = ".//*[@id='ordersListFilter']/section/div[3]/div[1]/ul/li[2]/a";
      public static $ListPagi2OFF = ".//*[@id='ordersListFilter']/section/div[3]/div[1]/ul/li[2]/span";
      
      
       //Orders List Delete Window
       public static $DLTWindowWindow = ".modal.hide.fade.in";
       public static $DLTWindowTitle = "//div/div/div[1]/h3";
       public static $DLTWindowMessage = "//div/div/div[2]/p";
       public static $DLTWindowButtX = ".//*[@id='mainContent']/div/div/div[1]/button";
       public static $DLTWindowButtDelete = ".//*[@id='mainContent']/div/div/div[3]/a[1]";
       public static $DLTWindowButtCancel = ".//*[@id='mainContent']/div/div/div[3]/a[2]";
       
       
       
       // Creating Order Product Page
       
//       public static $CrtPURL = "/admin/components/run/shop/orders/create";
       public static $CrtPTitle = "span.title.w-s_n";
       
       //Creating Order Button "Product"
       
       public static $CrtPButtBack = "span.t-d_u";
       public static $CrtPButtCreate = "//section/div[1]/div[2]/div/button[1]";
       public static $CrtPButtCreateAndGoBack = "//button[2]";
       public static $CrtPButtProduct = "//div/section/div[2]/div/a[1]";
       public static $CrtPButtUser = "//div/section/div[2]/div/a[2]";
       public static $CrtPButtOrder = "//div/section/div[2]/div/a[3]";
       public static $CrtPButtAddToCart = "//div[3]/button";
       public static $CrtPButtOutStock = "//div[3]/button";
       public static $CrtPButtInBasket = "//div[3]/button";
       public static $CrtPButtDeleteTr1 = ".//*[@id='insertHere']/tr[1]/td[6]/button";
       
       //Creating Order Name Element "Product"
       
       public static $CrtPNameBlockProductSearch = ".//*[@id='addProduct']/thead/tr/th[1]";
       public static $CrtPNameFieldIDNameArticul = "//tbody/tr[1]/td[1]/div/label";
       public static $CrtPNameSelectCategory = "//tbody/tr[2]/td[1]/label/b";
       public static $CrtPNameSelectProduct = "//tbody/tr[2]/td[2]/label/b";
       public static $CrtPNameSelectVariant = "//tbody/tr[2]/td[3]/label/b";
       public static $CrtPNameColProduct = './/*[@id="productsInCart"]/thead/tr/th[1]';
       public static $CrtPNameColVariant = ".//*[@id='productsInCart']/thead/tr/th[2]";
       public static $CrtPNameColPrice = ".//*[@id='productsInCart']/thead/tr/th[3]";
       public static $CrtPNameColNumber = ".//*[@id='productsInCart']/thead/tr/th[4]";
       public static $CrtPNameColTotalPrice = ".//*[@id='productsInCart']/thead/tr/th[5]";
       public static $CrtPNameColDelete = ".//*[@id='productsInCart']/thead/tr/th[6]";
       
       
       //Creating Order Fields "Product"
       
       public static $CrtPFieldAmount = "//section/form/div/div[1]/div/table[2]/tbody/tr/td[4]/div/input";
       public static $CrtPFieldTotalPrice = "//section/form/div/div[1]/div/table[2]/tbody/tr/td[5]/span[1]";
       public static $CrtPFieldCommon = "//section/form/div/div[1]/div/table[2]/tfoot/tr/td[3]/span";
       
       
       //Creating Order Name Element "User"
       
       public static $CrtUNameBlockUser = "//thead/tr/th[1]";
       public static $CrtULinkCreate = "#collapsed";
       public static $CrtUNameFieldEmeil = "//div/div[1]/div[1]/label[3]";
       public static $CrtUNameBlockCreateUser = "//div/div[1]/div/label[1]/b";
       public static $CrtUNameFieldName = "//*[@id='collapseOne']/div/div[1]/div/label[2]";
       public static $CrtUUNameFieldEmeil = "//div[@id='collapseOne']/div/div/div/label[3]";
       public static $CrtUNameFieldPhone = "//div[@id='collapseOne']/div/div/div/label[4]";
       public static $CrtUNameFieldAddress = "//div[@id='collapseOne']/div/div/div/label[5]";
       public static $CrtUButtCreate = "//div[2]/div/div[2]/div/button";
       public static $CrtULinkSearchUser = "//tbody/tr/td/div/div[2]/div[1]/a";
       public static $CrtUNameBlockSearch = "//div[2]/div[2]/div/div/div/label/b";
       public static $CrtUNameFieldIDNameEmeil = "//div[2]/div[2]/div/div/div/label[2]";
       public static $CrtUNameColID = ".//*[@id='tab2']/div/table[2]/thead/tr/th[1]";
       public static $CrtUNameColEmeil = "//div[@id='tab2']/div/table[2]/thead/tr/th[2]";
       public static $CrtUNameColUser = "//div[@id='tab2']/div/table[2]/thead/tr/th[3]";
       public static $CrtUNameColAddress = "//div[@id='tab2']/div/table[2]/thead/tr/th[4]";
       public static $CrtUNameColPhone = "//div[@id='tab2']/div/table[2]/thead/tr/th[5]";
       public static $CrtUMessageAlertPresence = "//div[2]/div";
//       public static $CrtUMessageAlertText = "";
       
       //Creating Order Fields "User"
       
       public static $CrtUFieldName = "#createUserName";
       public static $CrtUFieldEmeil = "#createUserEmail";
       public static $CrtUFieldPhone = "#createUserPhone";
       public static $CrtUFieldAddress = "#createUserAddress";
       public static $CrtUFieldIDNameEmeil = "//div[2]/div[2]/div/div/div/label[2]";
       public static $CrtULinkEditUser = "#userNameforOrder";
       
       //Creating Order Name "Order"
       
       public static $CrtONameBlockOrderInfo = "//div[3]/table/thead/tr/th";
       public static $CrtONameBlockUser = "//tbody/tr/td/div/div/div[1]/label[1]/b";
       public static $CrtONameBlockDeviliry = "//div[2]/label[1]/b";
       public static $CrtONameBlockCertificate = "//label[4]/b";
       public static $CrtONameFieldName = "//td/div/div[1]/div[1]/label[2]";
       public static $CrtONameFieldFamily = "//td/div/div[1]/div[1]/label[3]";
       public static $CrtONameFieldPhone = "//td/div/div[1]/div[1]/label[5]";
       public static $CrtONameFieldEmeil = "//td/div/div[1]/div[1]/label[4]";
       public static $CrtONameFieldAddres = "//td/div/div[1]/div[1]/label[6]";
       public static $CrtONameFieldTotalPrice = "//tbody/tr/td/div/div/div[1]/div[1]/label";
       public static $CrtONameSelectDelivey = "//div[2]/label[2]";
       public static $CrtONameSelectPlaymant = "//div[2]/label[3]";
       public static $CrtONameFieldPromoCode = "//div[2]/label[5]";
       
       //Creating Order Fields "Order"
       
       public static $CrtOFieldName = "//div[3]/table/tbody/tr/td/div/div/div[1]/input[1]";
       public static $CrtOFieldFamily = "//td/div/div/div/input[2]";
       public static $CrtOFieldEmeil = "//td/div/div/div/input[3]";
       public static $CrtOFieldPhone = "//td/div/div/div/input[4]";
       public static $CrtOFieldAddres = "//input[5]";
       public static $CrtOFieldTotalPrice = "//td/div/div/div/div/input";
       public static $CrtOselectDelivery = "//div[2]/select[1]";
       public static $CrtOSelectPlayment = "//select[2]";
       public static $CrtOFieldPromocode = "//div[2]/input";
       public static $CrtOButtUpdate = "//div[2]/button";    
       
       
       
       //Creating Order "Create Category 'Defolts' ".
       
       public static $CrtCategoryPageURL = "/admin/components/run/shop/categories/create";
       public static $CrtCategoryFieldName = "#inputName";
       public static $CrtCategorySelectMenu = "//div[1]/div[2]/div/div/a";
       public static $CrtCategorySelectMenuInput = "//section/form/div[1]/table[1]/tbody/tr/td/div/div[1]/div[2]/div/div/div/div/input";
       public static $CrtCategorySelectMenuSetSearch = "//section/form/div[1]/table[1]/tbody/tr/td/div/div[1]/div[2]/div/div/div/ul/li";
       public static $CrtCategoryButtonSaveandBack = "//button[2]";
       
       
       //Creating Order "Create Products 'Defolt' ".
       
       public static $CrtProductPageURL = "/admin/components/run/shop/products/create";
       public static $CrtProductNameProduct = "//table[1]/tbody/tr/td/div/div/div[1]/div[1]/div/input";
       public static $CrtProductPriceProduct = "//tbody/tr/td/div/div/div[1]/div[4]/table/tbody/tr/td[2]/input";
       public static $CrtProductArticleProduct = "//tbody/tr/td/div/div/div[1]/div[4]/table/tbody/tr/td[4]/input";
       public static $CrtProductAmountProduct = "//table[1]/tbody/tr/td/div/div/div[1]/div[4]/table/tbody/tr[1]/td[5]/input";
       public static $CrtProductCategoryProductSelectField = "//tbody/tr/td/div/div/div[2]/div/div[2]/div/div/a";
       public static $CrtProductCategoryProductSelectInput = "//tbody/tr/td/div/div/div[2]/div/div[2]/div/div/div/div/input";
       public static $CrtProductCategoryProductSetSelect = "//tbody/tr/td/div/div/div[2]/div/div[2]/div/div/div/ul/li";
       public static $CrtProductVariantButtonADD = "//tbody/tr/td/div/div/div[1]/div[4]/table/tfoot/tr/td/div/button";
       public static $CrtProductVariantFieldName = "//tbody/tr[2]/td[1]/div/input[3]";
       public static $CrtProductVariantFieldPrice = "//body/div[1]/div[5]/section/form/div/div[2]/div[1]/table[1]/tbody/tr/td/div/div/div[1]/div[4]/table/tbody/tr[2]/td[2]/input";
       public static $CrtProductVariantFieldArticle = "//body/div[1]/div[5]/section/form/div/div[2]/div[1]/table[1]/tbody/tr/td/div/div/div[1]/div[4]/table/tbody/tr[2]/td[4]/input";
       public static $CrtProductVariantFieldAmount = "//body/div[1]/div[5]/section/form/div/div[2]/div[1]/table[1]/tbody/tr/td/div/div/div[1]/div[4]/table/tbody/tr[2]/td[5]/input";
       public static $CrtProductButtonSaveandBack = "//body/div[1]/div[5]/section/div/div[2]/div/button[2]";
       
       
       
    //Defolt Values For Created Category
       
       public static $CrtCatName1 = "Основная КаТеГоРиЯ";
       public static $CrtCatName2= "First Дочерная";
       public static $CrtCatName3 = "Second ДоЧеРнАя";
       public static $CrtCatName1ForSearch = "Основ";
       public static $CrtCatName2ForSearch = "-First";
       public static $CrtCatName3ForSearch = "--Second";
       
    //Defolt Values For Created Category
       
    public static $CrtPrdNameMin = "....."; 
    public static $CrtPrdNameMax = "qwertyuioasdfghjklzxcvbnmйцукенгшщзхъфывапролдджэячсмиттьбюQWERTYUIOPASDFGHJKLZXCVBNMЙЦУКЕННГШГШЩЗФЫВАПРОЛДЖЭЯЧСМИТЬБЮqwertyuioasdfghjklzxcvbnmйцукенгшщзхъфывапролдджэячсмиттьбюQWERTYUIOPASDFGHJKLZXCVBNMЙЦУКЕННГШГШЩЗФЫВАПРОЛДЖЭЯЧСМИТЬБЮqwertyuioasdfghjklzxcvbnmйцукенгшщзхъфывапролдджэячсмиттьбюQWERTYUIOPASDFGHJKLZXCVBNMЙЦУКЕННГШГШЩЗФЫВАПРОЛДЖЭЯЧСМИТЬБЮqwertyuioasdfghjklzxcvbnmйцукенгшщзхъфывапролдджэячсмиттьбюQWERTYUIOPASDFGHJKLZXCVBNMЙЦУКЕННГШГШЩЗФЫВАПРОЛДЖЭЯЧСМИТЬБЮQWEQWEQWEQWEQWEASDASDZXCASDQ"; 
    public static $CrtPrdPriceMin = "1"; 
    public static $CrtPrdPriceMax = "10000000000000"; 
    public static $CrtPrdArticleMin = "R2D2"; 
    public static $CrtPrdArticleMax = "АааРррТттИииКккУууЛллМммАааКккСссАааРррТттИииКккУууЛллМммАааКккСссАааРррТттИииКккУууЛллМммАааКккСссАааРррТттИииКккУууЛллМммАааКккСссАааРррТттИииКккУууЛллМммАааКккСссАааРррТттИииКккУууЛллМммАааКккСссАааРррТттИииКккУууЛллМммАааКккСссАааРррТттИииКккУууЛллМмм"; 
    public static $CrtPrdAmountMin = "0"; 
    public static $CrtPrdAmountMax = "2147483647"; 
    public static $CrtVarNameMin = "VoP"; 
    public static $CrtVarNameMax = "фффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффффф"; 
    public static $CrtVarPriceMin = "1"; 
    public static $CrtVarPriceMax = "10000000000000"; 
    public static $CrtVarArticleMin = "D3R3"; 
    public static $CrtVarArticleMax = "ъъъъъъъъВариантаоооооооооооАРТИКУЛееееееееееееееМММаааКСъъъъъъъъВариантаоооооооооооАРТИКУЛееееееееееееееМММаааКСъъъъъъъъВариантаоооооооооооАРТИКУЛееееееееееееееМММаааКСъъъъъъъъВариантаоооооооооооАРТИКУЛееееееееееееееМММаааКСееееееееееееМММаааКСМаааКСйцууу"; 
    public static $CrtVarAmountMin = "0"; 
    public static $CrtVarAmountMax = "2147483647"; 
}