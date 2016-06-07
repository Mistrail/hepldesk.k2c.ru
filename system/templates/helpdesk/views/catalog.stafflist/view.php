<?
$link = false;
//$fields = $this->fields("stafflist");

$from = filter_input(INPUT_GET, "from");
$length = filter_input(INPUT_GET, "length");
$from = $from ? $from : 0;
$length = $length ? $length : 20;
$table = $this->getTable($from, $length);

if (!empty($_GET["action"]) && $_GET["action"] == "delete") {
    $this->deleteStaff($_GET["id"]);
    $link = repackQuery(array(
        "done" => ""
            ), array(
        "action", "id"
    ));
}

?>

<? if (isset($_GET["id"]) && !isset($_GET["action"]) && !$is_opened):    
    ?>
    <div id="staffcardupdate" class="modal fade">
        <div class="modal-dialog modal-lg">
    <? include __DIR__ . "/forms/staffUpdate.php"; ?>
        </div>
    </div>
    <script type="text/javascript">
        $("#staffcardupdate").modal("show");
    </script>
<? endif; ?>

<div id="staffcard" class="modal fade">
    <div class="modal-dialog modal-lg">
        <? include __DIR__ . "/forms/staff.php"; ?>
    </div>
</div>

<?
if ($link):
    ?><script type="text/javascript">
            location.href = '?<?= $link ?>'
    </script><?
    $link = false;
endif;
?>

<table class="table table-bordered table-striped table-hover">
    <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Процент</th>
        <th><a href="#" data-toggle="modal" data-target="#staffcard">Добавить</a></th>
    </tr>
    <? foreach ($table as $row) : 
        if($row["id"] == 0) continue;?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td>
            <a href="?<?= repackQuery(array("id" => $row["id"])) ?>"> <?= $row["name"] ?></a></td>
            <td><?= $row["phone"] ?></td>
            <td><?= $row["percent"] ?></td>
            <td><a href="?<?= repackQuery(array("action" => "delete", "id" => $row["id"])) ?>" >Удалить</a></td>
        </tr>
    <? endforeach; ?>
</table>
