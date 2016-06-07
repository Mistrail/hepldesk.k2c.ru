<?

$fields = $this->fields("tickets");

$keys = array(
    "id" => "ID заявки",
    "date" => "Дата звонка",
    //"date" => "Время заявки",
    //"city_id" => "Населенный пункт",    
    "contact" => "Контактное лицо",
    "phone" => "Телефон",    
    "address" => "Адрес",
    ////"source_id" => "Источник",
    "subject" => "Проблема",
    "type_id" => "Тип заявки",
    ////"staff_id" => "Мастер",
    //"status_id" => "Статус",
    //"summ" => "Сумма",
    //"summ_zch" => "Сумма ЗЧ",
    //"payment_type_id" => "Вид оплаты",
    //"payment" => "Оплачено",
    //"debt" => "Долг",
    //"bso" => "БСО",
    "comments" => "Комментарий",
    //"percent" => "%",
    //"payout" => "Выплачено",
    //"payout_date" => "Расчет",
    "payout_date_real" => "Время заявки",
    //"payout_date_real" => "Время",
    "bso" => "БСО"
);
$link = false;

$types = $this->db->query("SELECT * FROM @px:statustypes")->getRows();
$type = filter_input(INPUT_GET, "type");
$type = $type ? $type : "1";

$from = filter_input(INPUT_GET, "from");
$length = filter_input(INPUT_GET, "length");
$from = $from ? $from : 0;
$length = $length ? $length : 20;

if (!empty($_GET["action"]) && $_GET["action"] == "delete") {
    $this->deleteTicket($_GET["id"]);
    $link = repackQuery(array(
        "done" => ""
            ), array(
        "action", "id"
    ));
}
?>

<?
$is_adm = IS_ADMIN;
$sx = $is_adm ? "" : "_op";
$is_opened = false;
?>


<div id="ticketcard" class="modal fade">
    <div class="modal-dialog modal-lg">
        <? include __DIR__ . "/forms/ticket$sx.php"; ?>
    </div>
</div>
    
<? if (!empty($_GET["phone"]) && !isset($_GET["action"])):
    $is_opened = true; ?>
    <script type="text/javascript">
        $("#ticketcard").modal("show");
    </script>
<? endif; ?>
    
<? if (isset($_GET["id"]) && !isset($_GET["action"]) && !$is_opened):    
    ?>
    <div id="ticketcard-edit" class="modal fade">
        <div class="modal-dialog modal-lg">
    <? include __DIR__ . "/forms/ticketedit$sx.php"; ?>
        </div>
    </div>
    <script type="text/javascript">
        $("#ticketcard-edit").modal("show");
    </script>
<? endif; ?>


<?

if($link):
    ?><script type="text/javascript">
        location.href = '?<?=$link?>'
    </script><? 
    $link = false;
endif;

$errors = $this->error();
if (!empty($errors)):
    ?>
    <div class="alert alert-danger fade in">
        <h4>Внимание!<a href="#" class="close" data-dismiss="alert">&times;</a></h4>
        <hr>
        <? foreach ($errors as $error): ?>
            <? d($error) ?>
        <? endforeach; ?>
    </div>
<? endif; ?>

<ul class="nav nav-tabs">
    <?
    foreach ($types as $tab):
        $selected = $type == $tab["id"] ? "active" : "";
        $sql = "SELECT count(id) as cnt FROM @px:tickets WHERE status_id = " . $tab["id"];
        $s = filter_input(INPUT_GET, "s");
        if($s){
            $sql .= " AND phone LIKE '%$s%'";
        }
        $data = $this->db->query($sql)->getRow();
        $cnt = $data["cnt"];
        $href = repackQuery(array(
            "type" => $tab["id"]
        ), array("phone", "id", "action"));
        ?>
        <li class="<?= $selected; ?>"><a href="?<?=$href?>" class="tabtype-<?= $tab["code"] ?>"><?= $tab["title"] ?> <? if ($cnt): ?>(<?= $cnt ?>)<? endif; ?></a></li>
        
    <? endforeach; ?>
</ul>
<div class="clearfix">&nbsp;</div>
<? $total = $this->getPager($type, $from, $length); ?>
<div class="container-fluid">
    <div class="col-sm-9">
        <ul class="pagination">
            <?
            foreach ($total as $page):
                $link = repackQuery(array("from" => $page["from"]));
                $active = $page["current"] ? "active" : "";
                ?>
                <li class="<?= $active ?>"><a href="?<?= $link; ?>"><?= $page["page"] ?></a></li>
            <? endforeach; ?>
        </ul>
    </div>
    <div class="col-sm-3">
        <ul class="pagination pull-right">
            <li class="<?= ($length == 10 ? "active" : "") ?>"><a href="?<?= repackQuery(array("length" => 10)) ?>">по 10</a></li>
            <li class="<?= ($length == 20 ? "active" : "") ?>"><a href="?<?= repackQuery(array("length" => 20)) ?>">по 20</a></li>
            <li class="<?= ($length == 40 ? "active" : "") ?>"><a href="?<?= repackQuery(array("length" => 40)) ?>">по 40</a></li>
        </ul>
    </div>
</div>


<table class="table table-bordered table-striped table-hover">
    <tr>
        <th>№п/п</th>
        <? foreach ($keys as $field => $title): ?>
            <th><?= $title ?></th>
        <? endforeach; ?>
        <th style="text-align: center"><a href="#" data-toggle="modal" data-target="#ticketcard">Добавить</a></th>
    </tr>

    <?
    $search = filter_input(INPUT_GET, "s") != "Сброс" ? filter_input(INPUT_GET, "s") : false;
    $table = $this->getTable($type, $from, $length, $search);

    foreach ($table as $i => $row):
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><a href="?<?= repackQuery(array("id" => $row["id"])) ?>"><?= $row["id"] ?></a></td>
            <td><?= dateFormat($row["date"], " H:i d.m.Y") ?></td>
            <td><?= $row["contact"] ?></td>
            <td><?= $row["phone"] ?></td>
            <td><?= $row["address"] ?></td>
            <td><?= $row["subject"] ?></td>
            <td><?= $row["ordertype"] ?></td>
            <td><?= $row["comments"] ?></td>
            <td><?= dateFormat($row["payout_date_real"], " H:i d.m.Y") ?></td>
            <td><?= $row["bso"] ?></td>
            <th style="text-align: center"><? if($is_adm): ?><a href="?<?= repackQuery(array("action" => "delete", "id" => $row["id"])) ?>" >Удалить</a><? endif; ?></th>
        </tr>
    <? endforeach; ?>
</table>

<ul class="pagination">
    <?
    foreach ($total as $page):
        $link = repackQuery(array("from" => $page["from"]));
        $active = $page["current"] ? "active" : "";
        ?>
        <li class="<?= $active ?>"><a href="?<?= $link; ?>"><?= $page["page"] ?></a></li>
    <? endforeach; ?>
</ul>