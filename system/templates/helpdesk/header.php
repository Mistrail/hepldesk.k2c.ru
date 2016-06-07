<?
$tpl_url = URL . "/system/templates/" . TEMPLATE;
$is_adm = IS_ADMIN;
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="<?= $tpl_url ?>/script/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">   
        <link href="<?= $tpl_url ?>/script/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet">   
        
        <script src="<?= $tpl_url ?>/script/jquery/jquery.min.js" type=""></script>
        <script src="<?= $tpl_url ?>/script/bootstrap/js/bootstrap.min.js" type=""></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
        <script src="<?= $tpl_url ?>/script/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type=""></script>
        <title>Helpdesk</title>
    </head>
    <body>
        <!--[if IE 7]>
            <link href="<?= $tpl_url ?>/style/ie8_pathch.css" type="text/css" rel="stylesheet">     
        <![endif]-->
        <div class="navbar navbar-static-top navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-brand">Helpdesk</div>
                <ul class="nav navbar-nav navbar-right">
                    <?
                    $href = repackQuery(array(
                        "route" => "catalog/itemlist"
                            ), array("phone", "id", "action", "type", "done"));
                    ?>
                    <li><a href="?<?= $href ?>">Заявки</a></li> 
                    <?
                    $href = repackQuery(array(
                        "route" => "catalog/counter"
                            ), array("phone", "id", "action", "type", "done"));
                    ?>
                    <li><a href="?<?= $href ?>">Счетчик заявок</a></li>               
                    <? if ($is_adm): ?><li><a href="?route=catalog/stafflist">Мастера</a></li><? endif; ?>

                    <? /*  ?>
                      <li><a href="?route=catalog/clientlist">Клиенты</a></li>
                      <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Настройки <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                      <li><a href="?route=catalog/paymenttypes">Типы оплаты</a></li>
                      <li><a href="?route=catalog/ordertypes">Типы заявки</a></li>
                      <li><a href="?route=catalog/soursetypes">Источники</a></li>
                      <li><a href="?route=catalog/statustypes">Статусы</a></li>
                      </ul>
                      </li>
                      <? /* */ ?>

                    <li>
                        
                        <form class="navbar-form">
                            <? if(!empty($_GET["autologin"])): ?>
                            <input value="<?=$_GET["autologin"];?>" name="autologin" type="hidden">
                            <? endif; ?>    
                            <div class="form-group">
                                <?
                                $search = filter_input(INPUT_GET, "s") != "Сброс" ? filter_input(INPUT_GET, "s") : "";
                                ?>
                                <input value="<?= $search ?>" name="s" placeholder="Номер телефона" type="text" class="form-control input-sm">
                                <button type="submit" class="btn btn-sm btn-primary">Поиск</button>
                                <? if($search): ?>
                                <button type="submit" name="s" class="btn btn-sm btn-info">Сброс</button>
                                <? endif; ?>
                            </div>
                        </form>
                        <? /* */ ?>
                    </li>
                    <li class="btn-danger"><a style="color: #fff" href="<?= $logout ?>">Выход</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
